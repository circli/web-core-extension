<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\WebCore\Events\PreRegisterRoute;
use Polus\Adr\Interfaces\ActionDispatcher;
use Polus\Adr\Interfaces\Resolver;
use Polus\Adr\Interfaces\ResponseHandler;
use Polus\MiddlewareDispatcher\FactoryInterface as MiddlewareFactory;
use Polus\Router\RouterCollection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Adr extends \Polus\Adr\Adr
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Resolver $actionResolver,
        RouterCollection $routerContainer,
        ResponseHandler $responseHandler,
        MiddlewareFactory $middlewareFactory,
        EventDispatcherInterface $eventDispatcher,
        ?ActionDispatcher $actionDispatcher = null
    ) {
        parent::__construct($responseFactory, $actionResolver, $routerContainer, $responseHandler, $middlewareFactory, $actionDispatcher);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param mixed ...$args
     */
    protected function routerContainerProxy(string $method, ...$args): void
    {
        $this->eventDispatcher->dispatch(new PreRegisterRoute($method, ...$args));
        parent::routerContainerProxy($method, ...$args);
    }
}
