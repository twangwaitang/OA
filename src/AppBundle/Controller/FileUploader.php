<?php
/**
 * Created by PhpStorm.
 * User: jiangcoco
 * Date: 2017/4/27
 * Time: 8:34
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader extends Controller
{
    private $targetDir;
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;

    }

    public function upload(UploadedFile $file,$middlePath)
    {
        if ($file){

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $filePath = $this->targetDir.$middlePath.$fileName;

            $file->move($this->targetDir.$middlePath, $fileName);

            return $fileName;
        }
        else return new JsonResponse(array('message' => '上传文件不存在', 'code' => 0));
    }


}


