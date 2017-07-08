<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\RegType;

class RegController extends Controller
{
    /**
     * @Route("/reg", name="reg")
     */
    public function regAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            //对密码进行加密处理
            $password = $user->getPassword();
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin');
        }else{

        }
        return $this->render('login/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}