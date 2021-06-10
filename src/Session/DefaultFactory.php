<?php declare(strict_types=1);

namespace Circli\WebCore\Session;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class DefaultFactory implements Factory
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function fromRequest(ServerRequestInterface $request, $sessionCls)
    {
        if ($request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE)) {
            if ($sessionCls === FlashSession::class) {
                return new StoragelessFlashSession($request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE));
            }
        }
        if ($sessionCls === FlashSession::class) {
            return new PhpFlashSession();
        }

        if ($sessionCls === ObjectSession::class) {
            return new PhpObjectSession();
        }
        return null;
    }
}