<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Position;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class PositionController extends Controller
{
    /**
     * @Route("admin/position", name="position")
     */
    public function userAction()
    {

        return $this->render('admin/position.html.twig', [


        ]);
    }

    /**
     * @Route("admin/positions", name="positions")
     */
    public function getPositions()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Position');
            $positions = $repository->findAll();
            return $this->json($positions);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/addpositions", name="addpositions")
     */
    public function addpositions(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $position = new Position();
            $position->setName($request->request->get('name'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($position);
            $validator = $this->get('validator');
            $errors = $validator->validate($position);
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
     * @Route("admin/updateposition", name="updateposition")
     */
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

    /**
     * @Route("admin/removepositions", name="removepositions")
     */
    public function removepositions(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $position = $em->getRepository('AppBundle:Position')->find($id);
                if (!$position) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($position);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}