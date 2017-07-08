<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\LoginType;


class AdminController extends Controller
{
    /**
     * @Route("/admin/login", name="login")
     */
    public function loginAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);

        $helper = $this->get('security.authentication_utils');

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
    /**
     * @Route("/admin/dashbroad", name="admin_dashbroad")
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
        $teacher = $repository->findOneBy(array('number' => $user->getUsername()));
        return $this->render('admin/index.html.twig',[
            'teacher'=>$teacher
        ]);
    }
}