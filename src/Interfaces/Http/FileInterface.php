<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * File Interface
 */
interface FileInterface
{
    /**
     * Save file to storage.
     *
     * @param string $fileName
     * @return bool
     */
    public function save($fileName);

    /**
     * Check if file exists in the storage.
     *
     * @param string $fileName
     * @return bool
     */
    public function has($fileName);

    /**
     * Retrieve file's content from storage.
     *
     * @param string $fileName
     * @return mixed
     */
    public function get($fileName);
}
