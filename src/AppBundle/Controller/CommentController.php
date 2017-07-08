<?php
namespace AppBundle\Controller;

use AppBundle\Entity\comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class CommentController extends Controller
{
//    /**
//     * @Route("/comment", name="comment")
//     */
//    public function indexAction()
//    {
//
//        return $this->render('admin/comment.html.twig', [
//
//
//        ]);
//    }

    /**
     * @Route("admin/comments", name="comments")
     */
    public function getPositions()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:comment');
            $positions = $repository->findAll();
            return $this->json($positions);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("comment/add", name="comment_add")
     */
    public function addComment(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $comment = new comment();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lessonId'));
            $user = $em->getRepository('AppBundle:User')->find($request->request->get('userId'));
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('courseId'));
            $comment->setContent($request->request->get('content'));
            $comment->setLesson($lesson);
            $comment->setUser($user);
            $comment->setCourse($course);
            $em->persist($comment);
            $validator = $this->get('validator');
            $errors = $validator->validate($comment);
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
     * @Route("comment/reply/add", name="comment_add_replay")
     *
     */
    public function addReply(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $reply = new comment();
            $comment = $em->getRepository('AppBundle:Comment')->find($request->request->get('commentId'));
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lessonId'));
            $user = $em->getRepository('AppBundle:User')->find($request->request->get('userId'));
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('courseId'));
            $reply->setContent($request->request->get('content'));
            $reply->setLesson($lesson);
            $reply->setUser($user);
            $reply->setComment($comment);
            $reply->setCourse($course);
            $comment->addReply($reply);
           // $em->persist($comment);
            $em->persist($reply);
            $validator = $this->get('validator');
            $errors = $validator->validate($reply);
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
     * @Route("comment/update", name="comment_update")
     */
    public function updateUser(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //$position = new User();
            $em = $this->getDoctrine()->getManager();
            $position = $em->getRepository('AppBundle:comment')->find($request->request->get('id'));
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

    /**
     * @Route("comment/remove", name="remove_comment")
     */
    public function removepositions(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $id = $request->request->get('id');
            $comment = $em->getRepository('AppBundle:Comment')->find($id);
            $em->remove($comment);
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}