<?php
namespace App\Exception;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This exception thrown when Api response was invalid format
 */
class InvalidApiResponseException extends HttpException
{
    /**
     * @param string $message
     * @param Throwable|null $previous
     * @param int $code
     * @param array<string,mixed> $headers
     */
    public function __construct(
        //@todo translate
        string|null $message = 'Invalid API response',
        ?Throwable $previous = null,
        int $code = 0,
        array $headers = [],
    )
    {
        parent::__construct(500, $message, $previous, $headers, $code);
    }
}