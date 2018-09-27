<?php

namespace Hindsight\Remote;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Transmits data to Hindsight.
 *
 * This class is not under test.
 */
class HindsightTransmitter
{
    /**
     * @var Client Guzzle HTTP client.
     */
    protected $http;

    /**
     * @var string
     */
    protected $apiToken;

    protected $ingestionUrl = 'https://logs.inhindsight.io';

    public function __construct()
    {
        $this->http = new Client();
    }

    public function setApiToken(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $ingestionUrl
     */
    public function setIngestionUrl(string $ingestionUrl): void
    {
        $this->ingestionUrl = $ingestionUrl;
    }

    /**
     * Transmit events to Hindsight. Ensure events have already been
     * formatted by HindsightEventFormatter before sending.
     *
     * @param array $events
     */
    public function sendForIngest(array $events)
    {
        // quit early if no API token
        if (!$this->apiToken) return;

        try {
            $this->http->request('POST', $this->ingestionUrl, [
                'json' => [
                    'messages' => $events,
                ],
                'headers' => ['Authorization' => "Bearer {$this->apiToken}"]
            ]);
        } catch (GuzzleException $e) {
            // silence guzzle exceptions
        }
    }

    /**
     * Check to see if we can connect to ingest.
     */
    public function diagnoseIngestConnection()
    {

    }


}
