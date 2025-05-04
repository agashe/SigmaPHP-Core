<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\Response;

/**
 * Response Test
 */
class ResponseTest extends TestCase
{
    /**
     * @var Response $response
     */
    private $response;

    /**
     * ResponseTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->response = new Response();
    }


    /**
     * Get all headers helper
     * 
     * @param string $headerName to get specific header
     * @return array|string|null
     */
    private function getAllHeadersHelper($headerName = null)
    {
        $headers = [];

        foreach ($_SERVER as $key => $val) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $headers[
                    ucwords(
                        strtolower(
                            str_replace(
                                '_', '-', str_replace('HTTP_', '', $key)
                            )
                        ),
                    '-')
                ] = $val;
            }
        }

        return empty($key) ? $headers : ($headers[$headerName] ?? null);
    }

    /**
     * Fake response.
     * 
     * To test response headers.
     *
     * @param array $data
     * @param string $type
     * @param integer $statusCode
     * @param array $headers
     * @return void
     */
    final public function fakeResponse(
        $data = [], 
        $type = 'text/html', 
        $statusCode = 200, 
        $headers = []
    ) {
        $this->response->response(
            $data,
            $type,
            $statusCode,
            $headers,
        );

        $_SERVER['HTTP_CONTENT_TYPE'] = $type;

        if (!empty($headers)) {
            foreach ($headers as $key => $val) {
                $_SERVER['HTTP_' . strtoupper($key)] = $val;
            }
        }
    }

    /**
     * Test http response.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHttpResponse()
    {
        $this->response->response('hello world');
        $this->expectOutputString('hello world');
    }

    /**
     * Test response returns the content type.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHttpResponseReturnsTheContentType()
    {
        $this->fakeResponse('hello world', 'text/xml');

        
        $this->assertEquals(
            'text/xml',
            $this->getAllHeadersHelper('Content-Type')
        );

        ob_clean(); // clear output
    }

    /**
     * Test response returns the status code.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHttpResponseReturnsTheStatusCode()
    {
        $this->response->response('hello world', 'text/plain', 201);
        
        $this->assertEquals(201, http_response_code());
        
        ob_clean(); // clear output
    }

    /**
     * Test json response.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testJsonResponse()
    {
        $message = [
            'data' => 'hello world'
        ];

        $this->response->responseJSON(json_encode($message));
        
        $this->expectOutputString('"{\"data\":\"hello world\"}"');        
    }
}