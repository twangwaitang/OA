<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends BaseController
{
    /**
     * @Route("/admin/teacher", name="teacher")
     */
    public function indexAction()
    {

        return $this->render('admin/teacher.html.twig', [


        ]);
    }

    /**
     * @Route("/admin/teacher/{number}", name="teacher_one")
     */
    public function getOneAction($number)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
        $teacher = $repository->findOneBy(array('number'=>$number));
        return $this->json($teacher);

    }
    /**
     * @Route("/admin/t/uploadface", name="face_upload")
     */
    public function uploadFace(Request $request){
        $file = $request->files->get('files')[0];
        $errors = $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $fileName = $this->get('app.file_uploader')->upload($file,$this->getParameter('face_upload_middle_path'));
        return new JsonResponse(array('message' => '上传成功', 'fileName' => $fileName,'code' => 1));
    }
    /**
     * @Route("/admin/teacher/upload", name="teacher_upload")
     */
    public function importStudentExcel(Request $request){
        $file = $request->files->get('files')[0];
        $errors =  $this->fileContraints($file);
        if (count($errors) > 0) {
            return new JsonResponse(array('message' => $errors[0]->getMessage(), 'code' => 0));
        }
        $filePath = $this->get('app.file_uploader')->upload($file);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filePath);
        //$inputFileType = 'Excel2007';//这个是计xlsx的
        //指定为哪张表（默认第一张表）
        //$objWorksheet = $phpExcelObject->getActiveSheet("sheet1");
        $objWorksheet = $phpExcelObject->getActiveSheet();
        //获取行数
        $highestRow = $objWorksheet->getHighestRow();
        //获取列数
        //$highestColumn = $objWorksheet->getHighestColumn();
        $teacher = new Teacher();
        $user = new User();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            $teacher->setNumber($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $teacher->setName($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
            $teacher->setGendar($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
            $teacher->setTel($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
            $teacher->setRoles(Array('ROLE_TEACHER'));
            $user->setUsername($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $this->getParameter('default_password'));
            $user->setPassword($encoded);
            $user->setRoles(Array('ROLE_TEACHER'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->persist($user);
            $validator = $this->get('validator');
            $errors = $validator->validate($teacher);
            //$errors = array_merge($validator->validate($teacher), $validator->validate($user));
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

    /**
     * @Route("/admin/t/teachers", name="teacher_list")
     */
    public function getTeacherAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');
            $teacher = $repository->findAll();
            return $this->json($teacher);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }
    /**
     * @Route("/admin/teacher/teachertogroup", name="teacher_to_group")
     */
    public function StudentToGroup(Request $request)
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
                $teacher = $em->getRepository('AppBundle:Teacher')->find($row['id']);
                $teacher->addGroup($group);
                $validator = $this->get('validator');
                $errors = $validator->validate($teacher);
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
     * @Route("/admin/teacher/add", name="add_teacher")
     */
    public function addTeacher(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $teacher = new Teacher();
            $user = new User();
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $this->getParameter('default_password'));
            $user->setPassword($encoded);
            $user->setUsername($request->request->get('name'));
            $user->setRoles(Array('ROLE_TEACHER'));
            $teacher->setName($request->request->get('name'));
            $teacher->setNumber($request->request->get('number'));
            $teacher->setGendar($request->request->get('gendar'));
            $teacher->setTel($request->request->get('tel'));
            $teacher->setRoles(Array('ROLE_TEACHER'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($teacher);
            $validator = $this->get('validator');
            $errors = $validator->validate($teacher);
            $errors2 = $validator->validate($user);
           // return new Response(var_dump($teacher));
//            $logger = $em->getConnection()
//                ->getConfiguration()
//                ->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
//            return new JsonResponse(array('message' => $logger, 'code' => 1));
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
     * @Route("/admin/t/update", name="teacher_update")
     */
    public function updateTeacher(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $teacher = $em->getRepository('AppBundle:Teacher')->find($request->request->get('id'));
            if (!$teacher) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $teacher->setName($request->request->get('name'));
            $teacher->setGendar($request->request->get('gendar'));
            $teacher->setNumber($request->request->get('number'));
            $teacher->setTel($request->request->get('tel'));
            $teacher->setPosition($request->request->get('position'));
            $teacher->setFace($request->request->get('face'));
            $teacher->setInfo($request->request->get('info'));
            $teacher->setIsTop($request->request->get('isTop'));
            $validator = $this->get('validator');
            $errors = $validator->validate($teacher);
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
     * @Route("/admin/teacher/remove", name="remove_teachers")
     */
    public function removeStudents(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $teacher = $em->getRepository('AppBundle:Teacher')->find($id);
                if (!$teacher) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($teacher);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }
}