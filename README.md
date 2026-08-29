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
| Run         |   ✅   |   ✅   |

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

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
