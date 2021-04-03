<?php

session_start();
include_once '../../config/database.php';
$user_id_edit = $_POST['user_id_edit'];


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
?>


<form id= "Edit_FormData">
<div class="container">
    <div class="col-md-12"><h4><span class="badge btn btn-primary" onclick="return_controlaccess();"><i class="fa fa-arrow-left"></i></span><i><b>  Batch Access</b></i></h4></div>


    <div class="col-md-12">
         <input type="hidden" value="<?php echo $user_id_edit; ?>" id="user_id" name="user_id">
    </div>


    <div class="col-md-10">
    <ul class="list-group" id="list-tab" role="tablist">
    <li class="list-group-item active">Batch Name<span class="pull-right">Permission</span></li>
    <?php 
            $dept_sel = "SELECT setup_batchmaster.* FROM `setup_batchmaster` JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' ";
            $check_sel = mysqli_query($mysqli,$dept_sel);
       
            while($row = mysqli_fetch_array($check_sel)){
    ?>
    
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <?php echo $row['batch_name']; ?>
        <span class="pull-right">
            <?php 
                $check_entry = "SELECT * FROM `comm_batch_access` where userId = '$user_id_edit' AND batchMaster_Id = '".$row['Id']."'";
                $q_entry = mysqli_query($mysqli,$check_entry);
                $row_check = mysqli_num_rows($q_entry); 
                if($row_check > 0){ ?>
                
                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" checked id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>"> 
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>     

                <?php } else {?>

                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>">
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>       
           
                <?php } ?>    
        </span>
    </li>
    
    
    <?php   }  ?> 

    </ul>
    </div>


    

    
    <div class="col-md-10">
        <button type="button" id="submit_editinstance" name="submit" class="btn btn-primary pull-right">Save Changes</button>
    </div>

</div>
</form>


<script>


//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

var EditData = $('#Edit_FormData').serializeArray();

$("#loader").css("display", "block");
$("#DisplayDiv").css("display", "none");

$.ajax({
    url:'./user_management/user_management_api.php?Batch_Access_Control_User='+'u',
    type:'POST',
    data: EditData,
    success:function(edit_instance_res){  
 
            iziToast.success({
                title: 'OK',
                message: 'Access Granted to the User Successfully!',
            });

            $("#loader").css("display", "none");
			$("#DisplayDiv").css("display", "block");
     

    },//Close Success
});// close Ajax 

});

//Instance Edit Submit Close----------------------------------------------------------------------------------------------------


function return_controlaccess() {
			$("#loader").css("display", "block");
	$("#DisplayDiv").css("display", "none");	
						jQuery.ajax({
        url: './user_management/homescreen.php',
        type: 'GET',
        dataType : 'html',
        success: function(response, textStatus, jqXHR){
      $('#DisplayDiv').html(response);
			$("#loader").css("display", "none");
	$("#DisplayDiv").css("display", "block");
    },
        error: function(xhr, textStatus, errorThrown) {
           // console.log();
			$('#DisplayDiv').html(textStatus.reponseText);
			$("#loader").css("display", "none");
			$("#DisplayDiv").css("display", "block");
        }
    });
					
				}

</script>