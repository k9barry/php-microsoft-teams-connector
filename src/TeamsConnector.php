<?php

namespace Sebbmyr\Teams;

/**
 * Teams connector
 */
class TeamsConnector
{
    private $webhookUrl;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Sends card message as POST request
     *
     * @param  TeamsConnectorInterface $card
     * @throws Exception
     */
    public function send(TeamsConnectorInterface $card)
    {
        $json = json_encode($card->getMessage());

        $ch = curl_init($this->webhookUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);

        try {
            $result = curl_exec($ch);

            if (curl_error($ch)) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }
            if ($result !== "1") {
                throw new \Exception('Error response: ' . $result);
            }
        }
        catch (Exception $e) {
            MyEcho(__FILE__, __LINE__, '[TeamsConnector.php]', "Curl Error: curl_error($ch), curl_errno($ch)");
            MyEcho(__FILE__, __LINE__, '[TeamsConnector.php]', "Error response: $result");
            MyEcho(__FILE__, __LINE__, '[TeamsConnector.php]', "Exception: $e->getMessage()");
            echo "Exception: $e->getMessage()";
        }
    }
}
