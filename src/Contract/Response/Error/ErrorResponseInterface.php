<?php

namespace OneToMany\AI\Contract\Response\Error;

interface ErrorResponseInterface
{
    /**
     * @return non-empty-string
     */
    public function getVendor(): string;

    /**
     * @return ?non-negative-int
     */
    public function getCode(): ?int;

    /**
     * @return non-empty-string
     */
    public function getMessage(): string;
}
