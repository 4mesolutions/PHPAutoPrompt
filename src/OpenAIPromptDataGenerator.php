<?php

/**
 * OpenAIPromptDataGenerator
 * 
 * This class is responsible for generating data examples using the OpenAI API
 * based on provided configurations and prompts.
 */
class OpenAIPromptDataGenerator {
    /** @var Config The configuration object */
    private $config;
    
    /** @var APIClient The API client for making requests to OpenAI */
    private $apiClient;
    
    /** @var DataProcessor The data processor for handling input and output */
    private $dataProcessor;

    /**
     * Constructor for OpenAIPromptDataGenerator
     * 
     * @param Config $config The configuration object
     */
    public function __construct(Config $config) {
        $this->config = $config;
        $this->apiClient = new APIClient($config->getApiKey());
        $this->dataProcessor = new DataProcessor();
    }

    /**
     * Generate examples based on the configuration
     * 
     * @return array An array of Example objects
     */
    public function generateExamples(): array {
        $allData = [];
        $chunk = [];
        $allDataContent = [];

        // Create a new progress bar
        $progressBar = new ProgressBar('Generating Examples', $this->config->getNumberOfExamples());

        // Generate examples until we reach the desired number
        while (count($allData) < $this->config->getNumberOfExamples()) {
            // Prepare messages for the API request
            $messages = $this->dataProcessor->prepareMessages(
                $allDataContent, 
                $this->config->getNumberOfExamples(),
                $this->config->getPrompt(),
                $this->config->getInputFunction(),
                $this->config->isSingleShot(),
                $this->config->isDiversify()
            );
            
            try {
                // Make API request with backoff strategy
                $output = $this->apiClient->completionWithBackoff($messages, $this->config->getModelName());
                
                // Process the API output
                $newExample = $this->dataProcessor->processOutput(
                    $output, 
                    $this->config->getInputFunction()['parameters'],
                    $this->config->getExpectedParamName(),
                    $this->config->getFixedInput() ?? []
                );

                if ($newExample) {
                    $allData[] = $newExample;
                    $chunk[] = $newExample;
                    
                    // Update progress bar
                    $progressBar->advance();

                    // Extract content for diversity check
                    $content = $newExample->getContent();
                    if ($content) {
                        $allDataContent[] = $content;
                    }
                }
            } catch (Exception $e) {
                error_log("Error generating example: " . $e->getMessage());
            }
        }

        $progressBar->finish();

        // Save generated data if output paths are provided
        if ($this->config->getOutputPath()) {
            Utilities::saveToFile($allData, $this->config->getOutputPath());
        }

        if ($this->config->getOutputCsvPath()) {
            Utilities::saveToCsv($allData, $this->config->getOutputCsvPath());
        }

        return $chunk;
    }
}