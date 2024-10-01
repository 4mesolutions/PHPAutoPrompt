
<?php
use PHPUnit\Framework\TestCase;
use PHPAutoPrompt\APIClient;

class APIClientTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new APIClient();
    }


    /**
     * Tests the makeAPICall method of APIClient.
     *
     * Ensures that the API call is made correctly and returns the expected structure.
     */
    public function testAPICall(): void {
    {
        $response = $this->client->makeAPICall('test-endpoint', 'POST', ['key' => 'value']);
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals(200, $response['status']);
    }
}
?>
