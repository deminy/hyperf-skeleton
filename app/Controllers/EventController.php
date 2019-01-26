<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://hyperf.org
 * @document https://wiki.hyperf.org
 * @contact  group@hyperf.org
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controllers;

use App\Events\BeforeResponse;
use App\Events\RequestMessage;
use Hyperf\Di\Annotation\Debug;
use Hyperf\Event\EventDispatcher;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @AutoController
 */
class EventController
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcher $eventManager)
    {
        $this->dispatcher = $eventManager;
    }

    /**
     * @Debug
     */
    public function index()
    {
        /** @var ServerRequestInterface $request */
        $request = Context::get(ServerRequestInterface::class);
        $response = 'Hello EventManager';
        $event = (new BeforeResponse())->setData($response);
        $this->dispatcher->dispatch($event);
        $message = new RequestMessage($request->getUri()->getPath());
        $this->dispatcher->dispatch($message);
        return $event->getData();
    }
}
