<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Recode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class RecodeController extends Controller
{

    /**
     * @Route("recode/add", name="recode_add")
     */
    public function addRecodes(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
           try{
            $em = $this->getDoctrine()->getManager();
            $recode = new Recode();
            $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lessonId'));
            $student = $em->getRepository('AppBundle:Student')->findOneBy(array('number'=>$request->request->get('username')));
            if(!$student) return new JsonResponse(array('message' => '恭喜你!完成该视频', 'code' => 1));
            $course = $em->getRepository('AppBundle:Course')->find($request->request->get('courseId'));
            $recode->setLesson($lesson);
            $recode->setStudent($student);
            $recode->setCourse($course);
            $em->persist($recode);
            $validator = $this->get('validator');
            $errors = $validator->validate($recode);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else {
                $em->flush();
                return new JsonResponse(array('message' => '恭喜你!完成该视频', 'code' => 1));
            }
           }
           catch (\Exception $e)
            {
             return new JsonResponse(array('message' => '已完成该视频', 'code' => 1));
            }
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

}