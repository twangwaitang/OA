<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Makers;
use PhpSpec\Exception\Fracture\NamedConstructorNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;


class MakersController extends Controller
{
    /**
     * @Route("admin/makers/add", name="makers_add")
     */
    public function addMakers(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $maker = new Makers();
            foreach ($request->request->get('pramas') as $makers) {
                $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('id'));
                $maker->setMakerTime( $makers['time']);
                $question = $em->getRepository('AppBundle:Questions')->find($makers['text']);
                $maker->setQuestion($question);
                $maker->setLesson($lesson);
                $em->persist($maker);
                $validator = $this->get('validator');
                $errors = $validator->validate($maker);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                } else {
                    $em->flush();
                    $em->clear();
                }
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

    /**
     * @Route("makers/getMakersInLesson", name="makers")
     */
    public function noteToGood(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $makers = $em->getRepository('AppBundle:Makers')->findBy(array('lesson'=>$request->request->get('id')));
            if (!$makers) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            return $this->json($makers);

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}