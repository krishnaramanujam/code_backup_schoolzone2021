<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


$student_Id = $_GET['student_Id'];
$SBM_Id = $_GET['SBM_Id'];


//Student Details
$q = "SELECT user_studentregister.student_name, user_studentregister.student_Id, user_studentbatchmaster.fee_allocation_status, user_studentbatchmaster.Id AS SBM_Id, setup_batchmaster.batch_name, user_studentregister.gr_no FROM user_studentbatchmaster JOIN user_studentregister ON user_studentregister.SBM_Id = user_studentbatchmaster.Id JOIN setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id LEFT JOIN comm_batch_access ON comm_batch_access.batchMaster_Id = setup_batchmaster.Id JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE  setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND user_studentbatchmaster.Id = '$SBM_Id'";
$studentdetails_fetch_q = mysqli_query($mysqli,$q);

$r_studentdetails = mysqli_fetch_array($studentdetails_fetch_q);

?>
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Existing Student Fee Details</b></i></h4></div>


<input type="hidden" name="student_Id" id="student_Id" class="form-control" value="<?php echo $student_Id; ?>">
<input type="hidden" name="SBM_Id" id="SBM_Id" class="form-control" value="<?php echo $SBM_Id; ?>">


   <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// --> 
   <div class="col-md-11">
    
    <div class="box">

    <div class="headingPanel form-inline" style="font-size:medium;font-style: italic;text-transform: uppercase;margin-bottom:20px;">
        


        <div class="row" style="margin-top:20px;">

            <div class="col-md-3" style="text-align:center;"><label>Student Name :</label></div>
            <div class="col-md-4">
                <span class="text-primary"><?php echo $r_studentdetails['student_name']; ?></span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label>Student Id :</label></div>
            <div class="col-md-3">
                <span class="text-primary"><?php echo $r_studentdetails['student_Id']; ?></span>
            </div>

        </div><!--DD Row1 Close-->

        <div class="row">

            <div class="col-md-3" style="text-align:center;"><label>Batch Name :</label></div>
            <div class="col-md-4">
                <span class="text-primary"><?php echo $r_studentdetails['batch_name']; ?></span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label>GR NO :</label></div>
            <div class="col-md-3">
                <span class="text-primary"><?php echo $r_studentdetails['gr_no']; ?></span>
            </div>

        </div><!--DD Row2 Close-->

        <div class="row">

            <div class="col-md-3" style="text-align:center;"><label>Date of Receipt :</label></div>
            <div class="col-md-4">
                <span class="text-primary"> <input type="text" class="form-control" id="receipt_date" name="receipt_date" value="" placeholder="Enter Date" style="border-bottom: 2px solid #19aa6e;"> </span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label></label></div>
            <div class="col-md-3">
                <span class="text-primary"></span>
            </div>

        </div><!--DD Row2 Close-->
     
     
         </div>

    </div><!--Heading Panel Close-->
    </div>

    <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// --> 



<!-------------------------------------------------------------------------------------------------------------- --------->
<div class="container col-md-11">
<hr>
<?php

$receipt_q = mysqli_query($mysqli,"SELECT fee_receipts.*, fee_receipts.Id as frid, setup_batchmaster.Batch_Name FROM fee_receipts JOIN setup_batchmaster ON setup_batchmaster.Id = fee_receipts.batchMaster_Id WHERE fee_receipts.SBM_Id = '$SBM_Id' ");

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

<!-------------------------------------------------------------------------------------------------------------- --------->
<?php 
    $fee_type_q = mysqli_query($mysqli,"SELECT fee_receiptsdetails.Id AS FRD_Id, fee_receiptsdetails.Fee_name, SUM(fee_receiptsdetails.amount) as amount, fee_structure_details.Id AS SSD_Id, fee_structure_details.Name, fee_structure_details.Payable_date, fee_structure_details.Last_Date FROM fee_receiptsdetails LEFT JOIN fee_structure_details ON fee_structure_details.Id = fee_receiptsdetails.feestructuredetails_Id WHERE fee_receiptsdetails.SBM_Id = '$SBM_Id' AND fee_receiptsdetails.amount != '0' AND fee_receiptsdetails.feepaymentstatus = '0' AND fee_receiptsdetails.feeheadertype_Id = '1' GROUP By fee_structure_details.Id Order By fee_structure_details.sequence ");
    $row_fee_type = mysqli_num_rows($fee_type_q);
    if($row_fee_type > 0){
        $FSD_Arr = [];

        $FSD_Id_Arr = [];
        while($r_fee_type = mysqli_fetch_array($fee_type_q)){ 
        $tot_prop = $tot_prop + $r_fee_type['amount'];

        array_push($FSD_Id_Arr, $r_fee_type['SSD_Id']);

        $FSD_Val_Id = implode(',', $FSD_Id_Arr);

        $new_arrays = array(
            'FSD_Id' => $FSD_Val_Id,
            'FSD_Amount' => $tot_prop
        );  

        array_push($FSD_Arr, $new_arrays);
?>
<?php } }  ?>

