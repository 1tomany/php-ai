<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use function array_unique;
use function array_values;
use function trim;

final class ResponseOutputItem
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $type
     * @param ?non-empty-string $status
     * @param list<ResponseOutputMessage> $content
     */
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly ?string $status,
        public readonly array $content = [],
    ) {
    }

    /**
     * @var ?non-empty-string
     */
    public ?string $text {
        get => $this->compileOutputText();
    }

    /**
     * @var ?non-empty-string
     */
    public ?string $refusal {
        get => $this->compileRefusal();
    }

    /**
     * @phpstan-assert-if-true 'completed' $this->status
     */
    public function isStatusCompleted(): bool
    {
        return 'completed' === $this->status;
    }

    /**
     * @phpstan-assert-if-true 'message' $this->type
     */
    public function isTypeMessage(): bool
    {
        return 'message' === $this->type;
    }

    /**
     * @return list<non-empty-string>
     */
    public function getFileIds(): array
    {
        $fileIds = [];

        if ($this->isTypeMessage()) {
            foreach ($this->content as $content) {
                array_push($fileIds, ...$content->getFileIds());
            }
        }

        return array_values(array_unique($fileIds));
    }

    /**
     * @return ?non-empty-string
     */
    private function compileOutputText(): ?string
    {
        $outputText = null;

        if ($this->isTypeMessage()) {
            foreach ($this->content as $content) {
                if ($content->isTypeOutputText()) {
                    $outputText .= $content->text;
                }
            }

            $outputText = trim((string) $outputText);
        }

        return '' !== $outputText ? $outputText : null;
    }

    /**
     * @return ?non-empty-string
     */
    private function compileRefusal(): ?string
    {
        $refusal = null;

        if ($this->isTypeMessage()) {
            foreach ($this->content as $content) {
                if ($content->isTypeRefusal()) {
                    $refusal .= $content->refusal;
                }
            }

            $refusal = trim((string) $refusal);
        }

        return '' !== $refusal ? $refusal : null;
    }
}
