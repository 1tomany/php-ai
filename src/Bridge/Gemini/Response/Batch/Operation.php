<?php

namespace OneToMany\AI\Bridge\Gemini\Response\Batch;

readonly class Operation
{
    /**
     * The maximum number of times to poll
     * the operation to check it's status.
     *
     * @var positive-int
     */
    public const int POLL_COUNT = 50;

    /**
     * The number of seconds to sleep between each poll request.
     *
     * @var int<1,60>
     */
    public const int POLL_SLEEP_SECONDS = 5;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public bool $done = false,
    ) {
    }
}
