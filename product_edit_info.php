<?php
include 'config.php';
session_start();
if (empty($_SESSION['username'])) {
    header('Location: index.php');
}
//session time
include 'session_auto.php';

if (isset($_POST['productid']) && isset($_POST['rowid'])) {
    $spreadsheetIdOfProduct = $_POST['productid'];
    $rowid = $_POST['rowid'];
    $formData = $_POST['formData'];
    $updated_filed = array();
    parse_str($formData, $updated_filed);
    $fieldList = (array_keys($updated_filed));
    array_pop($fieldList);
    
    include 'googleapifunction.php';

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
    $spreadsheetId = CONFIGSHEETID;
    $range = PRODUCTCOLUMN;
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();


   

    $a =$fieldList;
    $sheetCallomArray=array('0'=>'A','1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H','8'=>'I','9'=>'J','10'=>'K','11'=>'L','12'=>'M','13'=>'N','14'=>'O','15'=>'P','16'=>'Q','17'=>'R','18'=>'S','19'=>'T','20'=>'U','21'=>'V','22'=>'W','23'=>'X','24'=>'Y','25'=>'Z','26'=>'AA','27'=>'AB','28'=>'AC','29'=>'AD','30'=>'AE','31'=>'AF','32'=>'AG','33'=>'AH','34'=>'AI','35'=>'AJ');

    foreach ($a as $r) {
        $key[] = array_search($r, $sheetCallomArray);
    }

    $client = getClient();
    $service = new Google_Service_Sheets($client);

//get product title details

//    $range = 'Sheet1!A1:AZ';
//    $response = $service->spreadsheets_values->get($spreadsheetIdOfProduct, $range);
//    $values = $response->getValues();
//    foreach ($values as $rowK => $row) {
//        foreach ($row as $rowKey => $rowCal) {
//            if ($rowK == 0) {
//                if (in_array($rowKey, $key)) {
//                    // echo $rowKey;
//                    $data['product_title'][$rowK][] = $row[$rowKey];
//                }
//            }
//            # code...
//        }
//    }

   $result = array();
// get row detail
    $rowid = $rowid + 1;
    $range = 'Sheet1!A' . $rowid . ':AZ';
    $response = $service->spreadsheets_values->get($spreadsheetIdOfProduct, $range);
    $values = $response->getValues();
    foreach ($values as $rowK => $row) {
        foreach ($row as $rowKey => $rowCal) {
            if ($rowK == 0) {
                if (in_array($rowKey, $key)) {
                    // echo $rowKey;
                    $sheetKey = $sheetCallomArray[$rowKey];
                    $result[$sheetKey] = $row[$rowKey];
                }
            }
            # code...
        }
    }
    
    echo json_encode($result);
    exit;
}
?>