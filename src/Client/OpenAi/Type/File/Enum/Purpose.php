<?php

namespace OneToMany\AI\Client\OpenAi\Type\File\Enum;

enum Purpose: string
{
    case Assistants = 'assistants';
    case Batch = 'batch';
    case Evals = 'evals';
    case FineTune = 'fine-tune';
    case UserData = 'user_data';
    case Vision = 'vision';

    public static function create(?string $purpose): self
    {
        return self::tryFrom(\strtolower(\trim($purpose ?? ''))) ?: self::UserData;
    }

    /**
     * @return 'Assistants'|'Batch'|'Evals'|'FineTune'|'UserData'|'Vision'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'assistants'|'batch'|'evals'|'fine-tune'|'user_data'|'vision'
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
