<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Auth;

final readonly class Token
{
    // @mago-expect lint:excessive-parameter-list
    public function __construct(
        public string $id,
        public string $issuer,
        public string $subject,
        public string $audience,
        public int $expiresAt,
        public int $notBefore,
        public int $issuedAt,
    ) {}
}
