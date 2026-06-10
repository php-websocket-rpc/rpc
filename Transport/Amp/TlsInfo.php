<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport\Amp;

use Amp\Socket\TlsInfo as AmpSocketTlsInfo;
use PhpWebsocketRpc\Rpc\Transport\TlsInfoInterface;

final readonly class TlsInfo implements TlsInfoInterface
{
    public function __construct(
        private AmpSocketTlsInfo $info,
    ) {}

    public function getApplicationLayerProtocols(): array
    {
        return $this->info->getApplicationLayerProtocols();
    }

    public function getPeerName(): ?string
    {
        return $this->info->getPeerName();
    }

    public function isClientVerifyRequired(): bool
    {
        return $this->info->isClientVerifyRequired();
    }

    public function getVersion(): string
    {
        return $this->info->getVersion();
    }

    public function getCrypto(): array
    {
        return $this->info->getCrypto();
    }

    public function getCipherName(): ?string
    {
        return $this->info->getCipherName();
    }

    public function getCipherBits(): ?int
    {
        return $this->info->getCipherBits();
    }

    public function isClientVerifyOptional(): bool
    {
        return $this->info->isClientVerifyOptional();
    }

    public function isClientVerify(): bool
    {
        return $this->info->isClientVerify();
    }

    public function getHandshakeTime(): float
    {
        return $this->info->getHandshakeTime();
    }

    public function getApplicationLayerProtocolNegotiation(): ?string
    {
        return $this->info->getApplicationLayerProtocolNegotiation();
    }
}
