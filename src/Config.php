<?php

/**
 * Config
 * 
 * This class handles the configuration settings for the OpenAIPromptDataGenerator.
 */
class Config {
    /** @var string The API key for OpenAI */
    private $apiKey;

    /** @var string The prompt to be used for generation */
    private $prompt;

    /** @var array The input function details */
    private $inputFunction;

    /** @var int The number of examples to generate */
    private $numberOfExamples;

    /** @var string The name of the OpenAI model to use */
    private $modelName;

    /** @var string|null The path to save the output file */
    private $outputPath;

    /** @var string|null The path to save the CSV output file */
    private $outputCsvPath;

    /** @var bool Whether to use single-shot generation */
    private $singleShot;

    /** @var bool Whether to diversify the generated examples */
    private $diversify;

    /** @var string The name of the parameter expected in the output */
    private $expectedParamName;

    /** @var array|null Fixed input to be added to all generated examples */
    private $fixedInput;

    /**
     * Constructor for Config
     * 
     * @param array $config An associative array of configuration settings
     */
    public function __construct(array $config) {
        $this->apiKey = $config['api_key'] ?? getenv('OPENAI_API_KEY');
        $this->prompt = $config['prompt'] ?? '';
        $this->inputFunction = $config['input_function'] ?? [];
        $this->numberOfExamples = $config['number_of_examples'] ?? 5;
        $this->modelName = $config['model_name'] ?? 'gpt-3.5-turbo';
        $this->outputPath = $config['output_path'] ?? null;
        $this->outputCsvPath = $config['output_csv_path'] ?? null;
        $this->singleShot = $config['single_shot'] ?? false;
        $this->diversify = $config['diversify'] ?? false;
        $this->expectedParamName = $config['expected_param_name'] ?? '';
        $this->fixedInput = $config['fixed_input'] ?? null;
    }

    // Getter methods for each property
    public function getApiKey(): string {
        return $this->apiKey;
    }

    public function getPrompt(): string {
        return $this->prompt;
    }

    public function getInputFunction(): array {
        return $this->inputFunction;
    }

    public function getNumberOfExamples(): int {
        return $this->numberOfExamples;
    }

    public function getModelName(): string {
        return $this->modelName;
    }

    public function getOutputPath(): ?string {
        return $this->outputPath;
    }

    public function getOutputCsvPath(): ?string {
        return $this->outputCsvPath;
    }

    public function isSingleShot(): bool {
        return $this->singleShot;
    }

    public function isDiversify(): bool {
        return $this->diversify;
    }

    public function getExpectedParamName(): string {
        return $this->expectedParamName;
    }

    public function getFixedInput(): ?array {
        return $this->fixedInput;
    }
}