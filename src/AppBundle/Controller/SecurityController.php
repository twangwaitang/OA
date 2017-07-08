<?php
//namespace AppBundle\Controller;
//
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//
//class SecurityController extends Controller
//{
//
//    public function loginAction(Request $request)
//    {
//        $authenticationUtils = $this->get('security.authentication_utils');
//
//        // get the login error if there is one / 获取可能存在的登录错误信息
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        // last username entered by the user / 获取用户输入的username（用户名）
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render(
//            'security/login.html.twig',
//            array(
//                // last username entered by the user
//                'last_username' => $lastUsername,
//                'error'         => $error,
//            )
//        );
//    }
//}