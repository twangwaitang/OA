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

class AdminProfiloController extends Controller
{
    /**
     * @Route("/admin/profilo/{username}", name="admin_profilo")
     */
    public function indexAction($username,Request $request)
    {

            return $this->render('admin/profilo.html.twig', [
                'username' => $username
            ]);
    }
}
