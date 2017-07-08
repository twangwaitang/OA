<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Advisor;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class AdvisorController extends BaseController
{
    /**
     * @Route("/admin/advisor", name="advisor")
     */
    public function indexAction()
    {

        return $this->render('admin/teacher.html.twig', [


        ]);
    }

    /**
     * @Route("/admin/advisor/upload", name="advisor_upload")
     */
    public function importStudentExcel(Request $request){
        $file = $request->files->get('files')[0];
        $errors =  $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $filePath = $this->get('app.file_uploader')->upload($file);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filePath);
        $objWorksheet = $phpExcelObject->getActiveSheet();
        //获取行数
        $highestRow = $objWorksheet->getHighestRow();
        //获取列数
        $advisor = new Advisor();
        $user = new User();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            $advisor->setNumber($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $advisor->setName($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
            $advisor->setGendar($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
            $advisor->setTel($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
            $advisor->setRoles(Array('ROLE_ADVISOR'));
            $user->setUsername($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $this->getParameter('default_password'));
            $user->setPassword($encoded);
            $user->setRoles(Array('ROLE_ADVISOR'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($advisor);
            $em->persist($user);
            $validator = $this->get('validator');
            $errors = $validator->validate($advisor);
            //$errors = array_merge($validator->validate($advisor), $validator->validate($user));
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            }
                $em->flush();
                $em->clear();
        }
        //上传成功后删除文件
          unlink($filePath);
          return new JsonResponse(array('message' => '操作成功', 'code' => 1));
    }

    public function fileContraints($file){
        $fileContraints = new File(array(
            'maxSize' => '500k',
            'maxSizeMessage' => '文件大小不能超过{{ limit*1000 }}KB'
        ));

       // $fileContraints->mimeTypes = array('application/vnd.ms-excel');
        //$fileContraints->mimeTypesMessage = '文件类型不匹配';
        $errors = $this->get('validator')->validate($file, $fileContraints);
        return $errors;
    }
    /**
     * @Route("admin/advisor/advisors", name="advisors")
     */
    public function getAdvisorAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Advisor');
            $advisor = $repository->findAll();
            return $this->json($advisor);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }
    /**
     * @Route("/admin/advisor/advisortogroup", name="advisor_to_group")
     */
    public function advisorToGroup(Request $request)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $group = $em->getRepository('AppBundle:Group')->find($request->request->get('group_id'));
            $rows = $request->request->get('rows');
            if (!$group) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            foreach ($rows as $row){
                $advisor = $em->getRepository('AppBundle:Advisor')->find($row['id']);
                $advisor->addGroup($group);
                $validator = $this->get('validator');
                $errors = $validator->validate($advisor);
                if (count($errors) > 0) {
                    return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
                }
            }
            $em->flush();
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));
        }
        catch (\Exception $e)
        {
            return new JsonResponse(array('message' => '用户重复输入', 'code' => 0));
        }
    }
    /**
     * @Route("/admin/advisor/add", name="add_advisor")
     */
    public function addAdvisor(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $advisor = new Advisor();
            $user = new User();
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $this->getParameter('default_password'));
            $user->setPassword($encoded);
            $user->setUsername($request->request->get('name'));
            $user->setRoles(Array('ROLE_ADVISOR'));
            $advisor->setName($request->request->get('name'));
            $advisor->setNumber($request->request->get('number'));
            $advisor->setGendar($request->request->get('gendar'));
            $advisor->setTel($request->request->get('tel'));
            $advisor->setIsTop(0);
            $advisor->setRoles(Array('ROLE_ADVISOR'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($advisor);
            $validator = $this->get('validator');
            $errors = $validator->validate($advisor);
            $errors2 = $validator->validate($user);
            if (count($errors) > 0) {
                return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
            } else if(count($errors2) > 0){
                return new JsonResponse(array('message' => $errors2[0]->getMessage(), 'code' => 0));
            } else {

                $em->flush();
                return new JsonResponse(array('message' => '操作成功', 'code' => 1));
            }
        }else{
            throw $this->createAccessDeniedException('拒绝访问');
        }
    }

    /**
     * @Route("/admin/advisor/update", name="update_advisor")
     */
    public function updateAdvisor(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $advisor = $em->getRepository('AppBundle:Advisor')->find($request->request->get('id'));
            if (!$advisor) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $advisor->setName($request->request->get('name'));
            $advisor->setGendar($request->request->get('gendar'));
            $advisor->setNumber($request->request->get('number'));
            $advisor->setTel($request->request->get('tel'));
            $advisor->setIsTop($request->request->get('isTop'));
            $advisor->setPosition($request->request->get('position'));
            $advisor->setFace($request->request->get('face'));
            $advisor->setInfo($request->request->get('info'));
            $validator = $this->get('validator');
            $errors = $validator->validate($advisor);
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
     * @Route("/admin/advisor/remove", name="remove_advisors")
     */
    public function removeAdvisors(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $advisor = $em->getRepository('AppBundle:Advisor')->find($id);
                if (!$advisor) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($advisor);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }

}