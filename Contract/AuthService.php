<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Contract;

use PhpWebsocketRpc\Rpc\Auth\Token;

interface AuthService
{
    public function authenticate(#[\SensitiveParameter] string $token): Token;

    public function logout(): void;

    public function refresh(#[\SensitiveParameter] string $token): Token;
}
