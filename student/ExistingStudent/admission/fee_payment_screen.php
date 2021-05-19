<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include('../../../config/database_student.php');


$SectionMaster_Id = $_SESSION['schoolzone_student']['SectionMaster_Id'];
$Activestudentregister_Id = $_SESSION['schoolzone_student']['Activestudentregister_Id'];
$CurrentSBM_Id = $_SESSION['schoolzone_student']['SBM_Id'];
//Fetching StudentName
$fetch_data_q = mysqli_query($mysqli,"SELECT user_studentregister.* FROM user_studentbatchmaster join `user_studentregister` on user_studentbatchmaster.studentRegister_Id = user_studentregister.Id WHERE user_studentbatchmaster.Id = '$CurrentSBM_Id'");

$r_fetch_data = mysqli_fetch_array($fetch_data_q);

//Finding AD_Id
$app_data_q = mysqli_query($mysqli,"SELECT  user_applicationdetails.Id as AD_Id FROM user_applicationdetails JOIN setup_batchmaster ON setup_batchmaster.Id = user_applicationdetails.batchMaster_Id WHERE user_applicationdetails.candidateRegister_Id = '$r_fetch_data[CR_Id]' AND setup_batchmaster.academicYear_Id = '$Acadmic_Year_ID'");
$row_app_data = mysqli_num_rows($app_data_q);

if($row_app_data > 0){
    $r_app_data = mysqli_fetch_array($app_data_q);
    $AD_Id = $r_app_data['AD_Id'];
}else{
    $AD_Id = '';
}



?>



<div class="row">
<div class="col-md-11">
    
    <div class="box">

    <div class="headingPanel form-inline" style="font-size:medium;font-style: italic;text-transform: uppercase;margin-bottom:20px;">
        <div class="row" style="margin-top:20px;">

            <div class="col-md-3" style="text-align:center;"><label>Student Name :</label></div>
            <div class="col-md-4">
                <span class="text-primary"><?php echo $r_fetch_data['student_name']; ?></span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label>Student Id :</label></div>
            <div class="col-md-3">
                <span class="text-primary"><?php echo $r_fetch_data['student_Id']; ?></span>
            </div>

        </div><!--DD Row1 Close-->

    </div><!--Heading Panel Close-->
</div>
</div>
</div>



