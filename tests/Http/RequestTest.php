<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\Request;

/**
 * Request Test
 */
class RequestTest extends TestCase
{
    /**
     * @var Request $request
     */
    private $request;

    /**
     * RequestTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->request = new Request();
    }

    /**
     * Test retrieve all GET data from request.
     *
     * @return void
     */
    public function testRetrieveAllGetDataFromRequest()
    {
        $_GET['hello1'] = 'world1';
        $_GET['hello2'] = 'world2';
        $_GET['hello3'] = 'world3';

        $this->assertCount(count($_GET), $this->request->get());
    }

    /**
     * Test retrieve specific GET data from request.
     *
     * @return void
     */
    public function testRetrieveSpecificGetDataFromRequest()
    {
        $_GET['hello'] = 'world';

        $this->assertEquals('world', $this->request->get('hello'));
    }
    
    /**
     * Test returns null if key not found in GET request.
     *
     * @return void
     */
    public function testReturnsNullIfKeyNotFoundInGetRequest()
    {
        $_GET = [];

        $this->assertNull($this->request->get('hello'));
    }

    /**
     * Test retrieve all POST data from request.
     *
     * @return void
     */
    public function testRetrieveAllPostDataFromRequest()
    {
        $_POST['hello1'] = 'world1';
        $_POST['hello2'] = 'world2';
        $_POST['hello3'] = 'world3';

        $this->assertCount(count($_POST), $this->request->post());
    }

    /**
     * Test retrieve specific POST data from request.
     *
     * @return void
     */
    public function testRetrieveSpecificPostDataFromRequest()
    {
        $_POST['hello'] = 'world';

        $this->assertEquals('world', $this->request->post('hello'));
    }
    
    /**
     * Test returns null if key not found in POST request.
     *
     * @return void
     */
    public function testReturnsNullIfKeyNotFoundInPostRequest()
    {
        $_POST = [];

        $this->assertNull($this->request->post('hello'));
    }

    /**
     * Test retrieve all FILES data from request.
     *
     * @return void
     */
    public function testRetrieveAllFilesDataFromRequest()
    {
        $_FILES['file1'] = [
            'name' => 'test file 1',
            'type' => 'text/plain',
            'size' => '123'
        ];

        $_FILES['file2'] = [
            'name' => 'test file 2',
            'type' => 'text/plain',
            'size' => '123'
        ];

        $_FILES['file3'] = [
            'name' => 'test file 3',
            'type' => 'text/plain',
            'size' => '123'
        ];

        $this->assertCount(count($_FILES), $this->request->files());
    }

    /**
     * Test retrieve specific FILES data from request.
     *
     * @return void
     */
    public function testRetrieveSpecificFilesDataFromRequest()
    {
        $_FILES['file1'] = [
            'name' => 'test file 1',
            'type' => 'text/plain',
            'size' => '123'
        ];

        $this->assertIsArray($this->request->files('file1'));
    }
    
    /**
     * Test returns null if key not found in FILES request.
     *
     * @return void
     */
    public function testReturnsNullIfKeyNotFoundInFilesRequest()
    {
        $this->assertNull($this->request->files('hello'));
    }
}