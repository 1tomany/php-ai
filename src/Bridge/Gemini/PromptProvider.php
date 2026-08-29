<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Common\Trait\PromptTrait;
use OneToMany\AI\Bridge\Gemini\Response\Interaction\Interaction;
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
        $url = $this->url($this->apiVersion, 'interactions');

        try {
            $response = $this->transport->postRequest($url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
                'json' => $query->getPayload(),
            ]);

            $record = $this->transport->decode($response, Interaction::class);
        } finally {
            unset($query);
        }

        return new Response($record->id, $record->completed, $record->text, null, $record->error?->message);
    }
}
