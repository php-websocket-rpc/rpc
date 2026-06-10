<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport;

use PhpWebsocketRpc\Rpc\Payload\Payload;
use PhpWebsocketRpc\Rpc\Serialization\Serializer;

final readonly class FramedConnection
{
    private Serializer $serializer;

    public function __construct(
        private WebSocketClientInterface $websocket,
        ?Serializer $serializer = null,
    ) {
        $this->serializer = $serializer ?? new Serializer();
    }

    public function send(Payload $payload): void
    {
        $this->websocket->sendBinary($this->serializer->encode($payload));
    }

    public function receive(): Payload
    {
        return $this->serializer->decode($this->websocket->receive()->buffer());
    }

    /**
     * @return \Traversable<Payload>
     */
    public function receiveStream(): \Traversable
    {
        foreach ($this->websocket as $message) {
            yield $this->serializer->decode($message->buffer());
        }
    }

    public function close(): void
    {
        $this->websocket->close();
    }

    public function isClosed(): bool
    {
        return $this->websocket->isClosed();
    }

    public function getWebsocket(): WebSocketClientInterface
    {
        return $this->websocket;
    }
}
