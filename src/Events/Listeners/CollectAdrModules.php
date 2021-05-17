<?php declare(strict_types=1);

namespace Circli\WebCore\Events\Listeners;

use Circli\Contracts\InitAdrApplication;
use Circli\Contracts\InitHttpApplication;
use Circli\Core\Events\InitModule;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @implements \IteratorAggregate<InitHttpApplication|InitAdrApplication>
 */
final class CollectAdrModules implements ListenerProviderInterface, \Countable, \IteratorAggregate
{
    /** @var InitHttpApplication[] */
    private array $modules = [];

    public function __invoke(InitModule $event): void
    {
        $module = $event->getModule();
        if ($module instanceof InitHttpApplication) {
            $this->modules[get_class($module)] = $module;
        }
    }

    /**
     * @return InitHttpApplication[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        if ($event instanceof InitModule) {
            yield $this;
        }
    }

    /**
     * @return InitHttpApplication[]|\ArrayIterator<int, InitHttpApplication>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->modules);
    }

    public function count(): int
    {
        return count($this->modules);
    }
}
