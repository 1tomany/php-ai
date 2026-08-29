<?php

namespace OneToMany\AI\Bridge\OpenAI;

use OneToMany\AI\Bridge\Common\Trait\PromptTrait;
use OneToMany\AI\Bridge\OpenAI\Response\Response\Response as ResponsePayload;
use OneToMany\AI\Contract\Bridge\PromptProviderInterface;
use OneToMany\AI\Resource\Query\Query;
use OneToMany\AI\Resource\Query\Response;

final readonly class PromptProvider extends AbstractProvider implements PromptProviderInterface
{
    use PromptTrait;

    /**
     * @see OneToMany\AI\Contract\Bridge\PromptProviderInterface
     */
    #[\Override]
    public function send(Query $query): Response
    {
        $url = $this->url('responses');

        try {
            $response = $this->transport->postRequest($url, [
                'auth_bearer' => $this->apiKey,
                'json' => [
                    ...$query->getPayload(),
                ],
            ]);

            $record = $this->transport->decode($response, ResponsePayload::class);
        } finally {
            unset($query);
        }

        return new Response($record->id, $record->completed, $record->text, $record->refusal, $record->error?->message);
    }
}
