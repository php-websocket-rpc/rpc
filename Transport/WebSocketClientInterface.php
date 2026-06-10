<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport;

interface WebSocketClientInterface extends \IteratorAggregate
{
    public function sendBinary(string $data): void;

    public function receive(): MessageInterface;

    public function close(): void;

    public function isClosed(): bool;

    public function getId(): int;

    public function getIterator(): \Traversable;

    public function getTlsInfo(): ?TlsInfoInterface;
}
