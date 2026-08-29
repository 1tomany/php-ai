<?php

require_once __DIR__.'/vendor/autoload.php';

use OneToMany\AI\Resource\Query\InputFile;
use OneToMany\AI\Resource\Query\Prompt;

$prompt = Prompt::create('openai:gpt-5.6-sol', 'text1', 'text2')->addText('here is anothe rprompt')->withInstructions('and some instructions here')->addFile(new InputFile('file_123', 'image/png'));
print_r($prompt);
