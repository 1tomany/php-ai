<?php

namespace OneToMany\AI\Bridge\Meta\Response\Response;

use function trim;

final class Response
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $status
     * @param list<ResponseOutputItem> $output
     */
    public function __construct(
        public readonly string $id,
        public readonly float $created_at,
        public readonly string $status,
        public readonly array $output = [],
        public readonly ?ResponseError $error = null,
        public readonly ?ResponseUsage $usage = null,
        public readonly ?float $completed_at = null,
    ) {
    }

    public bool $completed {
        get => 'completed' === $this->status;
    }

    /**
     * @var ?non-empty-string
     */
    public ?string $text {
        get => $this->compileText();
    }

    /**
     * @var ?non-empty-string
     */
    public ?string $refusal {
        get => $this->compileRefusal();
    }

    /**
     * @return ?non-empty-string
     */
    private function compileText(): ?string
    {
        $text = null;

        foreach ($this->output as $output) {
            if (null !== $output->text) {
                $text .= $output->text;
            }
        }

        $text = trim((string) $text);

        return '' !== $text ? $text : null;
    }

    /**
     * @return ?non-empty-string
     */
    private function compileRefusal(): ?string
    {
        $refusal = null;

        foreach ($this->output as $output) {
            if (null !== $output->refusal) {
                $refusal .= $output->refusal;
            }
        }

        $refusal = trim((string) $refusal);

        return '' !== $refusal ? $refusal : null;
    }
}
