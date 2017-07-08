<?php
/**
 * Created by PhpStorm.
 * User: jiangcoco
 * Date: 2017/4/26
 * Time: 12:22
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Constraints\File;


class BaseController extends Controller
{

    protected  $session;
    public function __construct()
    {
        $this->session =  new Session();
    }
    public function exportExcel($recodes){
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        //设置文件属性
        $phpExcelObject->getProperties()
            ->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");
        //设置文件内容
        $result = $phpExcelObject->setActiveSheetIndex(0);

        $result->setCellValue('A1', 'Hello');
        $result->setCellValue('B2', 'world!');
        //设置当前表的标题
        $phpExcelObject->getActiveSheet()
            ->setTitle('Simple');
        $phpExcelObject->setActiveSheetIndex(0);
        //创建一个写对象
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        //设置HTTP协议（创建下载文件）
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response;
    }
//序列化
    public function serializer ($obj){
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array($normalizer), array($encoder));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $obj = $serializer->serialize($obj, 'json');
        return $obj;
    }
    //分页
    public function Pagination ($objs,$request,$page=10){
        $paginator = $this->get('knp_paginator');
        $obj = $paginator->paginate(
            $objs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $page)
        );
        return $obj;
    }
    //验证
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
    //登录
}