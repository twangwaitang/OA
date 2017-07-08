<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\LoginType;

class IndexController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $courseRepository = $em->getRepository('AppBundle:Course');
        $teachers = $em->getRepository('AppBundle:Teacher')->findBy( array('isTop'=>1),array('id' => 'DESC'),4);
        $advisors = $em->getRepository('AppBundle:Advisor')->findBy( array('isTop'=>1),array('id' => 'DESC'),4);
        $news = $em->getRepository('AppBundle:News')->findBy( array('isTop'=>1,'type'=>'资讯'),array('id' => 'DESC'),5);
        $notes = $em->getRepository('AppBundle:News')->findBy( array('isTop'=>1,'type'=>'通知'),array('id' => 'DESC'),5);
        $blogs = $em->getRepository('AppBundle:Blog')->findBy( array('isGood'=>1),array('id' => 'DESC'),5);
        $courses = $courseRepository->findBy( array(),array('id' => 'DESC'));
        $courses = $this->Pagination($courses,$request);
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(array('number'=>$user->getUsername()));
            $assignments = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments')
                ->findBy(array('students'=>$student->getId(),'isReaded'=>0));
            $this->session->set('assignments', count($assignments));
            $this->session->set('student', $student);
            return $this->render('home/index.html.twig',[
                'form'          => $form->createView(),
                'last_username' => $helper->getLastUsername(),
                'error'         => $helper->getLastAuthenticationError(),
                'courses'       => $courses,
                'teachers'      => $teachers,
                'student'       => $student,
                'advisors'      => $advisors,
                'blogs'         => $blogs,
                'news'          => $news,
                'notes'         => $notes,
            ]);
        };
        return $this->render('home/index.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'courses'       => $courses,
            'teachers'      => $teachers,
            'advisors'      => $advisors,
            'blogs'         => $blogs,
            'news'          => $news,
            'notes'         => $notes,
        ]);
    }
}
