<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use League\Csv\Reader;

require_once 'vendor/autoload.php';

$password = 'PASSWORD'; // Replace with a password

if (!isset($_GET['pwd']) || $_GET['pwd'] !== $password) {
    exit('Invalid password');
}

$csv = Reader::createFromPath('pixel.csv');
$csv->setHeaderOffset(0);

foreach ($csv->getRecords() as $record) {
    $pixelConfig[$record['account']] = [
        'pixel' => $record['pixel'],
        'token' => $record['token'],
        'currency' => $record['currency'],
        'value' => $record['value'],
    ];
}

if (isset($_GET['account']) && isset($_GET['clickid']) && isset($_GET['ip']) && isset($_GET['event']) && isset($pixelConfig[$_GET['account']])) {
    $ts = time();

    $pixelConfig = $pixelConfig[$_GET['account']];

    $pixelIds = explode(',', $pixelConfig['pixel']);

    foreach ($pixelIds as $pixelId) {
        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v16.0/',
        ]);

        $data = [
            'event_name' => $_GET['event'],
            'event_time' => $ts,
            'action_source' => 'website',
            'user_data' => [
                'client_ip_address' => $_GET['ip'],
                'fbc' => 'fb.1.' . $ts . '.' . $_GET['clickid'],
            ],
            'custom_data' => [
                'currency' => $pixelConfig['currency'],
                'value' => $pixelConfig['value'],
            ]
        ];

        file_put_contents('pixel.log',
            date('Y-m-d H:i:s') . ' - Pixel: ' . json_encode($pixelConfig) . ' - ' . json_encode($data) . PHP_EOL,
            FILE_APPEND);

        try {
            $response = $client->post($pixelId . '/events', [
                RequestOptions::MULTIPART => [
                    [
                        'name' => 'data',
                        'contents' => json_encode([$data])
                    ],
                    [
                        'name' => 'access_token',
                        'contents' => $pixelConfig['token']
                    ]
                ]
            ]);

            echo 'OK';
        } catch (GuzzleException $exception) {
            echo 'Error: ' . $exception->getResponse()->getBody();
        }
    }
} else {
    echo 'Invalid parameters';
}