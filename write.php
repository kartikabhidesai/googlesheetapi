<?php
require __DIR__ . '/vendor/autoload.php';

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(('4/vABf4wMNvGe5iDPwmr7eW_MaR9hhrkOY0UnRzc-d8WCcRQzLNdkCtSI'));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

$client = getClient();

$service = new Google_Service_Sheets($client);

$spreadsheetId = '13iKoscqvkpB97HHZ5quU72qx-s4MoIOT4mfXEU8pXWk';
$values = [
    [
        'kartik','desai'
    ],
    // Additional rows ...
];
$body = new Google_Service_Sheets_ValueRange([
//    "range"=> "Sheet1!A1:D5",
    'values' => $values
]);
$params = [
    'valueInputOption' => 'USER_ENTERED'
];
$result = $service->spreadsheets_values->update($spreadsheetId, "Sheet1!A1:B1",
$body, $params);
printf("%d cells updated.", $result->getUpdatedCells());