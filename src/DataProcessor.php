<?php

/**
 * DataProcessor
 * 
 * This class handles the preparation of messages for API requests and
 * processes the output from the API.
 */
class DataProcessor {
    /**
     * Prepare messages for the API request
     * 
     * @param array $allDataContent Previously generated content
     * @param int $numberOfExamples Number of examples to generate
     * @param string $prompt The prompt to use
     * @param array $inputFunction The input function details
     * @param bool $singleShot Whether to use single-shot generation
     * @param bool $diversify Whether to diversify the generated examples
     * @return array The prepared messages
     */
    public function prepareMessages(
        array $allDataContent,
        int $numberOfExamples,
        string $prompt,
        array $inputFunction,
        bool $singleShot,
        bool $diversify
    ): array {
        $content = $prompt . "\n\nHere is the function details:\n\n" . $this->dictToDescription($inputFunction);

        if ($singleShot) {
            $content .= "\n\nPlease generate {$numberOfExamples} examples and put them in a list [].\n\n";
        } elseif ($diversify && !empty($allDataContent)) {
            $lastExamples = array_slice($allDataContent, -10);
            $content .= "\n\nGiven the last " . count($lastExamples) . " examples, please generate diverse results to ensure comprehensive evaluation. REMEMBER DON'T GENERATE THE SAME SAMPLE AS BELOW!\n\n" . json_encode($lastExamples);
        }

        return [['role' => 'user', 'content' => $content]];
    }

    /**
     * Process the output from the API
     * 
     * @param array $output The API output
     * @param array $parameters The expected parameters
     * @param string $expectedParamName The name of the expected parameter
     * @param array $fixedInput Fixed input to be added to all examples
     * @return Example|null The processed example, or null if processing failed
     */
    public function processOutput(array $output, array $parameters, string $expectedParamName, array $fixedInput): ?Example {
        $generatedExample = $this->extractDictFromGPTOutput($output['choices'][0]['message']['content']);

        if (!$generatedExample) {
            return null;
        }

        $generatedExample = array_intersect_key($generatedExample, $parameters);

        if (count($generatedExample) !== count($parameters)) {
            return null;
        }

        $expectedValue = $generatedExample[$expectedParamName] ?? null;

        if ($expectedValue) {
            unset($generatedExample[$expectedParamName]);
        }

        if ($fixedInput) {
            $generatedExample = array_merge($generatedExample, $fixedInput);
        }

        return new Example(
            $this->generateExampleId($output['choices'][0]['message']['content']),
            $generatedExample,
            $expectedValue
        );
    }

    /**
     * Convert a dictionary to a description string
     * 
     * @param array $data The dictionary to convert
     * @param int $indent The indentation level
     * @return string The description string
     */
    private function dictToDescription(array $data, int $indent = 0): string {
        $narrative = [];
        foreach ($data as $key => $value) {
            $prefix = str_repeat('  ', $indent);
            if ($key === 'parameters') {
                $paramStr = implode(', ', array_map(function($k, $v) {
                    return "'{$k}' of type '{$v}'";
                }, array_keys($value), $value));
                $narrative[] = "{$prefix}- It takes parameters: {$paramStr}.";
            } elseif (is_array($value) && !isset($value[0])) {
                $subNarrative = $this->dictToDescription($value, $indent + 1);
                $narrative[] = "{$prefix}- '{$key}' has the following properties:\n{$subNarrative}";
            } elseif (is_array($value)) {
                $items = implode(', ', $value);
                $narrative[] = "{$prefix}- '{$key}' can have values: {$items}.";
            } else {
                $narrative[] = "{$prefix}- '{$key}' is described as '{$value}'.";
            }
        }
        return implode("\n", $narrative);
    }

    /**
     * Extract a dictionary from the GPT output
     * 
     * @param string $output The GPT output
     * @return array|null The extracted dictionary, or null if extraction failed
     */
    private function extractDictFromGPTOutput(string $output): ?array {
        preg_match('/\{[^}]+\}/', $output, $matches);
        if (empty($matches)) {
            return null;
        }

        try {
            return json_decode($matches[0], true);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Generate an example ID
     * 
     * @param string $content The content to generate the ID from
     * @return string The generated ID
     */
    private function generateExampleId(string $content): string {
        return md5($content);
    }
}