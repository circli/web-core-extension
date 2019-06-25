<?php declare(strict_types=1);

namespace Circli\WebCore\Events\Listeners;

use Circli\Extensions\Template\Events\PreRenderEvent;
use Circli\WebCore\Session\Factory;
use Circli\WebCore\Session\FlashSession;

class PreRenderAssignFlashMessage
{
    /** @var Factory */
    private $sessionFactory;

    public function __construct(Factory $sessionFactory)
    {
        $this->sessionFactory = $sessionFactory;
    }

    public function __invoke(PreRenderEvent $event)
    {
        /** @var FlashSession $session */
        $session = $this->sessionFactory->fromRequest($event->getRequest(), FlashSession::class);
        $messages = $session->getMessages();

        $event->getTemplate()->assign(FlashSession::FLASH_TEMPLATE_KEY, $messages);
    }
}