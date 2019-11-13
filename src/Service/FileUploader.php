<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {

        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function removeFile($filename)
    {
        $files= glob($this->targetDirectory.'/'.$filename);
        foreach ($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
    }
}