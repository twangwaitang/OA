<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Exam;
use AppBundle\Entity\Questions;
use AppBundle\Entity\Score;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;


class ExamController extends BaseController
{
    /**
     * @Route("/exam/{id}/{uid}", name="do_exam")
     *前台考试,id为examID uid为username
     */
    public function doExamAction($id,$uid)
    {
        $exam = $this->getDoctrine()->getRepository('AppBundle:Exam')->find($id);
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(array('number'=>$uid));
        $score = $this->getDoctrine()->getRepository('AppBundle:Score')
                    ->findOneBy(array('student'=>$student->getId(),'exam'=>$id));
        if (!$score){
            return $this->render('home/exam.html.twig', [
                'exam' => $exam,
                'student' => $student,
            ]);
        }else{
            return $this->render('home/score.html.twig', [
                'score' => $score,
                'student' => $student,
            ]);
        }


    }
    /**
     * @Route("/exam/check", name="check_exam")
     *前台评卷
     */
    public function checkExamAction(Request $request)
    {
        $count1 = 0;
        $num1 = 0;
        $count2 = 0;
        $num2 = 0;
        $exam = $this->getDoctrine()->getRepository('AppBundle:Exam')->find($request->request->get('examid'));
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($request->request->get('studentid'));
        $questions = $exam->getQuestions()->getValues();
        for($i=0 ; $i<count($questions); $i++) {
            $id = $questions[$i]->getId();
            if ($questions[$i]->getQuestionType() == 0) {
                $num1++;
                if ($questions[$i]->getAnswer()[0] == $request->request->get($id)) {
                    $count1++;
                }
            } elseif ($questions[$i]->getQuestionType() == 1) {
                $num2++;
                if ($questions[$i]->getAnswer() == $request->request->get($id)) {
                    $count2++;
                }

            }
        }
        $totalSingle = $exam->getScoreRate()[0];
        $totalMultiple = $exam->getScoreRate()[1];
        $totalCheck = $exam->getScoreRate()[2];
        $finalSingleScore = $totalSingle / $num1 * $count1;
        $finalMultipleScore = $totalMultiple / $num2 * $count2;
        $totalScore = $finalSingleScore + $finalMultipleScore;
        $this->addStudentScore($exam,$student,$totalScore);
        return new Response($totalScore);
    }
    //记录学生考试成绩
    function addStudentScore($exam,$student,$totalScore){
        $em = $this->getDoctrine()->getManager();
        $score = new Score();
        $score->setScore($totalScore);
        $score->setExam($exam);
        $score->setStudent($student);
        $em->persist($score);
        $validator = $this->get('validator');
        $errors = $validator->validate($score);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        } else {
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
    }
    /**
     * @Route("admin/course/{course_id}/exam", name="exam")
     * 后台考试管理页
     */
    public function indexAction($course_id,Request $request)
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Course');
        $exams = $this->getDoctrine()->getRepository('AppBundle:Exam')->findAll();
        $exams = $this->Pagination($exams,$request);
        $course = $repository->find($course_id);
        return $this->render('admin/exam.html.twig', [
            'course' => $course,
            'exams' => $exams
        ]);
    }
    /**
     * @Route("admin/exam/show/{exam_id}", name="show_exam")
     * 后台考试预览页面
     */
    public function showExamAction($exam_id)
    {
        $exam = $this->getDoctrine()->getRepository('AppBundle:Exam')->find($exam_id);
        return $this->render('admin/show-exam.html.twig', [
            'exam' => $exam
        ]);
    }
    /**
     * @Route("admin/exam/one", name="exam_one")
     */
    public function getOneAction(Request $request)
    {
        $exam = $this->getDoctrine()->getRepository('AppBundle:Exam')->find($request->request->get('id'));
        return $this->json($exam);
    }


    /**
     * @Route("admin/exam/add", name="add_exam")
     */
    public function addExam(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $exam = new Exam();
            $array=array($request->request->get('single_score'));
            array_push($array,$request->request->get('multiple_score'),$request->request->get('check_score'));
            $em = $this->getDoctrine()->getManager();
            $exam->setTitle($request->request->get('title'));
            $exam->setStartTime($request->request->get('start_date'));
            $exam->setEndTime($request->request->get('end_date'));
            $exam->setInfo($request->request->get('info'));
            $exam->setDuration($request->request->get('duration'));
            $exam->setScoreRate($array);
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('course_id'));
            $exam->setCourse($course);
            $em->persist($exam);
            $validator = $this->get('validator');
            $errors = $validator->validate($exam);
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
     * @Route("admin/exam/update", name="update_exam")
     */
    public function updateExam(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $exam = $this->getDoctrine()->getRepository('AppBundle:Exam')->find($request->request->get('id'));
            $array=array($request->request->get('single_score'));
            array_push($array,$request->request->get('multiple_score'),$request->request->get('check_score'));
            $em = $this->getDoctrine()->getManager();
            $exam->setTitle($request->request->get('title'));
            $exam->setStartTime($request->request->get('start_date'));
            $exam->setEndTime($request->request->get('end_date'));
            $exam->setInfo($request->request->get('info'));
            $exam->setDuration($request->request->get('duration'));
            $exam->setScoreRate($array);
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('course_id'));
            $exam->setCourse($course);
            $validator = $this->get('validator');
            $errors = $validator->validate($exam);
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
     * @Route("admin/exam/add_question_to_exam", name="add_question_to_exam")
     */
    public function addQuestionToExam(Request $request)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $rows = $request->request->get('rows');
            foreach ($rows as $row){
                $exam = $em->getRepository('AppBundle:Exam')->find($request->request->get('exam_id'));
                if (!$exam) {
                    throw $this->createNotFoundException(
                        '没有找到数据'
                    );
                }
                $question = $em->getRepository('AppBundle:Questions')->find($row['id']);
                $exam->addQuestion($question);
                $question->addExam($exam);
                $validator = $this->get('validator');
                $errors = $validator->validate($exam);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                }
            }

            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
        catch (\Exception $e)
        {
            return new JsonResponse(array('message' => $e->getMessage(), 'code' => 0));
        }
    }

    /**
     * @Route("admin/exam/remove", name="remove_exam")
     */
    public function removeExam(Request $request)
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