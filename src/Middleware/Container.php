<?php

namespace Circli\WebCore\Middleware;

use Psr\Http\Server\MiddlewareInterface;

class Container extends \SplPriorityQueue
{
    private const MAX_PRE_PRIORITY = 1000;
    private const MIN_POST_PRIORITY = 1000;

    public function __construct(iterable $middlewares = [])
    {
        if (count($middlewares)) {
            foreach ($middlewares as $middleware) {
                $this->addPreRouter($middleware);
            }
        }
    }

    /**
     * @param string|MiddlewareInterface $middleware
     * @param int $priority
     */
    public function addPreRouter($middleware, int $priority = 500): void
    {
        if ($priority > self::MAX_PRE_PRIORITY) {
            $priority = self::MAX_PRE_PRIORITY;
        }
        $this->insert($middleware, $priority);
    }

    /**
     * @param string|MiddlewareInterface $middleware
     * @param int $priority
     */
    public function addPostRouter($middleware, int $priority = 2000): void
    {
        if ($priority < self::MIN_POST_PRIORITY) {
            $priority = self::MIN_POST_PRIORITY + 1;
        }
        $this->insert($middleware, $priority);
    }
}