<?php declare(strict_types=1);

namespace Circli\WebCore;

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestMarshal
{
    /**
     * @param scalar $value
     */
    private function toBool(float|bool|int|string $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        return 'on' === strtolower((string)$value);
    }

    /**
     * @param array<string, scalar> $serverParams
     */
    public function marshal(array $serverParams = []): ServerRequestInterface
    {
        if (!isset($serverParams['HTTP_HOST'])) {
            throw new \BadMethodCallException('Current sapi can\'t be setup automatically: ' . PHP_SAPI);
        }
        $https = false;
        if (array_key_exists('HTTPS', $serverParams)) {
            $https = $this->toBool($serverParams['HTTPS']);
        }
        elseif (array_key_exists('https', $serverParams)) {
            $https = $this->toBool($serverParams['https']);
        }

        $uriObj = new Uri();

        $uriObj = $uriObj
            ->withScheme($https ? 'https' : 'http')
            ->withHost((string)$serverParams['HTTP_HOST'])
            ->withPath((string)strtok((string)$serverParams['REQUEST_URI'], '?'))
            ->withQuery(ltrim((string)($serverParams['QUERY_STRING'] ?? '')));

        $headers = false;
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }
        if (!$headers) {
            $headers = $this->parseHeaders($serverParams);
        }

        $request = new ServerRequest(
            (string)($serverParams['REQUEST_METHOD'] ?? 'GET'),
            $uriObj,
            $headers,
            fopen('php://input', 'r'), // @phpstan-ignore-line
            '1.1',
            $serverParams,
        );

        return $request
            ->withParsedBody($_POST)
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE);
    }

    /**
     * @param array<string, scalar> $serverParams
     * @return array<string, scalar>
     */
    private function parseHeaders(array $serverParams): array
    {
        $headers = [];
        foreach ($serverParams as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            if ($value === '') {
                continue;
            }

            // Apache prefixes environment variables with REDIRECT_
            // if they are added by rewrite rules
            if (str_starts_with($key, 'REDIRECT_')) {
                $key = substr($key, 9);

                // We will not overwrite existing variables with the
                // prefixed versions, though
                if (array_key_exists($key, $serverParams)) {
                    continue;
                }
            }

            if (str_starts_with($key, 'HTTP_')) {
                $name = strtr(strtolower(substr($key, 5)), '_', '-');
                $headers[$name] = $value;
            }
        }
        return $headers;
    }
}
