<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Responder;

use Circli\WebCore\DomainStatusToHttpStatus;
use PayloadInterop\DomainPayload;
use Psr\Http\Message\ResponseInterface;

trait JsonResponderTrait
{
    public function jsonEncode(
        ResponseInterface $response,
        DomainPayload $payload
    ): ResponseInterface {
        $httpStatus = DomainStatusToHttpStatus::httpCode($payload);
        $response = $response->withStatus($httpStatus);
        $response = $response->withHeader('Content-Type', 'application/json');
        // Overwrite the body instead of making a copy and dealing with the stream.
        $response->getBody()->write(json_encode($payload->getResult(), JSON_THROW_ON_ERROR));

        return $response;
    }
}
