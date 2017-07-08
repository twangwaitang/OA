<?php
namespace AppBundle\Controller;

use AppBundle\Entity\AssignmentReplay;
use AppBundle\Entity\User;
use AppBundle\Entity\StudentHasAssignments;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class AssignmentReplayController extends BaseController
{


    /**
     * @Route("admin/assignment-replay/add", name="assignment_replay_add")
     */
    public function addAssignmentReplay(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $assignmentReplay = new AssignmentReplay();
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $repository2 = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments');
            $user = $repository->find($request->request->get('uid'));
            $assignment = $repository2->find($request->request->get('assignment_id'));
            $assignmentReplay->setContent($request->request->get('content'));
            $assignmentReplay->setUid($user);
            $assignmentReplay->setAssignment($assignment);
            $em->persist($assignmentReplay);
            $validator = $this->get('validator');
            $errors = $validator->validate($assignmentReplay);
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
     * @Route("admin/assignment-replay/one", name="assignment-replay_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Assignment');
            $assignment = $repository->find($request->request->get('id'));
            return $this->json($assignment);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * 后台调用任务回复数据
     * @Route("admin/assignment-replay/get/{id}/{studentname}", name="assignment-replay_all")
     */
    public function getAssignmentReplayById($id,$studentname,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository  = $this->getDoctrine()->getRepository('AppBundle:AssignmentReplay');
            $assignmentReplays = $repository->findBy(array('assignment'=>$id));
            $assignmentReplays = $this->Pagination($assignmentReplays,$request,50);
            $repository2  = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments');
            $studentHasAssignments  = $repository2->find($id);
            $assignment_id = $studentHasAssignments->getAssignments();
            $assignment = $this->getDoctrine()->getRepository('AppBundle:Assignment')->find($assignment_id);
            return $this->render('admin/assignment-replay.html.twig',[
                'assignmentReplays' => $assignmentReplays,
                'assignment'        => $assignment,
                'assignmentId'        => $id,
                'studentname'        => $studentname,
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }





}