<div class="container col-md-11">
    <form id="SelectionForm form-horizontal">

        <div class="form-group">
            <label class="col-sm-2 control-label text-center">Fees Type</label>
            <div class="col-sm-8">
                <select name="sel_feestype" id="sel_feestype" class="form-control">
                    <option value="">Select Fees Type</option>
                    
                    <?php if(isset($FSD_Arr)){ $i = 1; foreach ( $FSD_Arr as $FSD_Arr_header ) { ?>
                        <option value="<?php echo $FSD_Arr_header['FSD_Id']; ?>"  
                        <?php if($_GET['sel_feestype'] == $FSD_Arr_header['FSD_Id']){ echo 'Selected'; } ?>
                        >  Pay <?php echo $i; ?> instalment - Rs <?php echo $FSD_Arr_header['FSD_Amount']; ?></option>
                    <?php $i++; } } ?>
                    
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" value="submit" id="fee_type_submit" class="btn btn-primaryx">Submit</button>
            </div>
        </div>
       
    </form>
</div>
<!-------------------------------------------------------------------------------------------------------------- --------->


<!-- ---------------------------------------------------------------------------------------------------- -->

<?php 
    if(isset($_GET['Fees_Summary_Display'])){
    $sel_feestype = $_GET['sel_feestype'];
    
    $fee_details_group_query = mysqli_query($mysqli,"SELECT fee_receiptsdetails.Id AS FRD_Id, fee_receiptsdetails.Fee_name, SUM(fee_receiptsdetails.amount) as amount, fee_structure_details.Id AS SSD_Id, fee_structure_details.Name, fee_structure_details.Payable_date, fee_structure_details.Last_Date FROM fee_receiptsdetails LEFT JOIN fee_structure_details ON fee_structure_details.Id = fee_receiptsdetails.feestructuredetails_Id WHERE fee_receiptsdetails.SBM_Id = '$SBM_Id' AND fee_receiptsdetails.amount != '0' AND fee_receiptsdetails.feepaymentstatus = '0' AND fee_receiptsdetails.feeheadertype_Id = '1' AND fee_receiptsdetails.feestructuredetails_Id IN ($sel_feestype) Group BY fee_receiptsdetails.feeheader_Id Order By fee_receiptsdetails.feeheader_Id ");
    $row_fees_details_group = mysqli_num_rows($fee_details_group_query);

    //All Receipt Entries
    $fee_details__query = mysqli_query($mysqli,"SELECT fee_receiptsdetails.Id AS FRD_Id, fee_receiptsdetails.Fee_name, fee_receiptsdetails.amount AS amount, fee_structure_details.Id AS SSD_Id, fee_structure_details.Name, fee_structure_details.Payable_date, fee_structure_details.Last_Date FROM fee_receiptsdetails LEFT JOIN fee_structure_details ON fee_structure_details.Id = fee_receiptsdetails.feestructuredetails_Id WHERE fee_receiptsdetails.SBM_Id = '$SBM_Id' AND fee_receiptsdetails.amount != '0' AND fee_receiptsdetails.feepaymentstatus = '0' AND fee_receiptsdetails.feeheadertype_Id = '1' AND fee_receiptsdetails.feestructuredetails_Id IN($sel_feestype) ORDER BY fee_receiptsdetails.feeheader_Id  ");
    $row_fees_details = mysqli_num_rows($fee_details__query);

?>

<div class="container col-md-11">
    <form id="formInfo">

        <?php 
            if($row_fees_details > 0){
             while($r_fees_details = mysqli_fetch_array($fee_details__query)){   
        ?>

            <input type="hidden" id="fees_details_Id<?php echo $r_fees_details['FRD_Id']; ?>" name="fees_details_Id[]" value="<?php echo $r_fees_details['FRD_Id']; ?>"  readonly>

        <?php } }?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th colspan="100%" class="text-warning">Fees Summary</th>
                </tr>
                <tr>
                    <th>Sr No</th>
                    <th style="text-align:left;">Fees Type</th>
                    <th style="color:#3c763d;width: 20%;text-align:left;">Total Amount</th>
                    <th hidden>Payable Date</th>
                    <th style="width: 25%;"></th>
                </tr>
            </thead>
            <tbody>

                <?php $total_all_feess = 0; $i = 1;?>
                <?php if($row_fees_details_group > 0) {  
                        while($r_fees_details_group = mysqli_fetch_array($fee_details_group_query)){    
                    
                        $total_all_feess = $total_all_feess + $r_fees_details_group['amount'];
                ?>

                <tr>
                    
                    <td><?php echo $i; ?></td>
                    <td style="text-align:left;"><?php echo $r_fees_details_group['Fee_name']; ?></td>
                    <td style="text-align:left;padding-left: 20px;"><?php echo $r_fees_details_group['amount']; ?></td>
                    
                    <td hidden> 
                        <?php if(empty($r_fees_details_group['Payable_date'])){ ?>

                        <?php }else{ ?>
                            <?php echo  date( 'd-m-Y', strtotime( str_replace( '/', '-', $r_fees_details_group['Payable_date'] ) ) ); ?>
                        <?php } ?>
                    </td>

                    <td></td>
                </tr>

                 <?php $i++; } //close while ?>
                <?php }  //close if?>

                </tbody>

                <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align:left;">Total:</th>
                        <th colspan="2" style="text-align:left;"><input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo $total_all_feess; ?>" Placeholder="" style="text-align: left;" readonly>  </th>
                    </tr>

                    <tr>
                        <td></td>
                        <td style="text-align:left;">Previous fees adjustment</td>
                        <td style="text-align:left;"><input type="text" value="0" name="previous_fee" id="previous_fee" style="border-bottom: 2px solid #19aa6e;background-color: #eee; " onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" readonly></td>
                        <td></td>
                    </tr> 
                    <tr>
                        <td></td>
                        <td style="text-align:left;">Current fees adjustment</td>
                        <td style="text-align:left;"><input type="text" value="0" name="current_fee" id="current_fee" style="border-bottom: 2px solid #19aa6e;"  disabled></td>
                        
                        <td class="text-left">
                            <div class="pretty p-icon p-smooth">
                                    <input type="checkbox" id="current_fee_ajust_box" name="current_fee_ajust_box" value="on">
                                        <div class="state p-success">
                                            <i class="icon fa fa-check"></i>
                                            <label>Check to Enable</label>
                                        </div>
                            </div>
                        </td>
                
                    </tr>

                    <tr>
                        <th></th>
                        <th style="text-align:left;">Overall Total:</th>
                        <th colspan="2" style="text-align:left;"><input type="text" class="form-control" id="overall_total_amount" name="overall_total_amount" value="<?php echo $total_all_feess; ?>" Placeholder="" style="text-align: left;" readonly>  </th>
                    </tr>
 
                </tfoot>

               
        </table>

        <hr>

        <!-- PAYMENT OPTIONS -->

        <table class="table"> 
            <thead>
                <tr>
                    <th colspan="100%" class="text-warning">Payment Options</th>
                </tr>
                <tr>
                    <th>Select Payment Mode</th><th>Amount</th><th>Bank Name</th><th>Instrument no</th><th>Instrument date</th><th>RRN 
                    </th><th>APPR</th><th>Remove</th>
                </tr>
            </thead>

            <tbody class="payment_table_body">
                    
            </tbody>
            
        </table>
        <div class="panel-footer" style="text-align: left;"> 
            <input type="button" name="add_payment" value="Add Payment Method" id="add_payment" class="btn" style="background-color:#018488; color: white;" data-toggle="modal" data-target="#freeshipmodal">  
        </div>



        <!-- PAYMENT OPTION CLOSE -->

        <div class="panel-footer" style="text-align: center;"> 
            <button type="button" class="btn btn-success"  name="pay_fees_online" id="pay_fees_online">Pay Fees</button>  
        </div>

    </form>
</div>


    <?php include('./add_payment_options.php'); ?>
    <script>
    
    $("#formInfo").on('click', ".delete_instance_btn", function() {
        var $tr_r = $(this).closest('.tr_clone');
        var rem = $tr_r.remove().fadeOut(300);
        iziToast.success({
            title: 'Success',
            message: 'Payment Option Successfully Removed',
        });
    });
    
    


    </script>
<?php } // close Fee Summary Isset?>
<!-- ---------------------------------------------------------------------------------------------------- -->

    
<script>



