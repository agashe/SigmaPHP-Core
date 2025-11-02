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
     * @param array $fileData
     * @return bool
     */
    public function save($fileData)
    {
        if (!is_array($fileData)) {
            throw new InvalidArgumentException(
                "Invalid file upload was provided !"
            );
        }

        if (!isset($fileData["tmp_name"]) ||
            !file_exists($fileData["tmp_name"])
        ) {
            throw new FileNotFoundException(
                "The file you are trying to upload is invalid or doesn't exist"
            );
        }

        return rename(
            $fileData["tmp_name"],
            $this->storagePath . '/' . $fileData["name"]
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