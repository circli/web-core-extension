<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Actions;

use Circli\WebCore\Common\Input\RawInput;
use Circli\WebCore\Common\Responder\ApiResponder;

abstract class AbstractAction extends \Polus\Adr\AbstractAction
{
	protected $input = RawInput::class;
	protected $responder = ApiResponder::class;
}
