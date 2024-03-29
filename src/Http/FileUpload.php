<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Exceptions\FileNotFoundException;
use SigmaPHP\Core\Exceptions\InvalidArgumentException;
use SigmaPHP\Core\Interfaces\Http\FileUploadInterface;

/**
 * File Upload Class
 */
class FileUpload implements FileUploadInterface
{
    /**
     * @var string $path
     */
    private $path;

    /**
     * FileUpload Constructor
     */
    public function __construct($path)
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
            throw new InvalidArgumentException(
                "Error : No uploads path was provided"
            );
        }

        if (empty($file) || !file_exists($file["tmp_name"])) {
            throw new FileNotFoundException("Error : File doesn't exist");
        }

        return rename($file["tmp_name"], $this->path.'/'.$file["name"]);
    }
}