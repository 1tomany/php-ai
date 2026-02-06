<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response;

use OneToMany\AI\Client\OpenAi\Type\Response\Usage\InputTokensDetails;
use OneToMany\AI\Client\OpenAi\Type\Response\Usage\OutputTokensDetails;

final readonly class Usage
{
    public function __construct(
        public int $input_tokens = 0,
        public InputTokensDetails $input_tokens_details = new InputTokensDetails(),
        public int $output_tokens = 0,
        public OutputTokensDetails $output_tokens_details = new OutputTokensDetails(),
        public int $total_tokens = 0,
    ) {
    }
}
