<?php declare(strict_types=1);

namespace Circli\WebCore\Middleware;

use Psr\Http\Server\MiddlewareInterface;

/**
 * @implements \IteratorAggregate<string|MiddlewareInterface>
 */
final class Container implements \IteratorAggregate
{
    /** @var array<int, array<int, string|MiddlewareInterface>> */
    private array $data = [];

    public const DEFAULT_PRIORITY = 500;
    private const MAX_PRE_PRIORITY = 1000;
    private const MIN_POST_PRIORITY = 1000;

    /**
     * @param string[]|MiddlewareInterface[] $middlewares
     */
    public function __construct(iterable $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            $this->addPreRouter($middleware);
        }
    }

    /**
     * @param string|MiddlewareInterface $middleware
     */
    public function insert($middleware, int $priority): void
    {
        if (!isset($this->data[$priority])) {
            $this->data[$priority] = [];
        }
        $this->data[$priority][] = $middleware;
    }

    /**
     * @param string|MiddlewareInterface $middleware
     * @param int $priority
     */
    public function addPreRouter($middleware, int $priority = self::DEFAULT_PRIORITY): void
    {
        if ($priority > self::MAX_PRE_PRIORITY) {
            $priority = self::MAX_PRE_PRIORITY - 1;
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

    public function getIterator()
    {
        $data = $this->data;
        ksort($data);
        $tmp = [];
        foreach ($data as $middlewares) {
            $tmp[] = $middlewares;
        }

        return new \ArrayIterator(array_merge(...$tmp));
    }
}
