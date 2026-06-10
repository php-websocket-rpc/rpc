<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Transport;

interface MessageInterface
{
    public function buffer(): string;
}
