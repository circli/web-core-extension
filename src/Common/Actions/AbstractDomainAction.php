<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Actions;

use Circli\WebCore\Common\Responder\ApiResponder;

abstract class AbstractDomainAction extends \Polus\Adr\Actions\AbstractDomainAction
{
    protected ?string $responder = ApiResponder::class;
}
