<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\LoginType;


class CourseContentController extends BaseController
{
    /**
     * @Route("course/{courseid}/content", name="course_content")
     */
    public function contentAction($courseid)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $courseRepository = $em->getRepository('AppBundle:Course');
        $course = $courseRepository->findOneBy( array('id'=>$courseid));
        return $this->render('home/course-content.html.twig', [
                'form'          => $form->createView(),
                'last_username' => $helper->getLastUsername(),
                'error'         => $helper->getLastAuthenticationError(),
                'course'=>$course
        ]);
    }

}