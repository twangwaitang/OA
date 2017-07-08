<?php
namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Entity\User;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginType;


class NewsController extends BaseController
{

    /**
     * @Route("admin/news", name="news")
     */
    public function getNews(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:News');
            $news = $repository->findAll();
            $news = $this->Pagination($news,$request);
            return $this->render('admin/news.html.twig',[
                'news'       => $news
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("admin/news/add", name="news_add")
     */
    public function addNews(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $news = new News();
            $news->setContent($request->request->get('content'));
            $news->setTitle($request->request->get('title'));
            $news->setType($request->request->get('type'));
            $em->persist($news);
            $validator = $this->get('validator');
            $errors = $validator->validate($news);
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
     * @Route("admin/news/update", name="news_update")
     */
    public function updateNews(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:News');
            $news = $repository->find($request->request->get('id'));
            $news->setContent($request->request->get('content'));
            $news->setTitle($request->request->get('title'));
            $news->setType($request->request->get('type'));
            $validator = $this->get('validator');
            $errors = $validator->validate($news);
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
     * @Route("admin/news/one", name="news_one")
     */
    public function getOne(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:News');
            $news = $repository->find($request->request->get('id'));
            return $this->json($news);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("news/{id}", name="news_detail")
     */
    public function getDetail($id)
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $helper = $this->get('security.authentication_utils');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:News');
        $news = $repository->find($id);
        return $this->render('home/detail.html.twig',[
            'form'          => $form->createView(),
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'news'       => $news
        ]);
    }


    /**
     * @Route("admin/news/istop", name="news_istop")
     */
    public function newsIsTop(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $news = $em->getRepository('AppBundle:News')->find($request->request->get('id'));
            if (!$news) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($request->request->get('type') === 'cancel'){
                $news->setIsTop(0);
            }else{
                $news->setIsTop(1);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($news);
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
     * @Route("admin/news/remove", name="remove_news")
     */
    public function removeLesson(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $news = $em->getRepository('AppBundle:News')->find($request->request->get('id'));
            if (!$news) {
                throw $this->createNotFoundException('没有找数据id:' .$request->request->get('id'));
            }
            $em->remove($news);
            $em->flush();

            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}