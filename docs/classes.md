
# PHPAutoPrompt - Classes

### OpenAIPromptDataGenerator

This class is responsible for generating prompts and PHP code for financial analysis and assistants. It integrates with OpenAI API and has several customizable features.

#### Methods

- **`generateFinancialAnalysisPrompt(array $financialData, array $options): string`**  
  Generates a detailed financial analysis prompt based on provided data and options.

- **`generateAssistantPHPCode(array $tools): string`**  
  Generates PHP code to create an assistant using OpenAI API.

### APIClient

Handles API interactions, including sending requests and receiving responses.

### Utilities

Contains various helper methods used across the project.

### ProgressBar

Renders a progress bar for tracking long-running operations in the console.

