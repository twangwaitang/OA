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


class WelcomeController extends Controller
{
    /**
     * @Route("/admin/welcome", name="welcome")
     */
    public function loginAction(Request $request)
    {

        return $this->render('admin/welcome.html.twig', [

        ]);
    }

}