<div class="row">
    <div class="col-md-11">
        <div class="box">
            <?php
            //Fetching Unique Header type 
            $header_type_q = mysqli_query($mysqli, "SELECT fee_receiptsdetails.feeheadertype_Id, fee_headertype.headertype_name FROM fee_receiptsdetails JOIN fee_headertype ON fee_headertype.Id = fee_receiptsdetails.feeheadertype_Id WHERE (fee_receiptsdetails.SBM_Id = '$CurrentSBM_Id' OR fee_receiptsdetails.applicationdetails_Id = '$AD_Id') AND( fee_receiptsdetails.feeheadertype_Id IS NOT NULL OR fee_receiptsdetails.feeheadertype_Id != '' ) GROUP BY fee_receiptsdetails.feeheadertype_Id ORDER BY fee_headertype.sequence ASC ");
            $row_header_type = mysqli_num_rows($header_type_q);

                if($row_header_type > 0){
                    $feepaymentlink = []; 
                    while($r_header_type = mysqli_fetch_array($header_type_q)){

                        $new_arrays = array(
                            "AY_Name" => $Academic_Year,
                            "AY_Id" => $Acadmic_Year_ID,
                            "headerType" => $r_header_type['feeheadertype_Id'],	
                            "FeeName" => $r_header_type['headertype_name']
                        );  
                        array_push($feepaymentlink, $new_arrays);
                        
                    }// close while
                }// close if
    
            ?>

            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th class="text-left">Fees Type</th>
                        <th>link</th>
                        <th>Status</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php 
                        // print_r($feepaymentlink);
                        $pending_count = 0;
                        if(isset($feepaymentlink)){
                        foreach ( $feepaymentlink as $feedetails_val ) {
                            //Finding Application Id Based on Academic Year
                            //Getting CUrrent Year AD Id
                            

                                // $order_status = mysqli_query($mysqli,"SELECT * FROM `fee_hdfcpg_order` WHERE `student_application_no` = '$r_app_data[Id]' AND order_status IN ('Pending', 'Awaited') ");
                                // $row_order_status = mysqli_num_rows($order_status); 
                                $row_order_status = '0'; 


                                // $fee_details_query = mysqli_query($mysqli, "SELECT fee_receiptsdetails.Id As FRD_Id, fee_receiptsdetails.Fee_name, fee_receiptsdetails.amount FROM `fee_receiptsdetails` WHERE fee_receiptsdetails.applicationdetails_Id = '$r_app_data[Id]' AND fee_receiptsdetails.amount != '0' AND fee_receiptsdetails.feepaymentstatus = '0' AND fee_receiptsdetails.feeheadertype = '$feedetails_val[headerType]'");

                                $fee_details_query = mysqli_query($mysqli, "SELECT
                                fee_receiptsdetails.applicationdetails_Id AS AD_Id,
                                fee_receiptsdetails.Id AS FRD_Id,
                                fee_receiptsdetails.SBM_Id,
                                fee_receiptsdetails.Fee_name, fee_receiptsdetails.amount
                              FROM
                                fee_receiptsdetails 
                              WHERE
                                fee_receiptsdetails.feepaymentstatus = 0 AND fee_receiptsdetails.feereceipts_Id = 0 AND (
                                  fee_receiptsdetails.SBM_Id = '$CurrentSBM_Id' OR fee_receiptsdetails.applicationdetails_Id = '$AD_Id'
                                ) AND fee_receiptsdetails.feeheadertype_Id = '$feedetails_val[headerType]' AND fee_receiptsdetails.amount != '0'");

                                $row_fees_details = mysqli_num_rows($fee_details_query);



                    ?>
                            <tr>
                                <td style="width:25%"><?php echo $feedetails_val['AY_Name']; ?></td>
                                <td style="width:25%" class="text-left"><?php echo $feedetails_val['FeeName']; ?></td>
                                <td>
                                    <?php if($row_order_status > '0'){ $r_order_status = mysqli_fetch_array($order_status); ?>
                                        <?php if($pending_count == '0'){ ?>    
                                            <button class="btn btn-warning pending_btn" id="<?php echo $r_order_status['order_id']; ?>" type="button">Pending</button>
                                        <?php } ?>
                                    <?php $pending_count++; }elseif($row_fees_details > '0') { ?>
                                        <button class="btn btn-primary fee_details_btn"  type="button" id="<?php echo $AD_Id; ?>" fee-header-type="<?php echo $feedetails_val['headerType']; ?>">link</button>
                                    <?php } ?>


                                </td>

                                <td>
                                    <?php if($row_order_status > '0'){ ?>
                                        <?php if($pending_count == '1'){ ?>    
                                            You have just initiated a transaction. Please try again after some time
                                        <?php } ?>
                                    <?php } ?>
                                </td>

        
                            </tr>
                    <?php   
                        } } 
                    ?>
                

                </tbody> 
            </table>



        </div>
    </div>



</div><!--Close Row-->

<!-------------------------------------------------------------------------------------------------------------- --------->
<div class="container col-md-11">
<hr>
<?php

$receipt_q = mysqli_query($mysqli,"SELECT fee_receipts.*, fee_receipts.Id as frid, setup_batchmaster.Batch_Name FROM fee_receipts JOIN setup_batchmaster ON setup_batchmaster.Id = fee_receipts.batchMaster_Id WHERE fee_receipts.SBM_Id = '$CurrentSBM_Id'  ");

$row_receipt_q = mysqli_num_rows($receipt_q);

if($row_receipt_q > 0){

?>


    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="100%" class="text-warning">Receipt Details</th>
            </tr>
            <tr>
                <th>Sr No</th>
                <th>Fees Receipt No</th>
                <th>Batch Name</th>
                <th>Date</th>   
                <th>Amount</th>
                <th>Generate Receipt</th>
            </tr>
        </thead>

        <tbody>
        <?php $i = 1; while($r_receipt_fetch = mysqli_fetch_array($receipt_q)){ ?>
            <tr>
                <th><?php echo $i; ?></th>
                <th>
                    <?php if($r_receipt_fetch['feeheadertype'] == '10'){
                        echo 'F';   
                    }elseif($r_receipt_fetch['feeheadertype'] == '1'){
                        echo 'A';
                    }else{
                        echo 'O';   
                    } ?>
                    <?php echo $r_receipt_fetch['receipt_no']; ?>
                
                </th>
                <th><?php echo $r_receipt_fetch['Batch_Name']; ?></th>
                <th><?php echo  date( 'd-m-Y', strtotime( str_replace( '/', '-', $r_receipt_fetch['date_of_payment'] ) ) ); ?></th>
                <th><?php echo $r_receipt_fetch['amount']; ?></th>

                <th><button class="btn btn-info receipt_Sel" type="button" id="<?php echo $r_receipt_fetch['frid']; ?>">Receipt</button></th>                
            </tr>
        <?php $i++; } ?>
        </tbody>
    
    </table>


<?php } ?>


<hr>
</div> <!--Receipt Details Container close-->

<!-------------------------------------------------------------------------------------------------------------- --------->

<script>

$('.pending_btn').click(function(e){
    
  e.preventDefault();
  var StudentNameTxt = $('.StudentNameTxt').text();
  var msgTxt = "I, "+ StudentNameTxt +", confirm that fees are not deducted from my account so far and would like to proceed to pay the fees now";

  if (confirm(msgTxt)){
    
    var order_id =  $(this).attr('id');

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'processing_api.php?OrderidStatusChange='+'u',
        type:'POST',
        data: {order_id:order_id},
        dataType: "json",
        success:function(response){
            //Checking Status 
            if(response['status'] == 'success') {

                var headertypeId = response['headertypeId'];
                var application_Id = response['application_Id'];
                $.ajax({
                    type:'GET',
                    url:'./cf_fees_details_new.php',
                    data: {application_Id: application_Id, headertypeId:headertypeId},
                    success:function(response){
                        $('#DisplayDiv').html(response);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                    },
                });

            }else {
                //FAILED STAY ON Current Page
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                iziToast.error({
                    title: 'Warning',
                    message: 'Try Again After Some time',
                });

            }// Close Else




        },
   });

  } // close if

});// pending


$('.fee_details_btn').click(function(e){
    var application_Id = $(this).attr('id');
    var headertypeId = $(this).attr('fee-header-type');

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    console.log(headertypeId);
    $.ajax({
        type:'GET',
        url:'./admission/fee_details_screen.php',
        data : {application_Id:application_Id, headertypeId:headertypeId},
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });

});


$('.receipt_Sel').click(function(e){
    var R_No = $(this).attr('id');

    window.open("./studentReceiptPDF.php?R_no="+ R_No, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=800,left=800,width=400,height=400"); 
});


</script>

<style>

.modal-header {
  background-color: #203e5f;
  color: #FFF;
}

.designTheme {
  background-color: #203e5f;
  color: #FFF;
}

.designTheme:hover {
  background-color: #FFF;
  border: 1px solid #203e5f;
}

th{
    text-align: center;
    padding: 25px;
    border-bottom: 0;
    font-style: italic;
    font-weight:bold;
}

td{
    text-align:center;
}

</style>