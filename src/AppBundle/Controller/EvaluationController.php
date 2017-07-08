<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Evaluation;
use AppBundle\Entity\EvaluationDetail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class EvaluationController extends BaseController
{
    /**
     * @Route("admin/evaluation", name="evaluation")
     */
    public function EvaluationAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Evaluation');
        $evaluations = $repository->findAll();
        $evaluations = $this->Pagination($evaluations,$request);
        return $this->render('admin/evaluation.html.twig', [
            'evaluations'       => $evaluations,
        ]);
    }
    /**
 * @Route("admin/evaluation/add", name="evaluation_add")
 */
    public function addEvaluation(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $evaluation = new Evaluation();
            $evaluation->setTitle($request->request->get('name'));
            $em->persist($evaluation);
            $validator = $this->get('validator');
            $errors = $validator->validate($evaluation);
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
     * 接受数据为：教师账号，课程id,评教Id,学生id，总分
     * @Route("admin/evaluation-detail/add", name="evaluation_detail_add")
     */
    public function addEvaluationDetail(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $evaluationDetail = new EvaluationDetail();
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array('username'=>$request->request->get('number')));
            $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(array('id'=>$request->request->get('sid')));
            $course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($request->request->get('courseId'));
            $evaluation = $this->getDoctrine()->getRepository('AppBundle:Evaluation')->find($request->request->get('evaluationId'));
            $evaluationDetail->setUser($user);
            $evaluationDetail->setScore($request->request->get('score'));
            $evaluationDetail->setCourse($course);
            $evaluationDetail->setEvaluation($evaluation);
            $evaluationDetail->setStudent($student);
            $em->persist($evaluationDetail);
            $validator = $this->get('validator');
            $errors = $validator->validate($evaluationDetail);
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
     * 接受数据为：教师账号，课程id,评教Id,学生id，总分
     * @Route("admin/evaluation-detail/{id}", name="get_evaluation_detail")
     */
    public function getEvaluationDetailById($id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $evaluationDetails = $this->getDoctrine()->getRepository('AppBundle:EvaluationDetail')->getEvaluation($id);
            $evaluationDetails = $this->Pagination($evaluationDetails,$request,50);
            return $this->render('admin/evaluation-detail.html.twig', [
                'evaluationDetails'       => $evaluationDetails,
            ]);
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }
    /**
     * @Route("admin/evaluation/update", name="evaluation_update")
     */
    public function updateEvaluation(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:Evaluation');
            $evaluation = $repository->find($request->request->get('id'));
            $evaluation->setTitle($request->request->get('name'));
            $validator = $this->get('validator');
            $errors = $validator->validate($evaluation);
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
     * @Route("admin/evaluation/start", name="evaluation_start")
     */
    public function startEvaluation(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AppBundle:Evaluation');
            $evaluation = $repository->find($request->request->get('id'));
            if($evaluation->getIsStarted()){
                $evaluation->setIsStarted(false);
            }else{
                $evaluation->setIsStarted(true);
            }
            $validator = $this->get('validator');
            $errors = $validator->validate($evaluation);
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

}