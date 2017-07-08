<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Hamcrest\Core\IsNull;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\LoginType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;


class HomeLessonController extends BaseController
{

    /**
     * @Route("/course/{courseId}/lesson/{lessonId}", name="home_lesson")
     */
    public function indexAction($courseId,$lessonId,Request $request)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Lesson');
        $lesson = $repository->find($lessonId);
        $repComment = $this->getDoctrine()->getRepository('AppBundle:Comment');
        $comments = $repComment->findBy(array('lesson'=>$lessonId,'comment'=>Null));
        $comments = $this->Pagination($comments,$request);
        $repository2 = $this->getDoctrine()->getRepository('AppBundle:Course');
        $notes = $this->Pagination($lesson->getNotes(),$request);
        $course = $repository2->find($courseId);
        return $this->render('home/lesson.html.twig', [
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'lesson'        => $lesson,
            'course'        => $course,
            'comments'      => $comments,
            'notes'         => $notes,

        ]);
    }
    public function updateUser(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //$position = new User();
            $em = $this->getDoctrine()->getManager();
            $position = $em->getRepository('AppBundle:Position')->find($request->request->get('id'));
            if (!$position) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $position->setName($request->request->get('name'));
            $validator = $this->get('validator');
            $errors = $validator->validate($position);
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

}