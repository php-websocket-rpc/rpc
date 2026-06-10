<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport\Amp;

use PhpWebsocketRpc\Rpc\Transport\MessageInterface;

final readonly class Message implements MessageInterface
{
    public function __construct(
        private \Amp\Websocket\WebsocketMessage $message,
    ) {}

    public function buffer(): string
    {
        return $this->message->buffer();
    }
}
