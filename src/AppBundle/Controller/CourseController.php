<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\Questions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;


class CourseController extends BaseController
{
    /**
     * @Route("admin/course", name="course")
     */
    public function indexAction(Request $request)
    {
        $studentTotal = $this->getStudentsTotalNum();
        $teacherTotal = $this->getTeachersTotalNum();
        $advisorTotal = $this->getAdvisorTotalNum();
        $courses = $this->getCourses($request);
        return $this->render('admin/course.html.twig', [
            'studentTotal' => $studentTotal,
            'teacherTotal' => $teacherTotal,
            'advisorTotal' => $advisorTotal,
            'courses'        => $courses,
        ]);
    }
    /**
 * @Route("admin/course/detail/{id}", name="course_detail")
 */
    public function courseDetailAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
            $course = $repository->find($id);
            return $this->render('admin/course-detail.html.twig', [
                'course'          => $course,
                'upload_progress_name' => ini_get("session.upload_progress.name"),
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/course/{id}/questions", name="course_questions")
     * 单选题 type = 0
     */
    public function courseQuestionsAction($id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
            $repository2 = $this->getDoctrine()->getRepository('AppBundle:Questions');
            $allQuestions = $repository2->findBy( array('course'=>$id,'questionType'=>0),array('id' => 'DESC'));
            $questions = $this->Pagination($allQuestions,$request);
            $course = $repository->find($id);
            return $this->render('admin/questions.html.twig', [
                'course'            => $course,
                'questions'         => $questions
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/course/{id}/multiple-choice", name="course_multiple_choice")
     * 多选题 type=1
     */
    public function courseMultipleChoiceAction($id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
            $repository2 = $this->getDoctrine()->getRepository('AppBundle:Questions');
            $allQuestions = $repository2->findBy( array('course'=>$id,'questionType'=>1),array('id' => 'DESC'));
            $questions = $this->Pagination($allQuestions,$request);
            $course = $repository->find($id);
            return $this->render('admin/multiple-choice.html.twig', [
                'course'            => $course,
                'questions'         => $questions
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    public function getStudentsTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $studentTotal = $repository->getTotalRecodes();
        return $studentTotal;
    }

    public function getTeachersTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
        $teacherTotal = $repository->getTotalRecodes();
        return $teacherTotal;
    }

    public function getAdvisorTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Advisor');
        $advisorTotal = $repository->getTotalRecodes();
        return $advisorTotal;
    }
    /**
     * @Route("admin/course/courses", name="get_courses")
     */
    public function getCourses(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $courseRepository = $em->getRepository('AppBundle:Course');
            $teacherRepository = $em->getRepository('AppBundle:Teacher');
            $groupRepository = $em->getRepository('AppBundle:Group');
            if (true === $this->get('security.authorization_checker')->isGranted('ROLE_TEACHER'))
            {
                $teacher = $teacherRepository->findOneBy(array('number' => $user->getUsername()));
                $id = $teacher->getId();
                $groups = $groupRepository->findGroupsByTeacherId($id);
                $courses = $courseRepository->findBy( array('group'=>$groups),array('id' => 'DESC'));
                $courses = $this->Pagination($courses,$request);
                return $courses;
            }elseif (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
                $courses = $courseRepository->findBy( array(),array('id' => 'DESC'));
                $courses = $this->Pagination($courses,$request);
                return $courses;
            }

        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("admin/course/one", name="get_one_course")
     */
    public function getOneCourse(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
            $course = $repository->find($request->request->get('id'));
            return $this->json($course);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("admin/course/onegroup", name="get_one_group")
     * 根据group_id获得分组学生名单
     *
     */
    public function getOneGroup(Request $request){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
        if($request->query->get('group_id')){
            $group_id = $request->query->get('group_id');
            $group = $repository->findOneBy(array('id'=>$group_id));
            $students = $group->getStudents();
            $students = parent::serializer($students);
            return new Response($students);
        }
    }
    /**
     * @Route("/admin/course/upload", name="course_upload")
     */
    public function courseUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('course_upload_middle_path'));
        return new JsonResponse(array('message' => '上传成功', 'fileName' => $fileName,'code' => 1));
    }
    /**
     * @Route("admin/course/add", name="add_course")
     */
    public function addCourse(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $course = new Course();
            $em = $this->getDoctrine()->getManager();
            $course->setName($request->request->get('name'));
            $course->setTeachHours($request->request->get('teach_hours'));
            $course->setCourseGoal($request->request->get('course_goal'));
            $course->setCoursePlan($request->request->get('course_plan'));
            $course->setCourseInfo($request->request->get('course_info'));
            $course->setThumbnial($request->request->get('thumbnail'));
            $course->setIsFinished($request->request->get('is_finished'));
            $course->setIsPublic($request->request->get('is_public'));
            $group = $em->getRepository('AppBundle:Group')->find($request->request->get('group_id'));
            $group->setCourse($course);
            $course->setGroup($group);
            $em->persist($course);
            $em->persist($group);
            $validator = $this->get('validator');
            $errors = $validator->validate($course);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else {
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));
            }
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

    /**
     * @Route("admin/course/update", name="update_course")
     */
    public function updateGroup(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('id'));
            if (!$course) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $course->setName($request->request->get('name'));
            $course->setTeachHours($request->request->get('teach_hours'));
            $course->setCourseGoal($request->request->get('course_goal'));
            $course->setCoursePlan($request->request->get('course_plan'));
            $course->setCourseInfo($request->request->get('course_info'));
            $course->setThumbnial($request->request->get('thumbnail'));
            $course->setIsFinished($request->request->get('is_finished'));
            $course->setIsPublic($request->request->get('is_public'));
            $group = $em->getRepository('AppBundle:Group')->find($request->request->get('group_id'));
            $group->setCourse($course);
            $course->setGroup($group);
            $validator = $this->get('validator');
            $errors = $validator->validate($course);
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
     * @Route("admin/course/remove", name="remove_course")
     */
    public function removeCourse(Request $request)
    {
            if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $em = $this->getDoctrine()->getEntityManager();
                $id = $request->request->get('id');
                $course= $em->getRepository('AppBundle:Course')->find($id);
                $group= $em->getRepository('AppBundle:Group')->findOneBy(array('course'=>$id));
                if (!$course || !$group) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $group->setCourse(null);
                $em->remove($course);
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));

            }else {
            throw $this->createAccessDeniedException('非法访问');
            }
        }

}