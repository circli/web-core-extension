<?php declare(strict_types=1);

namespace Circli\WebCore\Exception;

use Circli\WebCore\Common\Payload\AccessDeniedPayload;
use Circli\WebCore\Common\Payload\NotFoundPayload;
use PayloadInterop\DomainPayload;
use Polus\Adr\ExceptionDomainPayload;
use Polus\Adr\Interfaces\ExceptionHandler as BaseExceptionHandler;
use Psr\Http\Message\ResponseInterface;

final class ExceptionHandler implements BaseExceptionHandler
{
    /**
     * @return DomainPayload|ResponseInterface
     */
    public function handle(\Throwable $e)
    {
        if ($e instanceof NotFoundInterface) {
            return new NotFoundPayload($e);
        }
        if ($e instanceof AccessDenied) {
            return new AccessDeniedPayload();
        }
        if (!$e instanceof \DomainException && !$e instanceof \InvalidArgumentException) {
            throw $e;
        }
        return new ExceptionDomainPayload($e);
    }
}
