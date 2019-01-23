<?php declare(strict_types=1);

namespace Circli\WebCore;

use Polus\Adr\Interfaces\ResolverInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionResolver implements ResolverInterface
{
	/** @var ContainerInterface */
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	protected function resolve($key)
	{
		if (\is_callable($key)) {
			return $key;
		}
		try {
			return $this->container->get($key);
		}
		catch (NotFoundExceptionInterface $e) {
			if (\is_string($key) && class_exists($key)) {
				return new $key();
			}
		}
		return null;
	}

	public function resolveDomain($domain): callable
	{
		return $this->resolve($domain);
	}

	public function resolveInput($input): callable
	{
		return $this->resolve($input);
	}

	public function resolveResponder($responder): callable
	{
		return $this->resolve($responder);
	}
}
