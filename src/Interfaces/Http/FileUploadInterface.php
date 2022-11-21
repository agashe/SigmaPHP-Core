<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * FileUpload Interface
 */
interface FileUploadInterface
{
    /**
     * Upload File.
     * 
     * @param string $file
     * @return bool
     */
    public function upload($file);
}