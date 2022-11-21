<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\FileUpload;

/**
 * FileUpload Test
 */
class FileUploadTest extends TestCase
{
    /**
     * @var FileUpload $fileUploader
     */
    private $fileUploader;

    /**
     * @var array $testFile
     */
    private $testFile;

    /**
     * FileUploadTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fileUploader = new FileUpload('uploads/');

        // create dummy file
        file_put_contents('tmp/file12345', 'hello world');

        $this->testFile = [
            'name' => 'test.txt',
            'type' => 'text/plain',
            'tmp_name' => 'tmp/file12345',
            'size' => 101,
            'error' => UPLOAD_ERR_OK
        ];
    }
   
    /**
     * Test file is uploaded.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileIsUploaded()
    {
        var_dump($_SERVER['DOCUMENT_ROOT']);
        $this->assertTrue($this->fileUploader->upload($this->testFile));
    }

    /**
     * Test throws exception if no path was provided to fileUploader.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoPathWasProvidedToFileUploader()
    {
        $this->expectException(\Exception::class);
        
        $testFileUploader = new FileUpload(null);
        $testFileUploader->upload($this->testFile);
    }

    /**
     * Test throws exception if no file was provided to fileUploader.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoFileWasProvidedToFileUploader()
    {
        $this->expectException(\Exception::class);
        $this->fileUploader->upload(null);
    }

    /**
     * Test fileUploader returns false if file could not be saved.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileUploaderReturnsFalseIfFileCouldNotBeSaved()
    {
        $this->expectException(\Exception::class);
        $this->assertFalse($this->fileUploader->upload($this->testFile));
    }
}