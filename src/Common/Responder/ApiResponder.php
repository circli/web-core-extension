<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Responder;

use Circli\WebCore\Common\Payload\LocationAwareInterface;
use Circli\WebCore\DomainStatusToHttpStatus;
use PayloadInterop\DomainPayload;
use Polus\Adr\Interfaces\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiResponder implements Responder
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DomainPayload $payload
    ): ResponseInterface {
        $responseCode = DomainStatusToHttpStatus::httpCode($payload);
        $response = $response->withStatus($responseCode);
        if ($payload instanceof LocationAwareInterface && in_array($responseCode, [201, 302, 303], true)) {
            $location = $payload->getLocation($request);
            if ($location) {
                return $response->withHeader('Location', $location);
            }
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        // Overwrite the body instead of making a copy and dealing with the stream.
        $response->getBody()->write(json_encode($payload->getResult(), JSON_THROW_ON_ERROR));

        return $response;
    }
}
