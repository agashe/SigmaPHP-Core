<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Core\Interfaces\Router\ExceptionHandlerInterface;
use SigmaPHP\Core\Views\ViewHandler;

/**
 * Exception Handler Class
 */
class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var array ERRORS
     */
    const ERRORS = [
        // 4xx Client Errors
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // :D
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',

        // 5xx Server Errors
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * @var ViewHandler $viewHandler
     */
    private $viewHandler;

    /**
     * ExceptionHandler Constructor
     */
    public function __construct() {
        // ? We are doing it like this, since the template engine currently
        // ? doesn't support loading templates outside default template's path
        // ToDO: Find better way to load the ViewHandler

        $this->viewHandler = new ViewHandler(
            str_replace(root_path(''), '', __DIR__) . '/templates',
            ''
        );
    }

    /**
     * Format exception.
     *
     * Just extract as associate array the message, file, line, code and trace.
     *
     * @param \Throwable $thrown
     * @return array
     */
    private function formatException($thrown)
    {
        $traces = [];
        $classes = [];
        $files = [];

        $files[] = $thrown->getFile() . ":" . $thrown->getLine();

        foreach ($thrown->getTrace() as $trace) {
            if (isset($trace['file']) && isset($trace['line'])) {
                $files[] = $trace['file'] . ":" . $trace['line'];
            }

            if (isset($trace['class']) && isset($trace['function'])) {
                $classes[] = $trace['class'] . "->" . $trace['function'];
            }
        }

        foreach ($files as $i => $file) {
            $traces[] = [
                'file' => $file,
                'class' => $classes[$i],
            ];
        }

        // ? some black magic :D , No actually in case of the template engine
        // ? the error itself will get parsed by the engine, this will cause
        // ? another exception inside the ExceptionHandler, so we need to
        // ? escape '{' and '%' so they won't be parsed
        return [
            'message' => str_replace(
                ['%', '{'],
                ['&#37;', '&#123;'],
                $thrown->getMessage()
            ),
            'code' => $thrown->getCode(),
            'trace' => $traces,
        ];
    }

    /**
     * Render error template.
     *
     * @param string $message
     * @param string $code
     * @return string
     */
    private function renderError($message, $code)
    {
        if (container('view')->templateExists("errors.$code")) {
            container('view')->render("errors.$code");
        }

        return $this->viewHandler->render('error', compact('message', 'code'));
    }

    /**
     * Render trace template.
     *
     * @param\Throwable $thrown
     * @return string
     */
    private function renderTrace($thrown)
    {
        return $this->viewHandler->render('trace', compact('thrown'));
    }

    /**
     * Create new response for the exception.
     *
     * @param \Throwable $thrown
     * @return SigmaPHP\Core\Http\Response
     */
    public static function handle($thrown)
    {
        $self = new self();
        $code = (http_response_code() == 200) ? 500 : http_response_code();
        $formattedException = $self->formatException($thrown);
        $content = null;

        if (!in_array($code, array_keys(self::ERRORS))) {
            $code = 500;
        }

        if (config('app.env') != 'development') {
            if (container('request')->isJson()) {
                $content = json_encode([$code => self::ERRORS[$code]]);
            } else {
                $content = $self->renderError(self::ERRORS[$code], $code);
            }
        } else {
            if ($code != 500) {
                $content = $self->renderError(self::ERRORS[$code], $code);
            }
            else if (container('request')->isJson()) {
                $content = json_encode($formattedException);
            } else {
                $content = $self->renderTrace($formattedException);
            }
        }

        return (container('request')->isJson()) ?
            container('response')->responseJSON($content, $code) :
            container('response')->responseData($content, 'text/html', $code);
    }
}
