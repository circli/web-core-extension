<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Circli\Core\PayloadStatusToHttpStatus;
use Psr\Http\Message\ResponseInterface;

trait JsonResponderTrait
{
    public function jsonEncode(
        ResponseInterface $response,
        PayloadInterface $payload
    ): ResponseInterface {
        $httpStatus = PayloadStatusToHttpStatus::httpCode($payload);
        $response = $response->withStatus($httpStatus);

        $status = PayloadStatusToHttpStatus::jsendStatus($payload);
        $json = [
            'status' => $status,
        ];

        if (in_array($status, ['error', 'fail'])) {
            $messages = $payload->getMessages();
            if ($status === 'error') {
                if (is_array($messages)) {
                    $json['message'] = $messages[0];
                } else {
                    $json['message'] = $messages;
                }
            }
            $data = (array) $payload->getOutput();
            $data['messages'] = $payload->getMessages();
            $data['input'] = $payload->getInput();

            $json['code'] = $payload->getStatus();
            $json['data'] = $data;
        } else {
            $json['data'] = (array) $payload->getOutput();
            if ($payload->getMessages()) {
                $json['data']['messages'] = $payload->getMessages();
            }
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        // Overwrite the body instead of making a copy and dealing with the stream.
        $response->getBody()->write(json_encode($json));

        return $response;
    }
}
