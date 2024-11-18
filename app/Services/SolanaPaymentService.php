<?php
namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class SolanaPaymentService 
{
    protected $client;
    protected $endpoint;

    public function __construct()
    {
        $this->client = new Client();
        $this->endpoint = config('services.solana.network_endpoint');
    }

    public function sendPayment($toAddress, $amount)
    {
        try {
            $response = $this->client->post($this->endpoint . '/sendTransaction', [
                'json' => [
                    'to' => $toAddress,
                    'amount' => $amount,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            throw new Exception("Giao dịch thất bại: " . $e->getMessage());
        }
    }
}