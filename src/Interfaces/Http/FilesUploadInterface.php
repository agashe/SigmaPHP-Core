<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * FilesUpload Interface
 */
interface FilesUploadInterface
{
    /**
     * Upload File.
     * 
     * @param string $file
     * @return bool
     */
    public function upload($file);
}