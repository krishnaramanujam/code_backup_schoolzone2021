<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


$return_batch_sel = $_POST['return_batch_sel'];
$selected_SBM_Id = $_POST['selected_SBM_Id'];

//Batch Details

if($ActiveStaffLogin_Id == '2'){
    $q = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND  setup_batchmaster.Id = '$return_batch_sel' ";
}else{
    $q = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id JOIN comm_batch_access ON comm_batch_access.batchMaster_Id = setup_batchmaster.Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND  setup_batchmaster.Id = '$return_batch_sel' ";
}
$batch_fetch = mysqli_query($mysqli,$q);
$r_batch_fetch = mysqli_fetch_array($batch_fetch);


?>
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Fee Allocation for Existing Students - (Batch : <?php echo $r_batch_fetch['batch_name']; ?>)</b></i></h4></div>


<input type="hidden" name="batch_sel" id="batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="selected_SBM_Id" id="selected_SBM_Id" class="form-control" value="<?php echo $selected_SBM_Id; ?>">



    <form id="FilterForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-2" style="text-align:center;">
                    <label for="email">Fee Structure:</label>
                </div>

                <div class="col-md-2"> 
                    <select name="FS_sel" id="FS_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select ----</option>
                                <?php 
                        
                                    $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' ";
                                    $run_de = mysqli_query($mysqli,$query_de);
                                    while($run_d = mysqli_fetch_array($run_de)){ ?>
                                    <option value="<?php echo $run_d['Id']; ?>"  
                                    <?php if($_POST['FS_sel'] == $run_d['Id']){ echo 'Selected'; } ?>
                                    ><?php echo $run_d['Fee_Structure_Name']; ?></option>
                                <?php }   ?>
                    </select>
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="submit" name="login" id="filter_result" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
    </form> 


    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-controls="collapseFive">
                    Selected Student
                </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                        <table id="online_table" class="table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Student Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php  $instance_fetch_q = mysqli_query($mysqli,"Select user_studentregister.student_name,  user_studentregister.student_Id, CASE WHEN user_studentbatchmaster.fee_allocation_status = 1 THEN 'Fee Allocated' WHEN user_studentbatchmaster.fee_allocation_status = 0 THEN 'Fee Not Allocated' ELSE 'NA' END AS fee_allocation_status_txt, user_studentbatchmaster.fee_allocation_status, user_studentbatchmaster.Id As SBM_Id FROM user_studentbatchmaster JOIN user_studentregister ON user_studentregister.SBM_Id = user_studentbatchmaster.Id WHERE user_studentbatchmaster.batchMaster_Id = '$return_batch_sel' AND  user_studentbatchmaster.Id IN ($selected_SBM_Id) ");
                                $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $r_instance_fetch['student_name']; ?></td>
                                    <td><?php echo $r_instance_fetch['student_Id']; ?></td>    
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div> <!--Close Panel -->
    </div>


