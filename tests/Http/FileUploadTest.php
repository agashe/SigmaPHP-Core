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
        $this->testFile = [
            'name' => 'test.txt',
            'type' => 'text/plain',
            'tmp_name' => dirname(__DIR__, 2) . '/file12345',
            'size' => 101,
            'error' => UPLOAD_ERR_OK
        ];

        $this->fileUploader = new FileUpload(dirname(__DIR__, 2) . '/uploads');
    }
   
    /**
     * Test file is uploaded.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileIsUploaded()
    {
        // create dummy file
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }

        if (!file_exists('file12345')) {
            file_put_contents('file12345', 'hello world');
        }

        $this->fileUploader->upload($this->testFile);
        $this->assertTrue(file_exists('uploads/test.txt'));

        // remove the dummy file
        if (file_exists('uploads/test.txt')) {
            unlink('uploads/test.txt');
        }

        if (is_dir('uploads')) {
            rmdir('uploads');
        }

        if (file_exists('file12345')) {
            unlink('file12345');
        }
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
        $this->fileUploader->upload($this->testFile);
        $this->assertFalse(file_exists('uploads/test.txt'));
    }
}