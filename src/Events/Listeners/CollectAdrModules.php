<?php declare(strict_types=1);

namespace Circli\WebCore\Events\Listeners;

use Circli\Contracts\InitAdrApplication;
use Circli\Core\Events\InitModule;
use Psr\EventDispatcher\ListenerProviderInterface;

final class CollectAdrModules implements ListenerProviderInterface, \Countable, \IteratorAggregate
{
	private $modules = [];

	public function __invoke(InitModule $event)
	{
		$module = $event->getModule();
		if ($module instanceof InitAdrApplication) {
			$this->modules[get_class($module)] = $module;
		}
	}

	/**
	 * @return InitAdrApplication[];
	 */
	public function getModules(): array
	{
		return $this->modules;
	}

	public function getListenersForEvent(object $event): iterable
	{
		if ($event instanceof InitModule) {
			yield $this;
		}
	}

	/**
	 * @return InitAdrApplication[]|\ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->modules);
	}

	public function count()
	{
		return count($this->modules);
	}
}
