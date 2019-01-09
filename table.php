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
    $range = 'Sheet1!A1:AZ';
    $response = $service->spreadsheets_values->get($spreadsheetIdOfProduct, $range);
    $values = $response->getValues();
    
    foreach ($values as $rowK => $row) {
        if($row[$statusKey] == 'Closed' && isset($_GET['status']) && $_GET['status']=='yes'){
//            foreach ($row as $rowKey => $rowCal) {
//
//                if (in_array($rowKey, $key)) {
//                    // echo $rowKey;
//                    $data['product_details'][$rowK][] = $row[$rowKey];
//                }
//                # code...
//            }
        }else{
            foreach ($row as $rowKey => $rowCal) {

                if (in_array($rowKey, $key)) {
                    // echo $rowKey;
                    $data['product_details'][$rowK][] = $row[$rowKey];
                }
                # code...
            }
        }
    }
    $result = array_unique($data);
}
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <title><?php echo $_SESSION['company_name']; ?></title>
        <link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
        <link rel="stylesheet" type="text/css" href="https://datatables.net/media/css/site-examples.css?_=19472395a2969da78c8a4c707e72123a">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style type="text/css" class="init">

        </style>


        <style>
            #example_wrapper,.mrg-top-30{
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <div class="fw-container">

            <div class="content">
                <div class="row">
                    <div class="col-md-2 mrg-top-30" >
                        <a  href="logout.php"><button type="button" class="btn">Log Out</button></a>
                    </div>
                    <div class="col-md-7 mrg-top-30" style="text-align: center;">
                        <!-- <button type="button" class="btn">Sign In</button> -->
                        
                        <h4 >Compney Name :- <b><?php echo $_SESSION['company_name']; ?></b></h4>
                    
                        <!-- <button type="button" class="btn">Upload</button> -->
                        <!-- <button type="button" class="btn">Save</button>  -->

                    </div>
                    <div class="col-md-3 mrg-top-30">
                            <?php if (isset($_GET['productid'])) { ?>
                            <input type="hidden" name="productid" id="productid" value="<?php echo $_GET['productid']; ?>">
                            <?php } ?>
                        <div class="form-group">
                            <!--<label for="sel1">Select list:</label>-->
                            <select class="form-control" id="product_list">
                                <?php
                                if (empty($dropdownProduct)) {
                                    echo "<option>No procuct found</option>";
                                } else {
                                    echo "<option>Select Product</option>";
                                    foreach ($dropdownProduct as $row) {
                                        if ($_GET['productid'] == $row[1]) {
                                            echo "<option value=" . $row[1] . " selected  >" . $row[0] . "</option>";
                                        } else {
                                            echo "<option value=" . $row[1] . "  >" . $row[0] . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div> 
                         <div class="form-group">
                                Show/Close : 
                              <label for="status_yes"><input type="radio" name="status_check" onClick="choose('yes')"  id="status_yes" >Yes</label> 
                              <label  for="status_no"><input type="radio" name="status_check" onClick="choose('no')" id="status_no">No</label>
                            
                         </div>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                <table id="example" class="table table-striped table-bordered" style="width:100%;margin-top: 20px;">
                    <thead>
                                <?php
                                $i = 0;
                                $th = '';
                                $td = '';
                                if (!empty($result['product_details'])) {
                                    foreach ($result['product_details'] as $product) {
                                        if ($i == 0) {
                                            echo $th .= '<tr>';
                                            for ($count = 0; $count < $totalCol; $count++) {
                                                echo $th = '<th>' . $product[$count] . '</th>';
                                            }
                                            echo $th = '<th>Action</th></tr>';
                                            ?>

                                </thead>
                                <tbody>
                                    <?php
                                } else {
//                                    if($_GET['status']=='yes'){
//                                        echo $td = '<tr>';
//                                        for ($count = 0; $count < $totalCol; $count++) {
//                                            if(isset($product[$count])){
//                                                 echo $td = '<td>' . $product[$count] . '</td>';
//                                            }else{
//                                                echo $td = '<td></td>';
//                                            }
//                                           
//                                        }
//                                        echo $td = '<td><a class="btn" href="javascript:;" data-toggle="modal" data-target="#myModal" id="' . $i . '" onClick="popupwindow(this.id)"  >View</a> <a class="btn" href="javascript:;" data-toggle="modal" data-target="#editModal" id="' . $i . '" onclick="editwindow(this.id)"  >Edit</a> </td>
//                                            </tr>';
//                                    }elseif(!isset($_GET['status'])){
//                                        echo $td = '<tr>';
//                                        for ($count = 0; $count < $totalCol; $count++) {
//                                            if(isset($product[$count])){
//                                                 echo $td = '<td>' . $product[$count] . '</td>';
//                                            }else{
//                                                echo $td = '<td></td>';
//                                            }                                           
//                                        }
//                                        echo $td = '<td><a class="btn" href="javascript:;" data-toggle="modal" data-target="#myModal" id="' . $i . '" onClick="popupwindow(this.id)"  >View</a> <a class="btn" href="javascript:;" data-toggle="modal" data-target="#editModal" id="' . $i . '" onclick="editwindow(this.id)"  >Edit</a> </td>
//                                            </tr>';
//                                    }
                                    echo $td = '<tr>';
                                        for ($count = 0; $count < $totalCol; $count++) {
                                            if(isset($product[$count])){
                                                 echo $td = '<td>' . $product[$count] . '</td>';
                                            }else{
                                                echo $td = '<td></td>';
                                            }
                                           
                                        }
                                        echo $td = '<td><a class="btn" href="javascript:;" data-toggle="modal" data-target="#myModal" id="' . $i . '" onClick="popupwindow(this.id)"  >View</a> <a class="btn" href="javascript:;" data-toggle="modal" data-target="#editModal" id="' . $i . '" onclick="editwindow(this.id)"  >Edit</a> </td>
                                            </tr>';
                                }
                                $i++;
                            }
                        }
                        ?>
                    </tbody>

                </table>
            </div>
            </div>

        </div>



        <!-- view model -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Product View</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <table id="mytitle" style="width:100%"></table>
                            </div>
                            <div class="col-md-8">
                                <table id="myid" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


        <!-- view model -->

        <!-- update model -->
        <div class="modal fade" id="editModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Product Edit</h4>

                    </div>
                    <form method="POST" id="update_form" action="#">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <table style="width:100%">
                                        <tr><th><label style="padding:10px 0px;">Status</label></th></tr>
                                        <tr><th><label style="padding:10px 0px;">Comment</label></th></tr>
                                        <tr><th><label style="padding:12px 0px;">Verifide</label></th></tr>
                                        <?php 
                                            for($m =0;$m<count($additionalCalArr);$m++){
                                        ?>
                                        <tr><th><label style="padding:12px 0px;">Filed <?php echo $m; ?></label></th></tr>
                                        <?php    }
                                        ?>
                                    </table>
                                </div>
                                <div class="col-md-8">
                                    <table id="myEditId" style="width:100%;margin:5px 0px;">
                                        <tr>
                                            <td> 
                                                <select class="form-control" name="<?php echo $statusCal; ?>"  id="status" >
                                                    <?php         
                                                    foreach ($caseStatusDropdown as $row) {
                                                        echo "<option value=" . $row[0] . "  >" . $row[0] . "</option>";
                                                    }
                                                ?>
                                                </select>
                                                <input id="statusField" type="hidden" value="<?php echo $statusCal; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <textarea type="text" name="<?php echo $commentCal; ?>" id="comment"  class="form-control" style="margin:5px 0px;" value="" ></textarea>
                                                <input id="commentField" type="hidden" value="<?php echo $commentCal; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="<?php echo $verifedCal; ?>" id="verified">
                                                <?php         
                                                    foreach ($fieldvifyrangedropdown as $row) {
                                                        echo "<option value=" . $row[0] . "  >" . $row[0] . "</option>";
                                                    }
                                                ?>
                                                </select>
                                                <input id="verifiedField" type="hidden" value="<?php echo $verifedCal; ?>">
                                                <input id="additionalField" type="hidden" value="<?php echo $additionalCal; ?>">
                                            </td>
                                        </tr>
                                        <?php 
                                            for($m =0;$m<count($additionalCalArr);$m++){
                                        ?>
                                            <tr>
                                                <td> <input type="text" class="addtionalfiled form-control" name="<?php echo $additionalCalArr[$m]; ?>" ></td>
                                            </tr>
                                        <?php    }
                                        ?>
                                    </table>
                                </div>
                                <input type="hidden" name="product_row_id" class="form-control" style="margin:5px 0px;" id="productRowId" value="" />
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
                            <input type="submit" name="button" class="btn btn-default" value="Update" />

                        </div>
                    </form>
                </div>

            </div>
        </div>
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>


        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" class="init">

            $(document).ready(function () {
                $('#example').DataTable({
                    order: [],
                    columnDefs: [{orderable: false}]
                });
            });

        </script>
<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
        <script>
            
function choose(choice){
    var productid=$('#productid').val();
    if (choice == 'yes') {
        status= 'yes';
    }
    else{
        status='no';
    }
     window.location='table.php?productid='+productid+'&status='+status;
}


function test(click){
    alert(chanceoflive);
    alert(localStorage.chanceoflive);
}
        </script>
        <script>
            $('#product_list').on('change', function () {
                var dataId = this.value;

                window.location = 'table.php?productid=' + dataId;
            });
        </script>


        <script type="text/javascript">
            function popupwindow(rowid)
            {
                $('#mytitle').html('');
                $('#myid').html('Loading....');
                var productid = $('#productid').val();
                //alert(id);
                $.ajax({
                    type: 'POST',
                    url: 'product_info.php',
                    data: {
                        productid: productid,
                        rowid: rowid
                    },
                    success: function (response) {
                        var obj = JSON.parse(response);

                        document.getElementById("mytitle").innerHTML = obj.product_title;
                        document.getElementById("myid").innerHTML = obj.product_details;
                        //$("#vendor").val(response['name']);
                    }
                });
            }

            function editwindow(rowid)
            {
                var productid = $('#productid').val();
                $('#productRowId').val(rowid);
                
                $.ajax({
                    type: 'POST',
                    url: 'product_edit_info.php',
                    data: {
                        productid: productid,
                        rowid: rowid,
                        formData: $('#update_form').serialize()
                    },
                    success: function (response) {
                        var obj = JSON.parse(response);
                        for(var i in obj){
                            $('input[name="'+i+'"]').val(obj[i]);
                            $('select[name="'+i+'"]').val(obj[i]);
                            $('textarea[name="'+i+'"]').val(obj[i]);
                          }
                        console.log(obj);
                        
                        //$("#vendor").val(response['name']);
                    }
                });
                
           //     $('#editModal').modal('show');

            }

            $(function () {
                $("#update_form").submit(function (event) {
                    event.preventDefault();
                    

                    var sheetid = $('#productid').val();
                    var productrowid = $('#productRowId').val();
                    var status = $('#status').val();
                    var comment = $('#comment').val();
                    var verified = $('#verified').val();
                    var formData = $('#update_form').serialize();
                    $.ajax({
                        url: 'product_edit.php',
                        type: 'POST',
                        data: {
                            "formData": formData,
                            "updateData": {sheetid: sheetid, productrowid: productrowid, status: status, comment: comment, verified: verified}
                        },
                        success: function (result) {

                            if (result == 1)
                            {
                                $('#editModal').modal('hide');
                                // alert('Update Successfully');
                                // window.location='table.php';
                            } else {
                                $('#editModal').modal('hide');
                                // alert('Update faild');
                                // location.reload();
                            }
                        }
                    });
                });
            });
        </script>

    </body>
</html>