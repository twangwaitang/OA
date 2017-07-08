<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Faker\Provider\Base;
use phpDocumentor\Reflection\Types\Null_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\LoginType;

class HomeProfiloController extends BaseController
{
    /**
     * @Route("/user/profilo/{username}", name="profilo")
     */
    public function indexAction($username,Request $request)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(array('number'=>$username));
        //最新的一条评教
        $evaluation = $this->getDoctrine()->getRepository('AppBundle:Evaluation')
            ->findBy(array('isStarted'=>true),array('id'=>'DESC'),1);
        return $this->render('home/profilo.html.twig', [
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'student' => $student,
            'evaluation' => $evaluation[0],
            ]);
    }

    /**
     * @Route("/user/profilo/{username}/blog", name="profilo_blog")
     */
    public function blogAction($username,Request $request)
    {
        $blogs = $this->getDoctrine()->getRepository('AppBundle:Blog')->findAll();
        $blogs = $this->Pagination($blogs,$request);
        return $this->render('home/blog.html.twig', [
            'username'=>$username,
            'blogs'=>$blogs
        ]);
    }
    /**
     * @Route("/user/profilo/{username}/assign", name="profilo_assign")
     */
    public function assignAction($username,Request $request)
    {
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(array('number'=>$username));
        $studentHasAssignments = $student->getAssignments();
        //$assignments = $studentHasAssignments->getAssignments();
        return $this->render('home/assign.html.twig', [
                'username'=>$username,
                'studentHasAssignments'=>$studentHasAssignments
        ]);
    }

    /**
     * @Route("/user/profilo/{studentId}/mycourse", name="profilo_mycourse")
     */
    public function mycourseAction($studentId,Request $request)
    {
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($studentId);
        $groups = $student->getGroups();
        //$courses = $groups->getCourse();
        return $this->render('home/mycourse.html.twig', [
            'groups'=>$groups,
            'student'=>$student,

        ]);
    }
    /**
     * @Route("/user/profilo/{studentId}/{evaluationId}/teval", name="profilo_teva")
     */
    public function tevalAction($studentId,$evaluationId,Request $request)
    {
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($studentId);
        $groups = $student->getGroups();
        return $this->render('home/teachereval.html.twig', [
            'groups'=>$groups,
            'student'=>$student,
            'evaluationId'=>$evaluationId,
        ]);
    }
    /**
     * @Route("/user/profilo/{number}/{courseId}/{evaluationId}/{studentId}/tevadetail", name="profilo_tevadetail")
     */
    public function tevalDetailAction($number,$courseId,$evaluationId,$studentId,Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array('username'=>$number));
        $evaluationDetail = $this->getDoctrine()->getRepository('AppBundle:EvaluationDetail')
            ->findOneBy(array('course'=>$courseId,'evaluation'=>$evaluationId,'user'=>$user->getId(),'student'=>$studentId));
        if($evaluationDetail){
            return $this->render('common/done.html.twig', [
            ]);
        }
        return $this->render('home/teachereval_detail.html.twig', [
            'courseId'      =>$courseId,
            'evaluationId'  =>$evaluationId,
            'number'        =>$number,
        ]);
    }
}
