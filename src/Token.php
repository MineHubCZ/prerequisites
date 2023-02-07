<?php

namespace MineHub\Prerequisities;

class Token
{
    public function __construct(
        public readonly TokenKind $kind,
        public readonly string $content,
    ) {

    }
}
