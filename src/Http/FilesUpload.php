<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Interfaces\Http\FilesUploadInterface;

/**
 * FilesUpload Class
 */
class FilesUpload implements FilesUploadInterface
{
    /**
     * @var string $path
     */
    private $path;

    /**
     * FilesUpload Constructor
     */
    public function __constructor($path)
    {
        $this->path = $path;
    }

    /**
     * Upload File
     * 
     * @param string $file
     * @return bool
     */
    final public function upload($file = null)
    {
        if (empty($this->path)) {
            throw new \Exception("Error : No uploads path was provided");
        }

        if (empty($file)) {
            throw new \Exception("Error : File doesn't exist");
        }

        try {
            move_uploaded_file($file["tmp_name"], $this->path.$file["name"]);
            return true;
        } catch (\Exception $e) {
            throw new $e;
        }

        return false;
    }
}