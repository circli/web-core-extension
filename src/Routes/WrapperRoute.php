<?php declare(strict_types=1);

namespace Circli\WebCore\Routes;

use Polus\Router\Route;

class WrapperRoute implements Route
{
    /**
     * @param string[] $allows
     * @param mixed[] $attributes
     */
    public function __construct(
        private Route $wrappedRoute,
        private mixed $handler = null,
        private ?int $status = null,
        private ?array $allows = null,
        private ?string $method = null,
        private ?array $attributes = null,
    ) {}

    public function getStatus(): int
    {
        return $this->status ?? $this->wrappedRoute->getStatus();
    }

    /**
     * @return string[]
     */
    public function getAllows(): array
    {
        return $this->allows ?? $this->wrappedRoute->getAllows();
    }

    public function getHandler(): mixed
    {
        return $this->handler ?? $this->wrappedRoute->getHandler();
    }

    public function getMethod(): string
    {
        return $this->method ?? $this->wrappedRoute->getMethod();
    }

    /**
     * @return mixed[]
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? $this->wrappedRoute->getAttributes();
    }
}
