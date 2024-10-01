# OpenAIPromptDataGenerator

The OpenAIPromptDataGenerator is a PHP library designed to generate data examples using AI language models (OpenAI GPT or Anthropic Claude) based on provided configurations and prompts. This tool is useful for creating datasets, testing prompts, and generating diverse examples for various natural language processing tasks.

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)
5. [Configuration](#configuration)
6. [Classes Overview](#classes-overview)
7. [Example](#example)

## Features

- Generate examples using OpenAI GPT or Anthropic Claude models
- Configurable number of examples, model, and output formats
- Support for single-shot and multi-shot generation
- Option to diversify generated examples
- Automatic retrying with exponential backoff for API requests
- Progress bar to track generation process
- Output as serialized PHP array or CSV file

## Requirements

- PHP 7.4 or higher
- Composer (for managing dependencies)
- OpenAI API key or Anthropic API key

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/OpenAIPromptDataGenerator.git
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Set up your API key:
   - For OpenAI: Set the `OPENAI_API_KEY` environment variable
   - For Anthropic: Set the `ANTHROPIC_API_KEY` environment variable

## Usage

1. Create a configuration array with your desired settings.
2. Instantiate the `Config` class with your configuration.
3. Create an instance of `OpenAIPromptDataGenerator` using the config.
4. Call the `generateExamples()` method to generate your data.

## Configuration

The `Config` class accepts an associative array with the following options:

- `api_key`: Your API key (optional if set as an environment variable)
- `prompt`: The prompt to use for generation
- `input_function`: An array describing the input function and its parameters
- `number_of_examples`: Number of examples to generate (default: 5)
- `model_name`: The name of the AI model to use (default: 'gpt-3.5-turbo')
- `output_path`: Path to save the serialized output (optional)
- `output_csv_path`: Path to save the CSV output (optional)
- `single_shot`: Whether to use single-shot generation (default: false)
- `diversify`: Whether to diversify the generated examples (default: false)
- `expected_param_name`: The name of the parameter expected in the output
- `fixed_input`: Fixed input to be added to all generated examples (optional)
- `api_type`: The type of API to use ('openai' or 'anthropic', default: 'openai')

## Classes Overview

1. `OpenAIPromptDataGenerator`: The main class that orchestrates the example generation process.
2. `Config`: Handles configuration settings for the generator.
3. `APIClient`: Manages API interactions with OpenAI or Anthropic, including error handling and retries.
4. `DataProcessor`: Prepares messages for API requests and processes the output.
5. `Utilities`: Provides utility functions for saving data to files.
6. `Example`: Represents a single generated example.
7. `ProgressBar`: Displays a progress bar during the generation process.

## Example

```php
<?php

require_once 'vendor/autoload.php';

$config = new Config([
    'prompt' => 'Generate a random person's name and age.',
    'input_function' => [
        'name' => 'generate_person',
        'parameters' => [
            'min_age' => 'int',
            'max_age' => 'int',
        ],
    ],
    'number_of_examples' => 10,
    'model_name' => 'gpt-3.5-turbo',
    'output_csv_path' => 'output.csv',
    'expected_param_name' => 'person',
    'api_type' => 'openai',
]);

$generator = new OpenAIPromptDataGenerator($config);
$examples = $generator->generateExamples();

print_r($examples);
```

This example will generate 10 random person names and ages using the OpenAI GPT-3.5-turbo model and save the results to `output.csv`.
