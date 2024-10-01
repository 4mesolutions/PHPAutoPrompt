
<?php
use PHPUnit\Framework\TestCase;
use PHPAutoPrompt\OpenAIPromptDataGenerator;

class OpenAIPromptDataGeneratorTest extends TestCase
{
    private $generator;

    protected function setUp(): void
    {
        $apiKey = 'test-api-key';
        $this->generator = new OpenAIPromptDataGenerator($apiKey);
    }


    /**
     * Tests the generateFinancialAnalysisPrompt function for correct output.
     *
     * Ensures that the financial data provided is correctly transformed into a prompt.
     */
    public function testGenerateFinancialAnalysisPrompt(): void {
    {
        $financialData = [
            'revenue' => 100000,
            'expenses' => 80000,
            'assets' => 50000,
            'liabilities' => 30000
        ];

        $options = [
            'graphs' => true,
            'spreadsheet_download' => true,
            'financial_advice' => true,
            'financial_predictions' => true
        ];

        $prompt = $this->generator->generateFinancialAnalysisPrompt($financialData, $options);

        $this->assertStringContainsString("revenue", $prompt);
        $this->assertStringContainsString("graphs", $prompt);
    }


    /**
     * Tests the generateAssistantPHPCode function for valid PHP code generation.
     *
     * Checks that the PHP code generated for the assistant includes all necessary components.
     */
    public function testGenerateAssistantPHPCode(): void {
    {
        $tools = ['graphing_tool', 'financial_analyzer'];
        $phpCode = $this->generator->generateAssistantPHPCode($tools);

        $this->assertStringContainsString("Assistant", $phpCode);
        $this->assertStringContainsString("graphing_tool", $phpCode);
    }
}
?>
