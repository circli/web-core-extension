<?php declare(strict_types=1);

namespace Circli\WebCore;

use Polus\Adr\Interfaces\Resolver;
use Polus\Adr\Interfaces\Responder;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionResolver implements Resolver
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(?string $class): callable
    {
        try {
            return $this->container->get($class);
        }
        catch (NotFoundExceptionInterface $e) {
            if (\is_string($class) && class_exists($class)) {
                return new $class();
            }
        }
        throw new class implements NotFoundExceptionInterface {
        };
    }

    public function resolveResponder(?string $responder): Responder
    {
        return $this->resolve($responder);
    }
}
