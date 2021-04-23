<?php

session_start();
include_once '../../config/database.php';


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
?>


<form id= "Edit_FormData">
<div class="container">
    <div class="col-md-12"><h4><i><b>  Student/Candidate Control Access</b></i></h4></div>


    <div class="col-md-10">
        <table class="table table-striped" id="InstanceMaster_Table">
             <thead>
                 <tr><th>Sr No.</th><th style="text-align:left;">Batch Name</th><th>Operations</th></tr>
             </thead>
             <tbody>
                 <?php  $instance_fetch_q = mysqli_query($mysqli,"SELECT setup_batchmaster.* FROM `setup_batchmaster` JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND setup_streammaster.sectionmaster_Id = '$SectionMaster_Id'");
                 $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>

                     <tr> 
                         <td style="width:5%"><?php echo $i; ?></td>
                         <td style="width:20%;text-align:left;"><?php echo $r_instance_fetch['batch_name']; ?></td>
                       
                         <td style="width:20%">
                            <div class="btn-group btn-group-xs" role="group" aria-label="...">
                            
                             <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default studentaccess_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Student Access" data-toggle="tooltip"> Student Access </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default candidateaccess_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Candidate Access" data-toggle="tooltip"> Candidate Access </button>
                                </div>
                             </div>


                         </td>
                     </tr>

                 <?php $i++; } ?>

             </tbody>
         </table>



    </div>

</div>
</form>

<script>
//Instance Delete----------------------------------------------------------------------------------------------------
$('.studentaccess_btn').click(function(event){
	var batch_sel = $(this).attr('id');
    var usertype = '1';     

    $.ajax({
        url:'./user_management/page_control_access_batchwise.php',
        type:'POST',
        data: {usertype:usertype, batch_sel:batch_sel},
        success:function(st_logs){
            $('#DisplayDiv').html(st_logs);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });   

});
//Instance Delete Close----------------------------------------------------------------------------------------------------

//Instance Delete----------------------------------------------------------------------------------------------------
$('.candidateaccess_btn').click(function(event){
	var batch_sel = $(this).attr('id');
    var usertype = '2';     

    $.ajax({
        url:'./user_management/page_control_access_batchwise.php',
        type:'POST',
        data: {usertype:usertype, batch_sel:batch_sel},
        success:function(st_logs){
            $('#DisplayDiv').html(st_logs);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });   

});
//Instance Delete Close----------------------------------------------------------------------------------------------------
</script>