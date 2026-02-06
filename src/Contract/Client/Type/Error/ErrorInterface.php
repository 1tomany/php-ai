<?php

namespace OneToMany\AI\Contract\Client\Type\Error;

interface ErrorInterface
{
    public function getMessage(): string;

    public function getInlineMessage(): string;
}
