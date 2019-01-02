<?php
session_start();
if(empty($_SESSION['username']))
{
header('Location: index.php');
}
//session time
include 'session_auto.php';

if(isset($_POST["updateData"]))
{
    $postData = $_POST["updateData"];

    $spreadsheetIdOfProduct=$postData['sheetid'];
    $productrowid=$postData['productrowid'];
    $status=$postData['status'];
    $comment=$postData['comment'];
    $verified=$postData['verified'];

    include 'googleapifunction.php';
    
    $id=1;
    $productrowid=$productrowid+$id;
    $range = 'Sheet1!AD'.$productrowid.':AF';

    // $options = array('valueInputOption' => 'RAW');
    // $values[] =array($status, $comment, $verified);
    // $body   = new Google_Service_Sheets_ValueRange(['values' => $values]);
    // $result = $service->spreadsheets_values->update($spreadsheetIdOfProduct, $range, $body, $options);
// print($result->updatedRange. PHP_EOL);

    $dd=array_values(array($status, $comment, $verified));
     // $dd=implode(",", $dd);
    //  print_r($dd);
    // exit;

    $values = [
        $dd
        
        // Additional rows ...
    ];
    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => "USER_ENTERED"
    ];
    $result = $service->spreadsheets_values->update($spreadsheetIdOfProduct, $range,
    $body, $params);
    // printf("%d cells updated.", $result->getUpdatedCells());
    if($result->getUpdatedCells()>0)
    {
        echo "1";
    }
}



?>