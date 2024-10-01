<?php

/**
 * APIClient
 * 
 * This class handles the interaction with the OpenAI and Anthropic APIs, including making requests
 * and implementing a backoff strategy for error handling.
 */
class APIClient {
    /** @var string The API key */
    private $apiKey;

    /** @var string The API type ('openai' or 'anthropic') */
    private $apiType;

    /** @var string The base URI for API requests */
    private $baseUri;

    /**
     * Constructor for APIClient
     * 
     * @param string $apiKey The API key
     * @param string $apiType The API type ('openai' or 'anthropic')
     */
    public function __construct(string $apiKey, string $apiType = 'openai') {
        $this->apiKey = $apiKey;
        $this->apiType = $apiType;
        $this->baseUri = $this->getBaseUri();
    }

    /**
     * Make a completion request to the API with a backoff strategy
     * 
     * @param array $messages The messages to send in the request
     * @param string $modelName The name of the model to use
     * @param int $maxAttempts The maximum number of retry attempts
     * @return array The API response
     * @throws \Exception If the maximum number of attempts is reached
     */
    public function completionWithBackoff(array $messages, string $modelName, int $maxAttempts = 6): array {
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            try {
                $response = $this->makeApiRequest($messages, $modelName);
                return json_decode($response, true);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    throw new \Exception("Max API request attempts reached: " . $e->getMessage());
                }
                $delay = min(pow(2, $attempt) + (random_int(0, 1000) / 1000), 20);
                sleep($delay);
            }
        }

        throw new \Exception("Unexpected error in API request");
    }

    /**
     * Make an API request using cURL
     * 
     * @param array $messages The messages to send in the request
     * @param string $modelName The name of the model to use
     * @return string The API response
     * @throws \Exception If the API request fails
     */
    private function makeApiRequest(array $messages, string $modelName): string {
        $curl = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];

        $data = $this->prepareRequestData($messages, $modelName);

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error: " . $err);
        }

        return $response;
    }

    /**
     * Prepare the request data based on the API type
     * 
     * @param array $messages The messages to send in the request
     * @param string $modelName The name of the model to use
     * @return array The prepared request data
     */
    private function prepareRequestData(array $messages, string $modelName): array {
        if ($this->apiType === 'openai') {
            return [
                'model' => $modelName,
                'messages' => $messages,
                'temperature' => 0.5,
            ];
        } elseif ($this->apiType === 'anthropic') {
            return [
                'model' => $modelName,
                'prompt' => $this->formatAnthropicPrompt($messages),
                'max_tokens_to_sample' => 300,
                'temperature' => 0.5,
            ];
        }

        throw new \Exception("Invalid API type: " . $this->apiType);
    }

    /**
     * Format messages for Anthropic API
     * 
     * @param array $messages The messages to format
     * @return string The formatted prompt
     */
    private function formatAnthropicPrompt(array $messages): string {
        $prompt = "";
        foreach ($messages as $message) {
            $role = $message['role'] === 'user' ? 'Human' : 'Assistant';
            $prompt .= "{$role}: {$message['content']}\n\n";
        }
        $prompt .= "Assistant:";
        return $prompt;
    }

    /**
     * Get the base URI for the selected API
     * 
     * @return string The base URI
     * @throws \Exception If an invalid API type is selected
     */
    private function getBaseUri(): string {
        if ($this->apiType === 'openai') {
            return 'https://api.openai.com/v1/chat/completions';
        } elseif ($this->apiType === 'anthropic') {
            return 'https://api.anthropic.com/v1/complete';
        }

        throw new \Exception("Invalid API type: " . $this->apiType);
    }
}