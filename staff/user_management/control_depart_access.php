<?php

session_start();
include_once '../../config/database.php';
$user_id_edit = $_POST['user_id_edit'];


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
?>


<form id= "Edit_FormData">
<div class="container">
    <div class="col-md-12"><h4><span class="badge btn btn-primary" onclick="return_controlaccess();"><i class="fa fa-arrow-left"></i></span><i><b>  DEPARTMENT ACCESS</b></i></h4></div>


    <div class="col-md-12">
         <input type="hidden" value="<?php echo $user_id_edit; ?>" id="user_id" name="user_id">
    </div>


    <div class="col-md-10">
    <table id="header_list" class="table table-bordered table-striped" style="width:100%">
        <thead>
        <tr>
          <th width="5%">Sr No</th>
          <th width="15%">Department Name</th>
          <th width="15%">Access Status</th>
          <th width="15%">Permission</th>
        </tr>
        </thead>    
        <tbody>

        <?php 
            $dept_sel = "select * from setup_departmentmaster Where sectionmaster_Id = '$SectionMaster_Id'";
            $check_sel = mysqli_query($mysqli,$dept_sel);
            $i = 1;
            while($row = mysqli_fetch_array($check_sel)){
    ?>
    
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['department_name']; ?></td>
            
               
                    <?php 
                    $check_entry = "SELECT * FROM `comm_batch_access` where userId = '$user_id_edit' AND batchMaster_Id = '".$row['Id']."'";
                    $q_entry = mysqli_query($mysqli,$check_entry);
                    $row_check = mysqli_num_rows($q_entry); 
                    if($row_check > 0){ ?>
                    <td>Activated</td>
                    <td>     
                        <input type="checkbox" class="form-check-label subcheck" checked id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>"> 
                    </td>

                    <?php } else { ?>
                    <td>Disabled</td>
                    <td>    
                        <input type="checkbox" class="form-check-label subcheck" id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>">
                    </td>
            
                    <?php } ?>    
                
            </tr>

        <?php  $i++; }  ?> 

        </body>
    </table>
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
    url:'./user_management/user_management_api.php?Department_Access_Control='+'u',
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



  
$('#header_list').DataTable( {
    dom: 'Bifrtp',
    bPaginate:false,
    
    buttons: [
    {
        extend: 'excel',
        footer: true,
		title: "Download Format",
		text: 'Download Format',
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

  $('#header_list').dataTable().yadcf([
    {column_number: 2, filter_match_mode: "exact"}
  ]);
</script>