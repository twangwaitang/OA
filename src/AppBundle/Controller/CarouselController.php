<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Carousel;
use AppBundle\Entity\User;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class CarouselController extends BaseController
{

    /**
     * @Route("admin/carousel", name="carousel")
     */
    public function getCarousel(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Carousel');
            $carousels = $repository->findAll();
            $carousels = $this->Pagination($carousels,$request);
            return $this->render('admin/carousel.html.twig',[
                'carousels'       => $carousels
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("/admin/carousel/upload", name="carousel_upload")
     */
    public function carouselUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('carousel_upload_middle_path'));
        return new JsonResponse(array('message' => '上传成功', 'fileName' => $fileName,'code' => 1));
    }
    /**
     * @Route("admin/carousel/add", name="carousel_add")
     */
    public function addCarousel(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $carousel = new Carousel();
            $carousel->setPic($request->request->get('pic'));
            $carousel->setUrl($request->request->get('url'));
            $em->persist($carousel);
            $validator = $this->get('validator');
            $errors = $validator->validate($carousel);
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
     * @Route("admin/carousel/update", name="carousel_update")
     */
    public function updatecarousel(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:carousel');
            $carousel = $repository->find($request->request->get('id'));
            $carousel->setContent($request->request->get('content'));
            $carousel->setTitle($request->request->get('title'));
            $carousel->setType($request->request->get('type'));
            $validator = $this->get('validator');
            $errors = $validator->validate($carousel);
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
     * @Route("admin/carousel/one", name="carousel_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:carousel');
            $carousel = $repository->find($request->request->get('id'));
            return $this->json($carousel);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("carousel/{id}", name="carousel_detail")
     */
    public function getDetail($id)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:carousel');
        $carousel = $repository->find($id);
        return $this->render('home/detail.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'carousel'       => $carousel
        ]);
    }


    /**
     * @Route("admin/carousel/ispublic", name="carousel_ispublic")
     */
    public function carouselIsPublic(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $carousel = $em->getRepository('AppBundle:carousel')->find($request->request->get('id'));
            if (!$carousel) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($carousel->getIsPublic()){
                $carousel->setIsPublic(false);
            }else{
                $carousel->setIsPublic(true);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($carousel);
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
     * @Route("admin/carousel/remove", name="carousel_remove")
     */
    public function removeLink(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $carousel = $em->getRepository('AppBundle:carousel')->find($request->request->get('id'));
            if (!$carousel) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($carousel);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}