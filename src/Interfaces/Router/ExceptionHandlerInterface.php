<?php

namespace SigmaPHP\Core\Interfaces\Router;

/**
 * Exception Handler Interface
 */
interface ExceptionHandlerInterface
{
    /**
     * Create new response for the exception.
     *
     * @param \Throwable $thrown
     * @return SigmaPHP\Core\Http\Response
     */
    public static function handle($thrown);
}
