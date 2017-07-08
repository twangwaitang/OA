<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Student;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends BaseController
{
    /**
     * @Route("/admin/student", name="student")
     */
    public function indexAction()
    {
        $groups = $this->studentGroups();
        $total = $this->getTotalRecodes();
        return $this->render('admin/student.html.twig', [
                'groups' => $groups,
                'total' => $total
        ]);
    }

    public function getTotalRecodes()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $total = $repository->getTotalRecodes();
        return $total;
    }

    public function studentGroups()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $groups = $repository->findStudentsByGroups();
        return $groups;
    }

    /**
     * @Route("/admin/student/upload", name="student_upload")
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
        $student = new Student();
        $user = new User();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            $student->setNumber($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $student->setName($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
            $student->setGendar($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
            $student->setGrade($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
            $student->setTel($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
            $user->setUsername($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, '123456');
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->persist($user);
            $validator = $this->get('validator');
            $errors = $validator->validate($student);
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
     * @Route("admin/student/students", name="students")
     */
    public function getStudentAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
            if ($request->query->get('grade')){
                $grade = $request->query->get('grade');
                $student = $repository->findBy(array('grade'=>$grade));
            }else{
                $student = $repository->findAll();
            }
            return $this->json($student);
        } else {
            throw $this->createAccessDeniedException('拒绝访问');

        }

    }

    /**
     * @Route("/admin/student/add", name="add_student")
     */
    public function addStudents(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $student = new Student();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $request->request->get('password'));
            $user->setPassword($encoded);
            $user->setUsername($request->request->get('username'));
            $user->setAge($request->request->get('age'));
            $user->setEmail($request->request->get('email'));
            $user->setPid($request->request->get('position'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
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
     * @Route("/admin/student/studnettogroup", name="student_to_group")
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
               $student = $em->getRepository('AppBundle:Student')->find($row['id']);

               $student->addGroup($group);

               $validator = $this->get('validator');
               $errors = $validator->validate($student);
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
     * @Route("/admin/student/update", name="update_student")
     */
    public function updateStudent(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $student = new Student();
            $em = $this->getDoctrine()->getManager();
            $student = $em->getRepository('AppBundle:Student')->find($request->request->get('id'));
            if (!$student) {
                throw $this->createNotFoundException(
                    '没有找到数据'
                );
            }
            $student->setName($request->request->get('name'));
            $student->setGendar($request->request->get('gendar'));
            $student->setNumber($request->request->get('number'));
            $student->setTel($request->request->get('tel'));
            $student->setGrade($request->request->get('grade'));
            $validator = $this->get('validator');
            $errors = $validator->validate($student);
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
     * @Route("/admin/student/remove", name="remove_students")
     */
    public function removeStudents(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getEntityManager();
            $ids = explode(",", $request->request->get('ids'));
            foreach ($ids as $id) {
                $student = $em->getRepository('AppBundle:Student')->find($id);
                if (!$student) {
                    throw $this->createNotFoundException('没有找数据id:' . $id);
                }
                $em->remove($student);
                $em->flush();
            }
            return new JsonResponse(array('message' => '操作成功', 'code' => 1));

        } else {
            throw $this->createAccessDeniedException('非法访问');
        }
    }


}