<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){
        $FS_sel = $_POST['FS_sel'];
        $FS_txt = $_POST['FS_txt'];


        $fee_structure_details_q = mysqli_query($mysqli, "SELECT fee_structure_details.*, fee_structure_master.Fee_Structure_Name,setup_academicyear.abbreviation FROM `fee_structure_details` JOIN fee_structure_master ON fee_structure_master.Id = fee_structure_details.Fee_Structure_Id JOIN setup_academicyear ON setup_academicyear.Id = fee_structure_details.academicYearId WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' AND  fee_structure_details.academicYearId = '$Acadmic_Year_ID' AND fee_structure_details.Fee_Structure_Id  = '$FS_sel' ");

        $row_fee_str_count = mysqli_num_rows($fee_structure_details_q);
        if($row_fee_str_count > 0){
            $FSD_Arr = [];
            $tot_prop = 0;
            $entry_count = 0;
            while($r_FSD_data = mysqli_fetch_array($fee_structure_details_q)){
                $new_arrays = array(
                    'FSD_Name' => $r_FSD_data['Name'],
                    'FSD_Abbreviation' => $r_FSD_data['Abbreviation'],
                    'FSD_proportion' => $r_FSD_data['proportion'],
                    'FSD_Id' => $r_FSD_data['Id']
                );  
                array_push($FSD_Arr, $new_arrays);
                $tot_prop = $tot_prop + $r_FSD_data['proportion'];

                $entry_count++;
            }
        }// close if
        // print_r($FSD_Arr);


?>                                        

    <div class="row">
        <div class="col-md-9">
        <form id="StudentListForm">
        <input type="hidden" name="SBM_Id" class="form-control" value="<?php echo $selected_SBM_Id; ?>">
          <table class="table table-striped" id="InstanceMaster_Table">
             <thead>
                 <tr><th colspan="100%" class="text-center">Fee Structure: <span class="text-primary"><?php echo $FS_txt; ?></span></th></tr>
                 <tr>
                    <th></th>
                    <th></th><th></th>
                    <?php foreach ( $FSD_Arr as $FSD_Arr_header ) { ?>
                        <th><?php echo $FSD_Arr_header['FSD_Name']; ?></th> 
                    <?php } ?>
                    <th></th>
                    <th></th>
                 </tr>
                 <tr>
                    <th>Sr No</th>
                    <th>Fee Header</th><th>Amount / Weightage</th>
                    <?php foreach ( $FSD_Arr as $FSD_Arr_header ) { ?>
                        <th><?php echo $FSD_Arr_header['FSD_proportion']; ?>
                            <input type="hidden" value="<?php echo $FSD_Arr_header['FSD_Id']; ?>" name="FSD_Id[]">
                        </th> 
                    <?php } ?>
                    <th>Total</th>
                    <th><input type="checkbox" class="subcheck" id="check"></th>
                 </tr>
             </thead>
             <tbody>
                  <?php  $instance_fetch_q = mysqli_query($mysqli," SELECT fee_feemaster.*, fee_headermaster.header_name FROM `fee_feemaster` JOIN fee_headermaster ON fee_headermaster.Id = fee_feemaster.feeheader_Id WHERE fee_feemaster.sectionmaster_Id = '$SectionMaster_Id' AND fee_feemaster.batchmaster_Id = '$return_batch_sel' ");
                 $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>
                     <tr> 
                         <td style="width:10%"><?php echo $i; ?></td>
                         <td style="width:15%;text-align:left;"><?php echo $r_instance_fetch['header_name']; ?></td>
                         <td class="weight_amount_<?php echo $r_instance_fetch['Id']; ?>"><?php echo $r_instance_fetch['Amount']; ?></td>
                         

            
                        <?php foreach ( $FSD_Arr as $FSD_Arr_header ) { ?>
                            <td><input type="text" id="allocate_amount_<?php echo $r_instance_fetch['Id']; ?>_<?php echo $FSD_Arr_header['FSD_Id']; ?>" class="row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?> row_allocate_amount_all" name="allocate_amount_<?php echo $r_instance_fetch['Id']; ?>_<?php echo $FSD_Arr_header['FSD_Id']; ?>[]" style="border-bottom: 2px solid #19aa6e;" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"></td>

                            <script>
                                

                                var current_prop = <?php echo $FSD_Arr_header['FSD_proportion']; ?>;
                                var total_prop = <?php echo $tot_prop; ?>;
                                var weight_amount = $('.weight_amount_<?php echo $r_instance_fetch['Id']; ?>').text();
                                var tot = (parseInt(current_prop) / parseInt(total_prop)  * parseInt(weight_amount)).toFixed();
                                $('#allocate_amount_<?php echo $r_instance_fetch['Id']; ?>_<?php echo $FSD_Arr_header['FSD_Id']; ?>').val(tot);
                                var texts= $(".row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>").map(function() {
                                       return $(this).val();
                                    }).get();
                                sum = 0;
                                texts.forEach(function (element, index) {
                                
                                if(element == '' || element == undefined){
                                    element = 0;
                                }
                                sum = sum + parseInt(element);
                                });
                                $('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').val(sum);


                                $('#allocate_amount_<?php echo $r_instance_fetch['Id']; ?>_<?php echo $FSD_Arr_header['FSD_Id']; ?>').keyup(function() {
                                    var current_prop = <?php echo $FSD_Arr_header['FSD_proportion']; ?>;
                                    var current_field = $(this).val();  
                                    var total_prop = <?php echo $tot_prop; ?>;
                                    var weight_amount = $('.weight_amount_<?php echo $r_instance_fetch['Id']; ?>').text();

                                    var tot = (parseInt(current_prop) / parseInt(total_prop)  * parseInt(weight_amount)).toFixed();

                                    // if(parseInt(current_field) > parseInt(tot)){
                                    //     iziToast.warning({
                                    //         title: 'Warning',
                                    //         message: 'Enter amount is more then proportion allocated, Max amount : '+ tot,
                                    //     });

                                    //     $(this).val(0);
                                    // }



                                });
                            </script>
                        <?php } ?>

                        
                            <script>
                                //Disabled Entries
                                $('.row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", true);
                                $('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", true);

                                 //GP KEY PRESS
                                $('.row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>').keyup(function() {
                                    var texts= $(".row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>").map(function() {
                                       return $(this).val();
                                    }).get();

                                 
                                    sum = 0;
                                    texts.forEach(function (element, index) {
                                    
                                    if(element == '' || element == undefined){
                                        element = 0;
                                    }
                                    sum = sum + parseInt(element);
                                    });
                                    
                                    
                                    $('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').val(sum);

                                });
                            


                                $('#check<?php echo $r_instance_fetch['Id']; ?>').click(function(){
                                    var overall_tot = $('.overall_tot').text();
                                    if($(this).prop("checked") == false){
                                        $('.row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", true);
                                        $('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", true);
                                        
                                        var newtot = +overall_tot - parseInt($('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').val());
                                    }else{
                                        $('.row_allocate_amount_<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", false);
                                        $('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').prop("disabled", false);
                                        
                                        var newtot = +overall_tot + parseInt($('#row_totalamount<?php echo $r_instance_fetch['Id']; ?>').val());
                                    }   
                                    $('.overall_tot').text(newtot);
                                });
                            </script>
               
                        <td><input type="text" id="row_totalamount<?php echo $r_instance_fetch['Id']; ?>" name="row_totalamount[]" readonly value="" class="row_totalamount_all"></td> 

                         <td>    
                            <div class="pretty p-icon p-smooth">
                                    <input type="checkbox" class="subcheck sel_box" id="check<?php echo $r_instance_fetch['Id']; ?>" name="FM_Id[]" value="<?php echo $r_instance_fetch['Id']; ?>" >
                                        <div class="state p-success">
                                            <i class="icon fa fa-check"></i>
                                            <label></label>
                                        </div>
                            </div> 
                         </td>
                     </tr>
                 <?php $i++; } ?>


             </tbody>
             <tfoot>
                 <tr>
                    <th colspan="3"></th>
                    <th colspan="<?php echo $entry_count; ?>" class="text-right">Total : </th>
                    <th class="overall_tot">0</th>
                    <th></th>
                 </tr>
             </tfoot>
         </table>
         <div class="panel-footer" style="text-align: center;"> 
            <button type="button" class="btn btn-primary" id="save_Fee_list">Submit</button>
        </div>
        </form>

        </div>
    </div>
<?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->


<script>

$('#check').click(function(){
    var overall_tot = $('.overall_tot').text();
    if($(this).prop("checked") == false){
        $(".sel_box").removeAttr('checked');
    
        $('.row_totalamount_all').prop("disabled", true);
        $('.row_allocate_amount_all').prop("disabled", true);

        var texts= $(".row_totalamount_all").map(function() {
                return $(this).val();
            }).get();

        sum = 0;
        texts.forEach(function (element, index) {
        
        if(element == '' || element == undefined){
            element = 0;
        }
        sum = sum + parseInt(element);
        });
        var new_tot = overall_tot - sum;
        
        console.log(new_tot);
        
    }else{
        $('.row_totalamount_all').prop("disabled", false);
        $('.row_allocate_amount_all').prop("disabled", false);

        $(".sel_box").prop('checked', true);

        var texts= $(".row_totalamount_all").map(function() {
                return $(this).val();
            }).get();
        sum = 0;
        texts.forEach(function (element, index) {
        
        if(element == '' || element == undefined){
            element = 0;
        }
        sum = sum + parseInt(element);
        });
        
        var new_tot = sum;
    }   
    $('.overall_tot').text(new_tot);
});

$('.return_btn').click(function(event){
    event.preventDefault();

    var batch_sel = $('#batch_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
        url:'./fee_management/fee_allocation_existing.php?Generate_View='+'u',
        type:'GET',
        data: {batch_sel:batch_sel},
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });
});


$('#FilterForm').submit(function(e){
    e.preventDefault();
    var FS_sel = $('#FS_sel').val();

    var FS_txt = $("#FS_sel option:selected").text();

    var selected_SBM_Id = $('#selected_SBM_Id').val();

    var batch_sel = $('#batch_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./fee_management/fee_allocation_existing_save_students.php?Generate_View='+'u',
        type:'POST',
        data: {FS_sel:FS_sel, FS_txt:FS_txt, selected_SBM_Id:selected_SBM_Id, return_batch_sel:batch_sel},
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });
});




//Instance Edit----------------------------------------------------------------------------------------------------
$('#save_Fee_list').click(function(event){
    event.preventDefault();
    var batch_sel = $('#batch_sel').val();

    var newSelectResult = '';
    var AmountMatchStatus = '';
    $('.sel_box:checked').each(function () {
        var status = (this.checked ? $(this).val() : "");
        var id = $(this).attr("id");
        newSelectResult = newSelectResult.concat( status , ",");

        var current_val = $(this).val();

        var weight_amt = $('.weight_amount_'+current_val).text();
        var generated_amt = $('#row_totalamount'+current_val).val();



        if(parseInt(weight_amt) != parseInt(generated_amt)){
            AmountMatchStatus = 'AmountNotMatch';
        }
    });


    if(newSelectResult == ''){
        alert('Please select Checkbox');
        return false;
    }

    
    if(AmountMatchStatus != ''){
        alert('Amount not matching');
        return false;
    }



    var FORMDATA = $('#StudentListForm').serializeArray();
  
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url: './fee_management/fee_management_api.php?Add_FeeForNewExistingStudents=u',
        type:'POST',
        data: FORMDATA,
        dataType: "json",
        success:function(response){
            //Checking Status 
            if(response['status'] == 'success') {
                $.ajax({
                    url:'./fee_management/fee_allocation_existing.php?Generate_View='+'u',
                    type:'GET',
                    data: {batch_sel:batch_sel},
                    success:function(response){
                        $('#DisplayDiv').html(response);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                    },
                });

                iziToast.success({
                    title: 'Success',
                    message: 'Fee Allocated Successfully',
                });
            }

        },
   });

});


$('#online_table').DataTable( {
    dom: 'Bifrtp',
    bPaginate:false,
    
    buttons: [
    {
        extend: 'excel',
        footer: true,
		title: "Student List",
		text: 'Excel',
        exportOptions : {
            columns : ':visible',
            format : {
                header : function (mDataProp, columnIdx) {
            var htmlText = '<span>' + mDataProp + '</span>';
            var jHtmlObject = jQuery(htmlText);
            jHtmlObject.find('div').remove();
            var newHtml = jHtmlObject.text();
            console.log('My header > ' + newHtml);
            return newHtml;
                }
            }
        }
    }
    ]
} );


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

