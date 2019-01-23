<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Circli\Core\JSend;
use Circli\Core\PayloadStatusToHttpStatus;
use Polus\Adr\Interfaces\ResponderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiResponder implements ResponderInterface
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response,
		PayloadInterface $payload
	): ResponseInterface {
		$jsend = JSend::fromPayload($payload);

		$response->withStatus(PayloadStatusToHttpStatus::httpCode($payload));
		$response = $response->withHeader('Content-Type', 'application/json');
		// Overwrite the body instead of making a copy and dealing with the stream.
		$response->getBody()->write(json_encode($jsend));

		return $response;
	}
}
