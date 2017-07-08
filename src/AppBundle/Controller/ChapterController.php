<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Chapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class ChapterController extends BaseController
{


    /**
     * @Route("admin/chapters", name="chapters")
     */
    public function getChapters()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Chapter');
            $chapters = $repository->findAll();
            return $this->json($chapters);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("admin/chapter/add", name="add_chapter")
     */
    public function addChapter(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $chapter = new Chapter();
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('course'));
            $chapter->setName($request->request->get('name'));
            $chapter->setCourse($course);
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapter);
            $validator = $this->get('validator');
            $errors = $validator->validate($chapter);
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
     * @Route("admin/chapter/update", name="update_chapter")
     */
    public function updateChapter(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $chapter = $em->getRepository('AppBundle:Chapter')->find($request->request->get('id'));
            if (!$chapter) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $chapter->setName($request->request->get('name'));
            $validator = $this->get('validator');
            $errors = $validator->validate($chapter);
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
     * @Route("admin/chapter/remove", name="remove_chapter")
     */
    public function removeChapter(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $chapter = $em->getRepository('AppBundle:Chapter')->find($request->request->get('id'));
            if (!$chapter) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($chapter);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}