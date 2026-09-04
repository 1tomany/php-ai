<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use OneToMany\AI\Resource\Prompt\FileCitation as FileCitationResource;

final readonly class FileCitation implements AnnotationInterface
{
    /**
     * @param 'file_citation' $type
     * @param non-empty-string $file_id
     * @param non-empty-string $filename
     * @param non-negative-int $index
     */
    public function __construct(
        public string $type,
        public string $file_id,
        public string $filename,
        public int $index = 0,
    ) {
    }

    public function toResource(): FileCitationResource
    {
        return new FileCitationResource($this->file_id, $this->filename);
    }
}
