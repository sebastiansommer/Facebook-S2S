<?php
use League\Csv\Writer;

require_once 'vendor/autoload.php';

$sheetId = 'SHEET_ID'; // Replace with your Google Sheet ID
$password = 'PASSWORD'; // Replace with a password

if (!isset($_GET['pwd']) || $_GET['pwd'] !== $password) {
    exit('Invalid password');
}
try {
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');
    $client->setAuthConfig(__DIR__ . '/credentials.json');
} catch (Exception $e) {
    exit('Error initializing Google Client: ' . $e->getMessage());
}

try {
    $service = new Google_Service_Sheets($client);
    $response = $service->spreadsheets_values->get($sheetId, 'sheet');
    $values = $response->getValues();
} catch (Exception $e) {
    exit('Error fetching data from Google Sheets: ' . $e->getMessage());
}

$expectedHeaders = ['account', 'pixel', 'token', 'currency', 'value'];

if ($values[0] !== $expectedHeaders) {
    exit('Invalid CSV file');
}

try {
    $csv = Writer::createFromPath('pixel.csv', 'w+');
    $csv->insertAll($values);
} catch (Exception $e) {
    exit('Error writing to CSV file: ' . $e->getMessage());
}

echo 'Successfully downloaded ' . count($values) . ' pixels';