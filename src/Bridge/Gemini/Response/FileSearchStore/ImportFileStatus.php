<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use function max;
use function trim;

final readonly class ImportFileStatus
{
    /**
     * @var non-negative-int
     */
    public int $code;

    /**
     * @var ?non-empty-string
     */
    public ?string $message;

    public function __construct(
        ?int $code = null,
        ?string $message = null,
    ) {
        $this->code = max(0, (int) $code);

        if (null !== $message) {
            $message = trim($message);
        }

        $this->message = '' !== $message ? $message : null;
    }
}
