<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\User;
use AppBundle\Entity\StudentHasAssignments;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class AssignmentController extends BaseController
{

    /**
     * @Route("admin/assignment", name="assignment")
     */
    public function getAssignment(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Assignment');
            $assignments = $repository->findAll();
            $assignments = $this->Pagination($assignments,$request);
            $groups = $this->getGroups();
            return $this->render('admin/assignment.html.twig',[
                'assignments'       => $assignments,
                'groups'            => $groups
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * 任务完成
     * @Route("admin/assignment/done", name="assignment_done")
     */
    public function assignmentDone(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments');
            $assignment = $repository->find($request->request->get('id'));
            $assignment->setIsFinished(true);
            $validator = $this->get('validator');
            $errors = $validator->validate($assignment);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else {
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));
            }
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }

    /**
     * 任务分配的学生名单
     * @Route("admin/assignment/student/{id}", name="assignment_detail_students")
     */
    public function getAssignmentDetail($id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments');
            $assignments = $repository->findBy(array('assignments'=>$id));
            $assignments = $this->Pagination($assignments,$request);
            return $this->render('admin/assignment-detail.html.twig',[
                'assignments'       => $assignments
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("admin/assignment/add", name="assignment_add")
     */
    public function addAssignment(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $assignment = new Assignment();
            $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
            $teacher = $repository->findOneBy(array('number'=>$request->request->get('teacher_id')));
            $assignment->setContent($request->request->get('content'));
            $assignment->setTitle($request->request->get('title'));
            $assignment->setTeacher($teacher);
            $em->persist($assignment);
            $validator = $this->get('validator');
            $errors = $validator->validate($assignment);
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
     * @Route("admin/assignment/update", name="assignment_update")
     */
    public function updateAssignment(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:Assignment');
            $assignment = $repository->find($request->request->get('id'));
            $assignment->setContent($request->request->get('content'));
            $assignment->setTitle($request->request->get('title'));
            $validator = $this->get('validator');
            $errors = $validator->validate($assignment);
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
     * @Route("admin/assignment/one", name="assignment_one")
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
     * sid是student id,id是assignment id, aid是StudentHasAssignments id
     * @Route("assignment/{sid}/{id}/{aid}", name="assignment_detail")
     */
    public function getDetail($sid,$id,$aid)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Assignment');
        $assignment = $repository->find($id);
        $repository2 = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments');
        $repository3 = $this->getDoctrine()->getRepository('AppBundle:AssignmentReplay');
        $assignmentReplays = $repository3->findBy(array('assignment'=>$aid));
        $studentHasAssignments = $repository2->find($aid);
        $studentHasAssignments->setIsReaded(true);
        $em->flush();
        $assignments = $this->getDoctrine()->getRepository('AppBundle:StudentHasAssignments')
            ->findBy(array('students'=>$sid,'isReaded'=>NULL));
        $this->session->set('assignments', count($assignments));
        return $this->render('home/assignment-detail.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'assignment'    => $assignment,
            'studentHasAssignments'    => $studentHasAssignments,
            'aid'           => $aid,
            'assignmentReplays'           => $assignmentReplays,
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }
    /**
     * @Route("admin/assignment/groups", name="get_groups")
     */
    public function getGroups()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Teacher');
            $repository2 = $em->getRepository('AppBundle:Group');
            $teacher = $repository->findOneBy(array('number' => $user->getUsername()));
            $id = $teacher->getId();
            $groups = $repository2->findGroupsByTeacherId($id);
            return $groups;
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/assignment/assignment-to-students", name="assignment_to_students")
     */
    public function assignToStudent(Request $request)
    {
        try{
            $studentToAssignments = new StudentHasAssignments();
            $em = $this->getDoctrine()->getManager();
            $assignment = $em->getRepository('AppBundle:Assignment')->find($request->request->get('id'));
            $rows = $request->request->get('rows');
            if (!$assignment) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            foreach ($rows as $row){
                $student = $em->getRepository('AppBundle:Student')->find($row['id']);
                $assignment = $em->getRepository('AppBundle:Assignment')->find($request->request->get('id'));
                $studentToAssignments->setAssignments($assignment);
                $studentToAssignments->setStudents($student);
                $validator = $this->get('validator');
                $errors = $validator->validate($studentToAssignments);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                }
                $em->persist($studentToAssignments);
                $em->flush();
                $em->clear();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
        catch (\Exception $e)
        {
            return new JsonResponse(array('message' => $e->getMessage(), 'code' => 0));
        }
    }

}