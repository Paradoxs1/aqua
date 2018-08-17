<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 02.03.2018
 * Time: 15:16
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Psr\Log\LoggerInterface;
use App\Service\MessageManager;

class AddNiceHeaderEventSubscriber implements EventSubscriberInterface
{
    private $logger;

    private $messageManager;

    private $showDiscouragingMessage;

    public function __construct(LoggerInterface $logger, MessageManager $messageManager, $showDiscouragingMessage)
    {
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->showDiscouragingMessage = $showDiscouragingMessage;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->logger->info('Adding a nice header!');

        $message = $this->showDiscouragingMessage
            ? $this->messageManager->getDiscouragingMessage()
            : $this->messageManager->getEncouragingMessage();

        $event->getResponse()
            ->headers->set('X-NICE-MESSAGE', 'That was a great request!');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

}