<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\Request;

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

    
}