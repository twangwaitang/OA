<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Links;
use AppBundle\Entity\User;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class LinksController extends BaseController
{

    /**
     * @Route("admin/links", name="links")
     */
    public function getLinks(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Links');
            $links = $repository->findAll();
            $links = $this->Pagination($links,$request);
            return $this->render('admin/links.html.twig',[
                'links'       => $links
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("/admin/links/upload", name="links_upload")
     */
    public function linksUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('links_upload_middle_path'));
        return new JsonResponse(array('message' => '上传成功', 'fileName' => $fileName,'code' => 1));
    }
    /**
     * @Route("admin/links/add", name="links_add")
     */
    public function addLinks(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $links = new Links();
            $links->setThumbnial($request->request->get('thumbnial'));
            $links->setTitle($request->request->get('title'));
            $links->setUrl($request->request->get('url'));
            $em->persist($links);
            $validator = $this->get('validator');
            $errors = $validator->validate($links);
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
     * @Route("admin/links/update", name="links_update")
     */
    public function updateLinks(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:links');
            $links = $repository->find($request->request->get('id'));
            $links->setContent($request->request->get('content'));
            $links->setTitle($request->request->get('title'));
            $links->setType($request->request->get('type'));
            $validator = $this->get('validator');
            $errors = $validator->validate($links);
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
     * @Route("admin/links/one", name="links_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:links');
            $links = $repository->find($request->request->get('id'));
            return $this->json($links);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("links/{id}", name="links_detail")
     */
    public function getDetail($id)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:links');
        $links = $repository->find($id);
        return $this->render('home/detail.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'links'       => $links
        ]);
    }


    /**
     * @Route("admin/links/ispublic", name="links_ispublic")
     */
    public function LinksIsPublic(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $links = $em->getRepository('AppBundle:Links')->find($request->request->get('id'));
            if (!$links) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($links->getIsPublic()){
                $links->setIsPublic(false);
            }else{
                $links->setIsPublic(true);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($links);
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
     * @Route("admin/links/remove", name="links_remove")
     */
    public function removeLink(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $links = $em->getRepository('AppBundle:Links')->find($request->request->get('id'));
            if (!$links) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($links);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}