<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;

final readonly class ResponseType
{
    public function __construct(
        public string $id,
        public string $object,
        public Status $status,
    )
    {
    }
}
