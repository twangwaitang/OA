<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Score;
use Faker\Provider\Base;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class ScoreController extends BaseController
{

    /**
     * @Route("score/show/{exam_id}", name="show_score")
     */
    public function showScore($exam_id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $exam = $em->getRepository('AppBundle:Exam')->find($exam_id);
            $scores = $em->getRepository('AppBundle:Score')->findBy(array('exam'=>$exam_id));
            $scores = $this->Pagination($scores,$request);
            return $this->render('admin/score.html.twig', [
                'scores' => $scores
            ]);
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

    /**
     * @Route("notes/isgood", name="notes_isgood")
     */
    public function noteToGood(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $note = $em->getRepository('AppBundle:Notes')->find($request->request->get('id'));
            if (!$note) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            if($request->request->get('type') === 'cancel'){
                $note->setIsGood(0);
            }else{
                $note->setIsGood(1);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($note);
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