<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\WebCore\Events\PreRegisterRoute;
use Polus\Adr\ActionDispatcherFactory;
use Polus\Adr\Interfaces\ResolverInterface;
use Polus\Adr\Interfaces\ResponseHandlerInterface;
use Polus\MiddlewareDispatcher\FactoryInterface as MiddlewareFactoryInterface;
use Polus\Router\RouterCollectionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Adr extends \Polus\Adr\Adr
{
    private const ALLOWED_METHODS = ['get', 'put', 'post', 'delete', 'patch', 'head', 'attach'];

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ResolverInterface $actionResolver,
        RouterCollectionInterface $routerContainer,
        ResponseHandlerInterface $responseHandler,
        MiddlewareFactoryInterface $middlewareFactory,
        EventDispatcherInterface $eventDispatcher,
        ActionDispatcherFactory $actionDispatcherFactory
    ) {
        parent::__construct($responseFactory, $actionResolver, $routerContainer, $responseHandler, $middlewareFactory, $actionDispatcherFactory);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, self::ALLOWED_METHODS, true)) {
            $this->eventDispatcher->dispatch(new PreRegisterRoute($name, ...$arguments));
            parent::__call($name, $arguments);
        }
    }
}