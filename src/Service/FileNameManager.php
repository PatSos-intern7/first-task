<?php
namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class FileNameManager extends Filesystem
{
    public function checkFileName($filename)
    {
        return $this->exists($filename.'.txt');
    }

    public function getFileName($filename)
    {
        while($this->checkFileName($filename)){
            $fileNamePrefix = preg_split('/\d+$/',$filename);
            $lastDigit = str_replace($fileNamePrefix, '',$filename);
            $lastDigit = (int)$lastDigit;
            $lastDigit++;
            $filename = $fileNamePrefix[0].$lastDigit;
        }
        return $filename;
    }
}