<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport\Amp;

use Amp\Websocket\WebsocketClient;
use PhpWebsocketRpc\Rpc\Transport\MessageInterface;
use PhpWebsocketRpc\Rpc\Transport\TlsInfoInterface;
use PhpWebsocketRpc\Rpc\Transport\WebSocketClientInterface;

final readonly class Client implements WebSocketClientInterface
{
    public function __construct(
        private WebsocketClient $client,
    ) {}

    public function sendBinary(string $data): void
    {
        $this->client->sendBinary($data);
    }

    public function receive(): MessageInterface
    {
        $message = $this->client->receive();

        if ($message === null) {
            throw new \RuntimeException('Connection closed');
        }

        return new Message($message);
    }

    public function close(): void
    {
        $this->client->close();
    }

    public function isClosed(): bool
    {
        return $this->client->isClosed();
    }

    public function getId(): int
    {
        return $this->client->getId();
    }

    /** @return \Traversable<MessageInterface> */
    public function getIterator(): \Traversable
    {
        while (true) {
            $message = $this->client->receive();

            if ($message === null) {
                break;
            }

            yield new Message($message);
        }
    }

    public function getTlsInfo(): ?TlsInfoInterface
    {
        $tls = $this->client->getTlsInfo();

        return $tls !== null ? new TlsInfo($tls) : null;
    }
}
