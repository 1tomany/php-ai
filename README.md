# PHP AI and LLM Library

This library provides a single, unified, framework-independent library for integration with several popular AI providers and large language models.

## Installation

Install the library using Composer:

```console
composer require 1tomany/php-ai
```

### Symfony Bundle

A [Symfony bundle](https://github.com/1tomany/php-ai-bundle) is available if you wish to integrate this library into your Symfony applications with autowiring and configuration support.

## Supported platforms

- Gemini
- OpenAI

### Platform features

| Feature     | Gemini | OpenAI |
| ----------- | :----: | :----: |
| **Files**   |        |        |
| Upload      |   ✅   |   ✅   |
| Delete      |   ✅   |   ✅   |
| **Indexes** |        |        |
| Create      |   ✅   |   ✅   |
| Read        |   ✅   |   ✅   |
| Delete      |   ✅   |   ✅   |
| Attach file |   ✅   |   ✅   |
| Read file   |   ✅   |   ✅   |
| Delete file |   ✅   |   ✅   |
| **Prompts** |        |        |
| Compile     |   ✅   |   ✅   |
| Send        |   ✅   |   ✅   |

#### Indexes

An "Index" is a repository of files that are available through a semantic search (or RAG) API.

| Vendor    | Resource          |
| --------- | ----------------- |
| Anthropic | N/A               |
| OpenAI    | `VectorStore`     |
| Gemini    | `FileSearchStore` |

#### Prompts

A "Prompt" is a general term for data (text, files, schemas, etc) sent to a large language model for inference.

| Vendor    | Resource      |
| --------- | ------------- |
| Anthropic | `Message`     |
| OpenAI    | `Response`    |
| Gemini    | `Interaction` |

##### Index search results

OpenAI index searches expose typed tool results alongside the generated text:

```php
use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Tool;
use OneToMany\AI\Resource\Prompt\ToolResult\IndexSearchResult;

// $aiClient implements OneToMany\AI\Contract\AiClientInterface.
$response = $aiClient->prompts->send(Prompt::create(
    'openai:gpt-5.4',
    'What work is included in the project scope?',
    Tool::indexSearch(['vs_123']),
));

$text = $response->getText();

foreach ($response->getTools() as $tool) {
    if ($tool instanceof IndexSearchResult) {
        foreach ($tool->getResults() ?? [] as $match) {
            printf("%s (%.3f): %s\n", $match->getFileId(), $match->getScore(), $match->getText());
        }
    }
}

$citations = $response->getCitations();
```

`tools` contains one result per tool invocation, with its ID, completion flag, queries, and retrieved passages. Each `IndexSearchMatch` includes `fileId`, `filename`, `score`, and `text`. Multiple passages from the same file remain separate. A null `results` value means the provider did not supply results; an empty list means it returned no matches.

`citations` contains file references from the generated answer, not every retrieved match. These references remain separate from tool calls because a citation does not identify which call retrieved the file. The response no longer exposes `fileIds` or `getFileIds()`.

The OpenAI normalizer automatically requests `file_search_call.results` while preserving other `include` options, as described in the [OpenAI file search documentation](https://developers.openai.com/api/docs/guides/tools-file-search#include-search-results-in-the-response). Gemini tool-result mapping is not implemented yet.

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
