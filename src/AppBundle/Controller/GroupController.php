<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\ORMException;


class GroupController extends BaseController
{
    /**
     * @Route("admin/group", name="group")
     */
    public function indexAction()
    {
        $studentTotal = $this->getStudentsTotalNum();
        $teacherTotal = $this->getTeachersTotalNum();
        $advisorTotal = $this->getAdvisorTotalNum();
        $groups = $this->getGroups();
        return $this->render('admin/groups.html.twig', [
            'studentTotal' => $studentTotal,
            'teacherTotal' => $teacherTotal,
            'advisorTotal' => $advisorTotal,
            'groups'        => $groups,
        ]);
    }

    public function getStudentsTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $studentTotal = $repository->getTotalRecodes();
        return $studentTotal;
    }

    public function getTeachersTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
        $teacherTotal = $repository->getTotalRecodes();
        return $teacherTotal;
    }

    public function getAdvisorTotalNum()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Advisor');
        $advisorTotal = $repository->getTotalRecodes();
        return $advisorTotal;
    }
    /**
     * @Route("admin/group/groups", name="get_groups")
     */
    public function getGroups()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            if (true === $this->get('security.authorization_checker')->isGranted('ROLE_TEACHER'))
            {
                $repository = $em->getRepository('AppBundle:Teacher');
                $repository2 = $em->getRepository('AppBundle:Group');
                $teacher = $repository->findOneBy(array('number' => $user->getUsername()));
                $id = $teacher->getId();
                $groups = $repository2->findGroupsByTeacherId($id);
                return $groups;
            }elseif (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
                $groups = $repository->findAll();
                return $groups;
            }

        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("admin/group/ajaxgroups", name="get_ajax_groups")
     */
    public function getAjaxGroups(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
            $groups = $repository->findUnrelatedGroups();
            if($request->isXmlHttpRequest()) {
                return $this->json($groups);
            }
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/group/{groupId}/students", name="get_students_one_group")
     * 根据group_id获得分组学生名单
     *
     */
    public function getStudentsInOneGroup($groupId,Request $request){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
        if($groupId){
            $group = $repository->find($groupId);
            $students = $group->getStudents();
            $students = $this->Pagination($students,$request,100);
            return $this->render('admin/students_in_one_group.html.twig', [
                'students' => $students,
                'group' => $group,

            ]);
        }
    }
    /**
     * @Route("admin/group/students", name="ajax_get_students_one_group")
     * 根据group_id获得分组学生名单,ajax请求下
     *
     */
    public function ajaxGetStudentsInOneGroup(Request $request){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
        if($request->request->get('group_id')){
            $group = $repository->find($request->request->get('group_id'));
            $students = $group->getStudents()->getValues();
            return $this->json($students);
        }
        throw $this->createAccessDeniedException('没有找到任何数据');
    }

    /**
     * @Route("admin/group/{groupId}/students/export", name="group_students_export")
     * 学生名单导出
     *
     */
    public function studentsExport($groupId){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Group');
        $group = $repository->find($groupId);
        $students = $group->getStudents();
        return $this->exportExcel($students);
    }

    /**
     * @Route("admin/group/add", name="add_group")
     */
    public function addGroup(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $group = new Group();
            $group->setName($request->request->get('name'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $validator = $this->get('validator');
            $errors = $validator->validate($group);
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
     * @Route("admin/group/update", name="update_group")
     */
    public function updateGroup(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $group = $em->getRepository('AppBundle:Group')->find($request->request->get('id'));
            if (!$group) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $group->setName($request->request->get('name'));
            $validator = $this->get('validator');
            $errors = $validator->validate($group);
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
     * @Route("admin/group/remove", name="remove_group")
     */
    public function removeGroup(Request $request)
    {
            if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $em = $this->getDoctrine()->getEntityManager();
                $id = $request->request->get('group_id');
                $group = $em->getRepository('AppBundle:Group')->find($id);
                if (!$group) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($group);
                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));

            }else {
            throw $this->createAccessDeniedException('非法访问');
            }
        }

}