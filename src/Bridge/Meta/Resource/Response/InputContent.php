<?php

namespace OneToMany\AI\Bridge\Meta\Resource\Response;

use function str_starts_with;

/**
 * @template TType of 'input_file'|'input_image'|'input_text'
 */
abstract readonly class InputContent implements \JsonSerializable
{
    /**
     * @param TType $type
     */
    public function __construct(
        public string $type,
    ) {
    }

    /**
     * @param non-empty-string $fileId
     * @param non-empty-lowercase-string $mimeType
     *
     * @return InputFileContent|InputImageContent
     */
    public static function asFile(
        string $fileId,
        string $mimeType,
    ): self {
        if (str_starts_with($mimeType, 'image')) {
            return new InputImageContent($fileId);
        }

        return new InputFileContent($fileId);
    }

    public static function asText(string $text): InputTextContent
    {
        return new InputTextContent($text);
    }
}
