<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use App\Service\LoadCsv;

class RedirectController extends AbstractController
{
    /**
     * @Route("/loadcsv", methods={"GET"}, name="redirect")
     */
    public function index(Request $request)
    {
        return $this->render('redirect/index.html.twig');
    }

    /**
     * @Route("/loadcsv", methods={"POST"}, name="loadcsv")
     */
    public function loadcsv(Request $request, FileUploader $fileUploader, LoadCsv $loadcsv)
    {

      $filename = $_FILES['upload-file']['name'];
      if ($_FILES['upload-file'] && 'csv' == pathinfo($filename, PATHINFO_EXTENSION)) {

        $file = new UploadedFile($_FILES['upload-file']['tmp_name'],$filename,'text/csv');
        $fileName = $fileUploader->upload($file);
        $data = $loadcsv->load($fileName);
        $this->addFlash(
            'success',
            $data
        );

      }
      else {
        $this->addFlash(
            'danger',
            'File Invalide, you need to load a CSV file.'
        );

      }
      return $this->render('redirect/index.html.twig');
    }
}
