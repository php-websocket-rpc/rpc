<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport;

/**
 * @template T
 */
interface TlsInfoInterface
{
    /**
     * @return T[]
     */
    public function getPeerCertificate(): array;

    public function getCipher(): string;

    public function getProtocol(): string;
}