$('.return_btn').click(function(event){
    event.preventDefault();

    var student_Id = $('#student_Id').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
        url:'./fee_management/srh_existing_student_fees.php?Generate_View='+'u',
        type:'GET',
        data: {student_Id:student_Id},
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });
});


//-----------------------------------------------------------------

$('#fee_type_submit').click(function(e){
    var sel_feestype = $('#sel_feestype').val(); // the selected options’s value
    var SBM_Id = $('#SBM_Id').val();
    var student_Id = $('#student_Id').val();

    if(sel_feestype == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'Please Select Valid Fees Type',
            });
            return false;
    }
	$("#loader").css("display", "block");
	$("#DisplayDiv").css("display", "none");
    $.ajax({
        type:'GET',
        url:'./fee_management/existing_student_pay_fee_details.php?Fees_Summary_Display=u',
        data: {sel_feestype:sel_feestype, student_Id:student_Id, SBM_Id:SBM_Id},
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });
               

});
    
//-----------------------------------------------------------------


$('#previous_fee').keyup(function() {
    var current_fee = $('#current_fee').val();
    var previous_fee = $('#previous_fee').val();
    var total_amount = $('#total_amount').val();


    if(previous_fee == ''){
        previous_fee = 0;
        $('#previous_fee').val(previous_fee);
    }

    var new_tot = +(total_amount) + +(previous_fee) - +(current_fee);

    $('#overall_total_amount').val(new_tot);
});

