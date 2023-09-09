<?php

namespace Cdnnow\Core\Handlers;

use Bitrix\Main\EventManager;

final class HandlerRegister
{
    static private array $handlers = [
        [
            'module' => 'main',
            'event'  => 'OnEndBufferContent',
            'class'  => 'Cdnnow\Core\Main',
            'method' => 'OnEndBufferContent',
        ],
    ];

    public static function init(): void
    {
        $eventManager = EventManager::getInstance();

        foreach (self::$handlers as $handler) {
            $eventManager->addEventHandler(
                $handler['module'],
                $handler['event'],
                [$handler['class'], $handler['method']]
            );
        }
    }
}
