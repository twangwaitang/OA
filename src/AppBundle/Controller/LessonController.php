<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use Symfony\Component\Validator\Constraints\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class LessonController extends BaseController
{


    /**
     * @Route("admin/lesson", name="lesson")
     */
    public function getLessons()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Lesson');
            $lessons = $repository->findAll();
            return $this->json($lessons);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("admin/lesson/one", name="lesson_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Lesson');
            $lesson = $repository->find($request->request->get('id'));
            return $this->json($lesson);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("admin/course/{courseid}/lesson/{lessonid}/questions", name="lesson_question")
     */
    public function LessonAddQuestions($courseid,$lessonid,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')->find($lessonid);
            $course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($courseid);
            $questions = $lesson->getQuestions();
            $questions = $this->Pagination($questions,$request);

            return $this->render('admin/lesson_question.html.twig', [
                'questions' =>$questions,
                'lesson'    =>$lesson,
                'course'    =>$course,
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("/admin/lesson/add_question_to_lesson", name="add_question_to_lesson")
     */
    public function addQuestionToLesson(Request $request)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lesson_id'));
            $rows = $request->request->get('rows');
            if (!$lesson) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            foreach ($rows as $row){
                $question = $em->getRepository('AppBundle:Questions')->find($row['id']);
                $question->addLesson($lesson);
                $validator = $this->get('validator');
                $errors = $validator->validate($question);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                }
            }

            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
        catch (\Exception $e)
        {
            return new JsonResponse(array('message' => '题目重复输入', 'code' => 0));
        }
    }
    /**
     * @Route("/admin/lesson/remove_question_to_lesson", name="remove_question_to_lesson")
     */
    public function removeQuestionToLesson(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lesson_id'));
        $question = $em->getRepository('AppBundle:Questions')->find($request->request->get('question_id'));
        if (!$lesson) {
            throw $this->createNotFoundException(
                '没有找到数据'
            );
        }
        $lesson->removeQuestion($question);
        $question->removeLesson($lesson);
        $em->persist($lesson);
        $em->flush();
        return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }


    /**
     * @Route("admin/lesson/upload", name="upload_lesson")
     */
    public function lessonUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('lesson_upload_middle_path'));
        $lesson->setVideo($fileName);
        $lesson->setDuration($request->request->get('duration'));
        $em->persist($lesson);
        $validator = $this->get('validator');
        $errors = $validator->validate($lesson);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        } else {
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
    }


    public function fileContraints($file){
        $fileContraints = new File(array(
            'maxSize' => '200M',
            'maxSizeMessage' => '文件大小不能超过{{ limit }}Mb'
        ));

        // $fileContraints->mimeTypes = array('application/vnd.ms-excel');
        //$fileContraints->mimeTypesMessage = '文件类型不匹配';
        $errors = $this->get('validator')->validate($file, $fileContraints);
        return $errors;
    }
    /**
     * @Route("admin/lesson/addwebvideo", name="add_web_video")
     */
    public function addWebVideo(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lesson_id'));
            $lesson->setVideo($request->request->get('video'));
            $em->persist($lesson);
            $validator = $this->get('validator');
            $errors = $validator->validate($lesson);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            }
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }
    /**
     * @Route("/admin/lesson/addmakers", name="add_makers")
     */
    public function addMakers(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $maker = Array();
            $em = $this->getDoctrine()->getManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
            foreach ($request->request->get('pramas') as $makers) {
                array_push($maker,$makers['time']);
            }
//            var_dump($maker);
//            die();
            $lesson->setMakers($maker);
            $em->persist($lesson);
            $validator = $this->get('validator');
            $errors = $validator->validate($lesson);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            }
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }
    /**
     * @Route("admin/lesson/add", name="add_lesson")
     */
    public function addLesson(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $chapter = $em->getRepository('AppBundle:Chapter')->find($request->request->get('chapter_id'));
            foreach ($request->request->get('name') as $name) {
                $lesson = new Lesson();
                $lesson->setName($name);
                $lesson->setChapter($chapter);
                //$chapter->addLesson($lesson);
                $em->persist($lesson);
                //$em->persist($chapter);
                $validator = $this->get('validator');
                $errors = $validator->validate($lesson);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                } else {
                    $em->flush();
                    //$em->clear();
                }
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

    /**
     * @Route("admin/lesson/update", name="update_lesson")
     */
    public function updateLesson(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
            if (!$lesson) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $lesson->setName($request->request->get('name'));
            $validator = $this->get('validator');
            $errors = $validator->validate($lesson);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else {
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));
            }
        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }

    /**
     * @Route("admin/lesson/res", name="lesson_res")
     */
    public function updateLessonRes(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
            if (!$lesson) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $lesson->setLessonRes($request->request->get('res'));
            $em->persist($lesson);
            $validator = $this->get('validator');
            $errors = $validator->validate($lesson);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else {
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));
            }
        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }

    /**
     * @Route("admin/lesson/remove", name="remove_lesson")
     */
    public function removeLesson(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
            if (!$lesson) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($lesson);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}