$("#current_fee").keypress(function(e){
  if (e.which != 46 && e.which != 45 && e.which != 46 &&
      !(e.which >= 48 && e.which <= 57)) {
    return false;
  }
});

$('#current_fee').keyup(function(e) {
    var current_fee = $('#current_fee').val();
    var previous_fee = $('#previous_fee').val();
    var total_amount = $('#total_amount').val();


    if(current_fee == ''){
        current_fee = 0;
        $('#current_fee').val(current_fee);
    }

    var new_tot = +(total_amount) + +(previous_fee) + +(current_fee);

    if(Number.isNaN(new_tot)){
        $('#current_fee').val(0);
        new_tot = $('#total_amount').val();
    }

    $('#overall_total_amount').val(new_tot);
});

$('#current_fee_ajust_box').click(function(){
    var total_amount = $('#total_amount').val();
    if($(this).prop("checked") == false){
        //Overall Total
        $('#current_fee').prop("disabled", true);

        $('#current_fee').val(0);
        $('#overall_total_amount').val(total_amount);
    }else{
        //Overall Total
        $('#current_fee').prop("disabled", false);
    }   
   
});




$('#pay_fees_online').click(function(event){
    event.preventDefault();
    var overall_total_amount = $('#overall_total_amount').val();
    var AmountMatchStatus = '';

    //Checking Total Payment Amount
    var texts= $(".paymentamt_all").map(function() {
                return $(this).val();
            }).get();
    sum = 0;
    texts.forEach(function (element, index) {
    
    if(element == '' || element == undefined){
        element = 0;
    }
    sum = sum + parseInt(element);
    });

    if(parseInt(sum) != parseInt(overall_total_amount)){
        AmountMatchStatus = 'AmountNotMatch';
    }

    if(AmountMatchStatus != ''){
        iziToast.warning({
            title: 'Warning',
            message: 'Amount Not Matching',
        });
        return false;
    }

    var receipt_date = $('#receipt_date').val();
    if(receipt_date == ''){
        iziToast.warning({
            title: 'Warning',
            message: 'Please Select Date Of Payment',
        });
        return false;
    }

    var FORMDATA = $('#formInfo').serializeArray();
    var SBM_Id = $('#SBM_Id').val();

    var student_Id = $('#student_Id').val();

    
  
  $("#loader").css("display", "block");
  $("#DisplayDiv").css("display", "none");

  $.ajax({
      url: './fee_management/fee_management_api.php?GenerateReceipt_FeeForNewExistingStudents=u&SBM_Id='+SBM_Id+'&Receipt_date='+receipt_date,
      type:'POST',
      data: FORMDATA,
      dataType: "json",
      success:function(response){
          //Checking Status 
          if(response['status'] == 'success') {
              
              $.ajax({
                url:'./fee_management/existing_student_pay_fee_details.php',
                type:'GET',
                data: {SBM_Id: SBM_Id, student_Id:student_Id},
                success:function(si_logs){
                    $('#DisplayDiv').html(si_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                    
                    
                },
            });  

            iziToast.success({
                        title: 'Success',
                        message: 'Receipt Generated Successfully',
                    });
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            
          }else{
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                  title: 'Error',
                  message: 'Try After Some Time',
            });
          }

      },
 });
});



$('.receipt_Sel').click(function(e){
    var R_Id = $(this).attr('id');
    var SBM_Id = $('#SBM_Id').val();

    window.open("./fee_management/receipts.php?R_Id="+ R_Id +"&SBM_Id="+SBM_Id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=800,left=800,width=400,height=400"); 
});

</script>

<style>


.detailsinput{
    border-bottom: 2px solid #3c8dbc;
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

input[type=text]{
border:0px;
background-color: transparent;
width: 70px;
text-align:center;
}

input[type=text]:hover{
    cursor: pointer;
}

input:disabled {
    background-color: #eee;          
}
</style>
<script>

$('#receipt_date').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        endDate: "today",
        startDate: "-52w"
    });
</script>

