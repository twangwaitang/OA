<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class BlogController extends BaseController
{
    /**
     * @Route("/blog/add", name="blog_add")
     */
    public function blogAddAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $blog = new Blog();
            $student = $em->getRepository('AppBundle:Student')->findOneBy(array('number'=>$request->request->get('username')));
            $blog->setContent($request->request->get('content'));
            $blog->setTitle($request->request->get('title'));
            $blog->setStudent($student);
            $em->persist($blog);
            $validator = $this->get('validator');
            $errors = $validator->validate($blog);
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
     * @Route("/blog/detail/{blog_id}", name="blog_detail")
     */
    public function blogDetailAction($blog_id){
        $blog = $this->getDoctrine()->getRepository('AppBundle:Blog')->find($blog_id);
        return $this->render('home/blog_detail.html.twig', [
            'blog'=>$blog
        ]);
    }
    /**
     * @Route("/blog/{id}", name="blog")
     */
    public function getOneBlog($id){
        $blog = $this->getDoctrine()->getRepository('AppBundle:Blog')->find($id);
        return $this->render('home/blog_detail.html.twig', [
            'blog'=>$blog
        ]);
    }
    /**
     * @Route("admin/blogs", name="blogs")
     */
    public function getBlogs(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Blog');
            $blogs = $repository->findAll();
            $blogs = $this->Pagination($blogs,$request);
            return $this->render('admin/blogs.html.twig',[
                'blogs'       => $blogs
            ]);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }
    }
    /**
     * @Route("admin/blog/istop", name="blog_istop")
     */
    public function blogIsTop(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $blog = $em->getRepository('AppBundle:Blog')->find($request->request->get('id'));
            if (!$blog) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($request->request->get('type') === 'cancel'){
                $blog->setIsGood(0);
            }else{
                $blog->setIsGood(1);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($blog);
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

}
