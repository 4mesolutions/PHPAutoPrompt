<?php

/**
 * Utilities
 * 
 * This class provides utility functions for the OpenAIPromptDataGenerator.
 */
class Utilities {
    /**
     * Save data to a file
     * 
     * @param array $data The data to save
     * @param string $path The path to save the file to
     */
    public static function saveToFile(array $data, string $path): void {
        file_put_contents($path, serialize($data));
        echo "Data successfully generated and saved to {$path}\n";
    }

    /**
     * Save data to a CSV file
     * 
     * @param array $data The data to save
     * @param string $path The path to save the CSV file to
     */
    public static function saveToCsv(array $data, string $path): void {
        $fp = fopen($path, 'w');
        
        // Write header
        if (!empty($data)) {
            $firstExample = reset($data);
            $header = array_merge(['example_id'], array_keys($firstExample->getContent()));
            fputcsv($fp, $header);
        }

        // Write data
        foreach ($data as $example) {
            $csvRow = array_merge([$example->getExampleId()], $example->getContent());
            fputcsv($fp, $csvRow);
        }

        fclose($fp);
        echo "Data successfully generated and saved to {$path}\n";
    }

    /**
     * Input data to CSV row
     * 
     * @param Example $data The input data
     * @return array The CSV row
     */
    public static function inputDataToCsvRow(Example $data): array {
        $row = [
            'example_id' => $data->getExampleId(),
        ];
        foreach ($data->getContent() as $key => $value) {
            $row[$key] = $value;
        }
        return $row;
    }
}