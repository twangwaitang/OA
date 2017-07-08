<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;
use AppBundle\Entity\User;

class UserController extends BaseController
{
    /**
     * @Route("admin/user", name="user")
     */
    public function indexAction()
    {
        $groups = $this->findUsersByGroups();
        $total = $this->getTotalRecodes();
        return $this->render('admin/user.html.twig', [
            'groups' => $groups,
            'total' => $total
        ]);
    }
    /**
     * @Route("admin/resetpassword", name="reset_password")
     * 重置用户初始密码 123456
     */
    public function reestPassword(Request $request){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $user = $em->getRepository('AppBundle:User')->find($id);
                if (!$user) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $this->getParameter('default_password'));
                $user->setPassword($encoded);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
    public function getTotalRecodes()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $total = $repository->getTotalRecodes();
        return $total;
    }

    public function findUsersByGroups()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $groups = $repository->findUsersByGroups();
        return $groups;
    }
    /**
     * @Route("admin/users", name="users")
     */
    public function getUsers(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            if ($request->query->get('roles')){
                $role = $request->query->get('roles');
                $users = $repository->findByRole($role);
            }else{
                $users = $repository->findAll();
            }
            return $this->json($users);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/addusers", name="addusers")
     */
    public function addUsers(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = new User();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $request->request->get('password'));
            $user->setPassword($encoded);
            $user->setUsername($request->request->get('username'));
            $user->setRoles(Array($request->request->get('roles')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
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
     * @Route("admin/updateuser", name="updateuser")
     */
    public function updateUser(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = new User();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->find($request->request->get('id'));
            if (!$user) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $user->setUsername($request->request->get('username'));
            $user->setRoles(Array($request->request->get('roles')));
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
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
     * @Route("admin/removeusers", name="removeusers")
     */
    public function removeUsers(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $user = $em->getRepository('AppBundle:User')->find($id);
                if (!$user) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($user);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}