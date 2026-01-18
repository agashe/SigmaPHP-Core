<?php

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\File;
use SigmaPHP\Core\Exceptions\FileNotFoundException;
use SigmaPHP\Core\Exceptions\InvalidArgumentException;

/**
 * File Test
 */
class FileTest extends TestCase
{
    /**
     * @var File $file
     */
    private $file;

    /**
     * @var array $testFile
     */
    private $testFile;

    /**
     * FileTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        // input field
        $this->testFile = 'user_notes';

        $_FILES[$this->testFile] = [
            'name' => 'test.txt',
            'type' => 'text/plain',
            'tmp_name' => 'file12345',
            'size' => 11,
            'error' => UPLOAD_ERR_OK
        ];

        $this->file = new File('uploads');
    }

    /**
     * Test file is saved to storage.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileIsSavedToStorage()
    {
        $this->file->save($_FILES[$this->testFile]);
        $this->assertTrue(file_exists('uploads/test.txt'));
    }

    /**
     * Test get file content.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetFileContent()
    {
        $this->assertEquals(
            'My Book',
            $this->file->get('book.txt')
        );
    }

    /**
     * Test check if file exists in the storage.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCheckIfFileExistsInTheStorage()
    {
        $this->assertTrue($this->file->has('book.txt'));
    }

    /**
     * Test throws exception if no path was provided to the file service.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoPathWasProvidedToFileService()
    {
        $this->expectException(InvalidArgumentException::class);

        $testFile = new File(null);
    }

    /**
     * Test throws exception if invalid path was provided to the file service.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfInvalidPathWasProvidedToFileService()
    {
        $this->expectException(InvalidArgumentException::class);

        $testFile = new File('xyz/');
    }

    /**
     * Test throws exception if invalid file was provided for save.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfInvalidFileWasProvidedForSave()
    {
        $this->expectException(FileNotFoundException::class);
        $this->file->save([
            'name' => 'test.txt',
            'type' => 'text/plain',
            'size' => 11,
            'error' => UPLOAD_ERR_OK
        ]);
    }

    /**
     * Test throws exception if no file was provided for save.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoFileWasProvidedForSave()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->file->save(null);
    }

    /**
     * Test throws exception if file could not be saved.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfFileCouldNotBeSaved()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->file->save('not_found');
    }

    /**
     * Test throws exception if trying to get content of non exists file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfTryingToGetContentOfNonExistsFile()
    {
        $this->expectException(FileNotFoundException::class);

        $this->file->get('not_found');
    }
}
