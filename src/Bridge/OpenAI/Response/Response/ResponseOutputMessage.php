<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use OneToMany\AI\Resource\Prompt\FileCitation as FileCitationResource;

use function implode;
use function trim;

final readonly class ResponseOutputMessage implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'message' $type
     * @param 'in_progress'|'completed'|'incomplete' $status
     * @param list<ContentInterface> $content
     * @param 'commentary'|'final_answer'|null $phase
     * @param 'assistant' $role
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
        public array $content,
        public ?string $phase,
        public string $role,
    ) {
    }

    /**
     * @return ?non-empty-string
     */
    public function getText(): ?string
    {
        $textBits = [];

        foreach ($this->content as $content) {
            if ($content instanceof ResponseOutputText) {
                $textBits[] = $content->text;
            }
        }

        $text = trim(implode('', $textBits));

        return '' !== $text ? $text : null;
    }

    /**
     * @return ?non-empty-string
     */
    public function getRefusal(): ?string
    {
        $refusalBits = [];

        foreach ($this->content as $content) {
            if ($content instanceof ResponseOutputRefusal) {
                $refusalBits[] = $content->refusal;
            }
        }

        $refusal = trim(implode('', $refusalBits));

        return '' !== $refusal ? $refusal : null;
    }

    /**
     * @return list<FileCitationResource>
     */
    public function getCitations(): array
    {
        $citations = [];

        foreach ($this->content as $content) {
            if ($content instanceof ResponseOutputText) {
                foreach ($content->annotations as $annotation) {
                    if ($annotation instanceof FileCitation) {
                        $citations[] = $annotation->toResource();
                    }
                }
            }
        }

        return $citations;
    }
}
