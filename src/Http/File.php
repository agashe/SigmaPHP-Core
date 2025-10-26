<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Exceptions\FileNotFoundException;
use SigmaPHP\Core\Exceptions\InvalidArgumentException;
use SigmaPHP\Core\Interfaces\Http\FileInterface;

/**
 * File Class
 */
class File implements FileInterface
{
    /**
     * @var string $storagePath
     */
    private $storagePath;

    /**
     * File Constructor
     * 
     * @param string $storagePath
     */
    public function __construct($storagePath)
    {
        if (empty($storagePath)) {
            throw new InvalidArgumentException(
                "No storage path was provided"
            );
        }
        
        $storageFullPath = root_path($storagePath);
        
        if (!file_exists($storageFullPath)) {
            throw new InvalidArgumentException(
                "Storage path {$storageFullPath} doesn't exists"
            );
        }

        $this->storagePath = $storageFullPath;
    }

    /**
     * Save file to storage.
     * 
     * @param string $fileName
     * @return bool
     */
    public function save($fileName)
    {
        if (empty($fileName) || empty(container('request')->files($fileName))) {
            throw new FileNotFoundException("File {$fileName} doesn't exist");
        }

        $fileObject = container('request')->files($fileName);

        if (!file_exists($fileObject["tmp_name"])) {
            throw new FileNotFoundException("File {$fileName} doesn't exist");
        }

        return rename(
            $fileObject["tmp_name"],
            $this->storagePath . '/' . $fileObject["name"]
        );
    }

    /**
     * Retrieve file's content from storage.
     * 
     * @param string $fileName
     * @return mixed
     */
    public function get($fileName)
    {
        $fileFullPath = $this->storagePath . '/' . ltrim($fileName, '/');

        if (!file_exists($fileFullPath)) {
            throw new FileNotFoundException("File {$fileName} doesn't exist");
        }

        return file_get_contents(
            $this->storagePath . '/' . ltrim($fileName, '/')
        );
    }
}