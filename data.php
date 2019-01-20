<?php
include 'config.php';
session_start();
if (empty($_SESSION['username'])) {
    header('Location: index.php');
}
//session time
include 'session_auto.php';

include 'googleapifunction.php';

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
$spreadsheetId = CONFIGSHEETID;
$range = PRODUCTCOLUMN;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$dropdownProduct = $response->getValues();

$fieldvifyrange = FIELDVERIFIERNAME;
$response = $service->spreadsheets_values->get($spreadsheetId, $fieldvifyrange);
$fieldvifyrangedropdown = $response->getValues();

$caseStatus = CASESTATUS;
$response = $service->spreadsheets_values->get($spreadsheetId, $caseStatus);
$caseStatusDropdown = $response->getValues();

if (isset($_GET['productid'])) {
    $spreadsheetIdOfProduct = $_GET['productid'];
    $additionalCal = array();
    if (empty($dropdownProduct)) {
        print "No data found.\n";
    } else {
        // print "Name, Major:\n";
        // print_r($dropdownProduct);
        $data = array();
        foreach ($dropdownProduct as $row) {
            // Print columns A and E, which correspond to indices 0 and 4.

            if (trim($spreadsheetIdOfProduct) == trim($row[1])) {

                $totalCol = $row[4];
                $dipCal = $row[5];
                
                $statusCal = $row[13];
                $commentCal = $row[14];
                $verifedCal = $row[15];
                $additionalCal = $row[16];
                break;
            }
        }
    }

    $additionalCalArr = explode(",", $additionalCal);
    $a = explode(",", $dipCal);
    $sheetCallomArray = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z', '26' => 'AA', '27' => 'AB', '28' => 'AC', '29' => 'AD', '30' => 'AE', '31' => 'AF', '32' => 'AG', '33' => 'AH', '34' => 'AI', '35' => 'AJ');

    foreach ($a as $r) {
        $key[] = array_search($r, $sheetCallomArray);
    }
    
    $statusKey = array_search($statusCal, $sheetCallomArray);
    
    // $keyA=explode(",",$key);
    // product wish data
    // exit;
    $client = getClient();
    $service = new Google_Service_Sheets($client);

    // Prints the names and majors of students in a sample spreadsheet:
    // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
    // $spreadsheetId = '1aloacehiHK0ookERRf4UbrGR0YkUodRAx6sHO1YJrQw';
    $range = 'Sheet1!A2:AZ';
    $response = $service->spreadsheets_values->get($spreadsheetIdOfProduct, $range);
    $values = $response->getValues();
    $i =1;
    foreach ($values as $rowK => $row) {
        
        
        if(isset($row[$statusKey]) && $row[$statusKey] == 'Closed' && isset($_GET['status']) && $_GET['status']=='yes'){
//            foreach ($row as $rowKey => $rowCal) {
//
//                if (in_array($rowKey, $key)) {
//                    // echo $rowKey;
//                    $data['product_details'][$rowK][] = $row[$rowKey];
//                }
//                # code...
//            }
        }else{
//            foreach ($row as $rowKey => $rowCal) {
//
//                if (in_array($rowKey, $key)) {
//                    // echo $rowKey;
//                    $data['product_details'][$rowK][] = $row[$rowKey];
//                }
//                 
//                # code...
//            }
            foreach ($key as $rowKey => $rowCal) {

                if (isset($row[$rowCal])) {
                    // echo $rowKey;
                    $data['product_details'][$rowK][] = $row[$rowCal];
                }else{
                    $data['product_details'][$rowK][] = '';
                }
                 
                # code...
            }
            $count3 = count($key) ;
            $data['product_details'][$rowK][$count3] = '<a class="btn" href="javascript:;" data-toggle="modal" data-target="#myModal" id="' . $i . '" onClick="popupwindow(this.id)"  >View</a> <a class="btn" href="javascript:;" data-toggle="modal" data-target="#editModal" id="' . $i . '" onclick="editwindow(this.id)"  >Edit</a> ';
           
        }
       $i++;
    }
    $result = array_unique($data);
    $result1['data'] = $result['product_details'];
    echo json_encode($result1);
}
 
?>
