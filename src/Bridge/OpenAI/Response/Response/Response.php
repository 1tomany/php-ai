<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use OneToMany\AI\Bridge\OpenAI\Response\Response\Enum\ResponseStatus;
use OneToMany\AI\Resource\Prompt\FileCitation as FileCitationResource;
use OneToMany\AI\Resource\Prompt\Response as ResponseResource;
use OneToMany\AI\Resource\Prompt\ToolResult;

use function implode;

final readonly class Response
{
    /**
     * @param non-empty-string $id
     * @param 'response' $object
     * @param positive-int $created_at
     * @param ?positive-int $completed_at
     * @param ?non-negative-int $max_output_tokens
     * @param ?non-negative-int $max_tool_calls
     * @param non-empty-string $model
     * @param list<OutputInterface> $output
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public ResponseStatus $status,
        public ?int $completed_at,
        public ?ResponseError $error,
        public ?IncompleteDetails $incomplete_details,
        public ?int $max_output_tokens,
        public ?int $max_tool_calls,
        public string $model,
        public array $output = [],
    ) {
    }

    /**
     * @return ?non-empty-string
     */
    public function getText(): ?string
    {
        $textBits = [];

        foreach ($this->output as $output) {
            if ($output instanceof ResponseOutputMessage) {
                if ($text = $output->getText()) {
                    $textBits[] = $text;
                }
            }
        }

        return [] !== $textBits ? implode("\n\n", $textBits) : null;
    }

    /**
     * @return ?non-empty-string
     */
    public function getRefusal(): ?string
    {
        $refusalBits = [];

        foreach ($this->output as $output) {
            if ($output instanceof ResponseOutputMessage) {
                if ($refusal = $output->getRefusal()) {
                    $refusalBits[] = $refusal;
                }
            }
        }

        return [] !== $refusalBits ? implode("\n\n", $refusalBits) : null;
    }

    /**
     * @return list<ToolResult>
     */
    public function getTools(): array
    {
        $tools = [];

        foreach ($this->output as $output) {
            if ($output instanceof FileSearchCall) {
                $tools[] = $output->toResource();
            }
        }

        return $tools;
    }

    /**
     * @return list<FileCitationResource>
     */
    public function getCitations(): array
    {
        $citations = [];

        foreach ($this->output as $output) {
            if ($output instanceof ResponseOutputMessage) {
                $citations = [...$citations, ...$output->getCitations()];
            }
        }

        return $citations;
    }

    public function toResource(): ResponseResource
    {
        return new ResponseResource(
            $this->id,
            $this->status->isCompleted(),
            $this->getText(),
            $this->getRefusal(),
            $this->error?->message,
            tools: $this->getTools(),
            citations: $this->getCitations(),
        );
    }
}
