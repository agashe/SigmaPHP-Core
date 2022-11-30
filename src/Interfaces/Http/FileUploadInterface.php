<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * File Upload Interface
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