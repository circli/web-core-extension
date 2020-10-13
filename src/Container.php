<?php declare(strict_types=1);

namespace Circli\WebCore;

use DI\ContainerBuilder;

abstract class Container extends \Circli\Core\Container
{
    protected function initDefinitions(ContainerBuilder $builder, string $defaultDefinitionPath): void
    {
        $builder->addDefinitions($defaultDefinitionPath . '/adr.php');
    }
}
