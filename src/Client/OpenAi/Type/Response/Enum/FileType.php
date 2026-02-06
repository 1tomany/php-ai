<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Enum;

enum FileType: string
{
    case InputFile = 'input_file';
    case InputImage = 'input_image';

    public static function create(string $format): self
    {
        return 0 === \stripos($format, 'image/') ? self::InputImage : self::InputFile;
    }

    /**
     * @return 'InputFile'|'InputImage'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'input_file'|'input_image'
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
