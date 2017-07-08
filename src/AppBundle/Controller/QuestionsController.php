<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Questions;
use Symfony\Component\Validator\Constraints\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class QuestionsController extends BaseController
{


    /**
     * @Route("admin/questions/list", name="questions_list")
     */
    public function getQuestions(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Questions');
            if($request->isXmlHttpRequest()){
                $courseId   = $request->get('courseId');
                $type       = $request->get('type');
                $questions  = $repository->findBy(array('course'=>$courseId,'questionType'=>$type));
                return $this->json($questions);
            }else{
                $questions = $repository->findBy( array(),array('id' => 'DESC'));
                return $questions;
            }

        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("/admin/questions/upload", name="lesson_upload")
     */
    public function lessonUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Questions')->find($request->request->get('id'));
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('lesson_upload_middle_path'));
        $question->setVideo($fileName);
        $em->persist($question);
        $validator = $this->get('validator');
        $errors = $validator->validate($question);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        } else {
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
    }


    public function fileContraints($file){
        $fileContraints = new File(array(
            'maxSize' => '100M',
            'maxSizeMessage' => '文件大小不能超过{{ limit }}Mb'
        ));

        // $fileContraints->mimeTypes = array('application/vnd.ms-excel');
        //$fileContraints->mimeTypesMessage = '文件类型不匹配';
        $errors = $this->get('validator')->validate($file, $fileContraints);
        return $errors;
    }
    /**
     * @Route("admin/questions/one", name="one_question")
     */
    public function getOneQuestion(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository('AppBundle:Questions')->find($request->request->get('id'));
           return $this->json($question);
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }
    /**
     * @Route("/admin/questions/add", name="add_questions")
     */
    public function addQuestions(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $question = new Questions();
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('id'));
            $question->setCourse($course);
            $question->setQuestionType($request->request->get('type'));
            $question->setQuestionTitle($request->request->get('title'));
            $question->setQuestionLevel($request->request->get('question_level'));
            $question->setQuestionAnswer($request->request->get('question_answer'));
            $question->setAnswer($request->request->get('answer'));
            $em->persist($question);
            $validator = $this->get('validator');
            $errors = $validator->validate($question);
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
     * @Route("admin/questions/update", name="update_question")
     */
    public function updateQuestion(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository('AppBundle:Questions')->find($request->request->get('id'));
            if (!$question) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $question->setQuestionTitle($request->request->get('title'));
            $question->setQuestionLevel($request->request->get('question_level'));
            $question->setQuestionAnswer($request->request->get('question_answer'));
            $question->setAnswer($request->request->get('answer'));
            $validator = $this->get('validator');
            $errors = $validator->validate($question);
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
     * @Route("admin/questions/remove", name="remove_question")
     */
    public function removeQuestion(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $question = $em->getRepository('AppBundle:Questions')->find($request->request->get('id'));
            if (!$question) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($question);
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}