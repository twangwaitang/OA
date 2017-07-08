<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Notes;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class NotesController extends Controller
{



    /**
     * @Route("notes/add", name="notes_add")
     */
    public function addNotes(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $notes = new Notes();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lessonId'));
            $user = $em->getRepository('AppBundle:User')->find($request->request->get('userId'));
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('courseId'));
            $notes->setContent($request->request->get('content'));
            $notes->setLesson($lesson);
            $notes->setUser($user);
            $notes->setCourse($course);
            $em->persist($notes);
            $validator = $this->get('validator');
            $errors = $validator->validate($notes);
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
     * @Route("notes/isgood", name="notes_isgood")
     */
    public function noteToGood(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $note = $em->getRepository('AppBundle:Notes')->find($request->request->get('id'));
            if (!$note) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($request->request->get('type') === 'cancel'){
                $note->setIsGood(0);
            }else{
                $note->setIsGood(1);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($note);
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