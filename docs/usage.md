
# PHPAutoPrompt - Usage

The PHPAutoPrompt class can be used for generating financial analysis prompts and creating PHP code for assistants using OpenAI API.

### Financial Analysis Prompt

```php
$apiKey = 'your-api-key-here';
$client = new OpenAIPromptDataGenerator($apiKey);
$financialData = [
    'revenue' => 150000,
    'expenses' => 120000,
    'assets' => 70000,
    'liabilities' => 40000
];
$options = [
    'graphs' => true,
    'spreadsheet_download' => true,
    'financial_advice' => true,
    'financial_predictions' => true,
];
$prompt = $client->generateFinancialAnalysisPrompt($financialData, $options);
echo $prompt;
```

### Assistant Code Generation

```php
$client = new OpenAIPromptDataGenerator($apiKey);
$tools = ['data_analysis', 'graphing_tool'];
$assistantPHPCode = $client->generateAssistantPHPCode($tools);
file_put_contents('generated_assistant.php', $assistantPHPCode);
```

