<?php
namespace AppBundle\Controller;

use AppBundle\Entity\BaseWeb;
use AppBundle\Entity\User;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class BaseWebController extends BaseController
{

    /**
     * @Route("admin/baseweb", name="base_web")
     */
    public function getBaseWeb(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:BaseWeb');
            $baseweb = $repository->findAll();
            return $this->render('admin/base-web.html.twig',[
                'baseweb'       => $baseweb
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("/admin/baseweb/upload", name="base_web_upload")
     */
    public function baseWebUpload(Request $request)
    {
        $file = $request->files->get('files')[0];
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('baseweb_upload_middle_path'));
        return new JsonResponse(array('message' => '上传成功', 'fileName' => $fileName,'code' => 1));
    }
    /**
     * @Route("admin/baseweb/add", name="base_web_add")
     */
    public function addBaseWeb(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $baseweb = new BaseWeb();
            $baseweb->setLogo($request->request->get('logo'));
            $baseweb->setIsOpen($request->request->get('isOpen'));
            $baseweb->setWebTitle($request->request->get('webtitle'));
            $baseweb->setFooter($request->request->get('footer'));
            $em->persist($baseweb);
            $validator = $this->get('validator');
            $errors = $validator->validate($baseweb);
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
     * @Route("admin/baseweb/update", name="baseweb_update")
     */
    public function updateBaseWeb(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:BaseWeb');
            $baseweb = $repository->find($request->request->get('id'));
            $baseweb->setLogo($request->request->get('logo'));
            if($request->request->get('isOpen') =='true'){
                $baseweb->setIsOpen(true);
            }else{
                $baseweb->setIsOpen(false);
            }
            $baseweb->setWebTitle($request->request->get('webtitle'));
            $baseweb->setFooter($request->request->get('footer'));
            $validator = $this->get('validator');
            $errors = $validator->validate($baseweb);
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
     * @Route("admin/baseweb/one", name="baseweb_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:BaseWeb');
            $baseweb = $repository->findOneBy(array(),array(),1);
            return $this->json($baseweb);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("baseweb/{id}", name="baseweb_detail")
     */
    public function getDetail($id)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:baseweb');
        $baseweb = $repository->find($id);
        return $this->render('home/detail.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'baseweb'       => $baseweb
        ]);
    }


    /**
     * @Route("admin/baseweb/ispublic", name="baseweb_ispublic")
     */
    public function basewebIsPublic(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $baseweb = $em->getRepository('AppBundle:baseweb')->find($request->request->get('id'));
            if (!$baseweb) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($baseweb->getIsPublic()){
                $baseweb->setIsPublic(false);
            }else{
                $baseweb->setIsPublic(true);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($baseweb);
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
     * @Route("admin/baseweb/remove", name="baseweb_remove")
     */
    public function removeLink(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $baseweb = $em->getRepository('AppBundle:baseweb')->find($request->request->get('id'));
            if (!$baseweb) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($baseweb);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}