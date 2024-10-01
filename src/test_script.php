<?php

require_once 'OpenAIPromptDataGenerator.php';
require_once 'Config.php';
require_once 'APIClient.php';
require_once 'DataProcessor.php';
require_once 'Utilities.php';
require_once 'ProgressBar.php';
require_once 'Example.php';

// Create a configuration
$config = new Config([
    'api_key' => 'your_api_key_here',
    'prompt' => 'Generate a headline for a tech startup',
    'input_function' => [
        'name' => 'headline_generation',
        'description' => 'Generate a catchy headline for a tech startup',
        'parameters' => [
            'startup_name' => 'string',
            'industry' => 'string',
        ],
    ],
    'number_of_examples' => 5,
    'model_name' => 'gpt-3.5-turbo',
    'output_path' => 'output.txt',
    'output_csv_path' => 'output.csv',
    'single_shot' => true,
    'diversify' => false,
    'expected_param_name' => 'headline',
]);

// Create the generator
$generator = new OpenAIPromptDataGenerator($config);

// Generate examples
$examples = $generator->generateExamples();

// Print the generated examples
foreach ($examples as $example) {
    echo "Example ID: " . $example->getExampleId() . "\n";
    echo "Content: " . json_encode($example->getContent()) . "\n";
    echo "Expected Result: " . $example->getExpectedResult() . "\n\n";
}