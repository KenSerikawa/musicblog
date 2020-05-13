<?php

namespace App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader 
{
    
    /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;   
    }


    public function updateFile(UploadedFile $file, string $foldername)
    {
        // add unique id plus the extension of the file (MIME)
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

        $foldername = 'uploads_' . $foldername;
 
        $targetDirectory = $this->container->getParameter($foldername);

        $file->move(
            $targetDirectory, // to
            $filename
        );

        return $filename;
    }

}