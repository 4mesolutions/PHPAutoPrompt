<?php

require_once 'OpenAIPromptDataGenerator.php';
require_once 'Config.php';
require_once 'APIClient.php';
require_once 'DataProcessor.php';
require_once 'Utilities.php';
require_once 'ProgressBar.php';
require_once 'Example.php';

/**
 * Example usage of the FinancialPromptGenerator
 */
class FinancialPromptGeneratorExample {
    /**
     * Generate various financial analysis prompts
     */
    public function run() {
        $this->generateStockAnalysisPrompt();
        $this->generateFinancialAdvicePrompt();
        $this->generateEconomicPredictionPrompt();
    }

    /**
     * Generate a prompt for stock market analysis
     */
    private function generateStockAnalysisPrompt() {
        $config = new Config([
            'api_type' => 'openai',
            'model_name' => 'gpt-3.5-turbo',
            'financial_tool' => 'analysis',
            'data_source' => 'stock_market',
            'time_frame' => 'monthly',
            'output_format' => 'graph',
            'specific_metrics' => ['PE_ratio', 'revenue_growth', 'debt_to_equity'],
        ]);

        $generator = new OpenAIPromptDataGenerator($config);
        $prompt = $generator->generateExamples();

        echo "Stock Market Analysis Prompt:\n$prompt\n\n";
    }

    /**
     * Generate a prompt for financial advice
     */
    private function generateFinancialAdvicePrompt() {
        $config = new Config([
            'api_type' => 'anthropic',
            'model_name' => 'claude-2',
            'financial_tool' => 'advice',
            'data_source' => 'personal_finance',
            'time_frame' => 'yearly',
            'output_format' => 'text',
            'specific_metrics' => ['savings_rate', 'debt_repayment', 'investment_allocation'],
        ]);

        $generator = new OpenAIPromptDataGenerator($config);
        $prompt = $generator->generateExamples();

        echo "Financial Advice Prompt:\n$prompt\n\n";
    }

    /**
     * Generate a prompt for economic predictions
     */
    private function generateEconomicPredictionPrompt() {
        $config = new Config([
            'api_type' => 'openai',
            'model_name' => 'gpt-4',
            'financial_tool' => 'prediction',
            'data_source' => 'economic_indicators',
            'time_frame' => 'quarterly',
            'output_format' => 'spreadsheet',
            'specific_metrics' => ['GDP_growth', 'inflation_rate', 'unemployment_rate'],
        ]);

        $generator = new OpenAIPromptDataGenerator($config);
        $prompt = $generator->generateExamples();

        echo "Economic Prediction Prompt:\n$prompt\n\n";
    }
}

// Run the example
$example = new FinancialPromptGeneratorExample();
$example->run();