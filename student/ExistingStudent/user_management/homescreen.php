<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include_once '../../config/database.php';

$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

$q = "SELECT user_stafflogin.*, setup_sectionmaster.section_name FROM user_stafflogin  JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id Where setup_sectionmaster.Id = '$SectionMaster_Id'";


?>


<div class="container">

    <div class="row">
        <div class="col-md-6"><h3 style="font-weight:bold;font-style:italic;" class="font_all"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i> User Management</h3>
        </div>   
    </div>



				
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-controls="collapseFive">
                    Download Excel Format
                </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                        <table id="online_table" class="table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Mobile No</th>
                                    <th>Email Address</th>
                                    <th>Date Of Birth <br>(DD/MM/YYYY) <br> (Convert in Text Format before Upload)</th>
                                    <th>Staff Type <br> (1: Admin, 0: Staff)</th>
                                    <th>Staff Login Status <br> (1: Active, 0: Disable)</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
            </div> <!--Close Panel -->
        </div>	

        <div class="col-md-4">
            <a onclick="$('#contact_dialog').modal('show');" class="btn btn-default">
                    <i class="fa fa-upload" aria-hidden="true"></i> Import
            </a>
        </div>


        
        <div class="DetailsDisplay">
        <div class="modal fade" id="contact_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import File</h4>
                    </div>
                    <div class="modal-body" style="padding-bottom:10%">
                        <form id="import_file_form" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-4 input-md control-label" for="name">Select an excel file </label>
                                    <div class="col-md-4">
                                        <input type="file" name="upload_file">
                                    
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" id="import_file_submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>



    <div class="row">
    
       <div class="col-md-12"> 

        <div class="col-md-9">
            <form id="All_instance_form">
            <table class="table table-striped" id="InstanceMaster_Table">
                <thead>
                    
                    <tr>
                        <th style="width:8%">Sr No.</th>
                        <th>Username</th>
                        <th>Mobile No</th>
                        <th>Email Address</th>
                        <th>Date Of Birth</th>
                        <th>Section</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $instance_fetch_q = mysqli_query($mysqli,$q);
                    $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $r_instance_fetch['username']; ?></td>
                            <td><?php echo $r_instance_fetch['reg_mobile_no']; ?></td>
                            <td><?php echo $r_instance_fetch['reg_email_address']; ?></td>
                            <td><?php echo $r_instance_fetch['date_of_birth']; ?></td>
                            <td><?php echo $r_instance_fetch['section_name']; ?></td>
                            <td>
             
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="display:flex">
                               
                               
            
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default menuAccessLink"
                                    data-toggle="tooltip" data-placement="top" title="Menu Access" id="<?php echo $r_instance_fetch['Id']; ?>"><span class="glyphicon glyphicon-tasks" aria-hidden="true" style="color:#fbb536;"></span></button>
                                </div>

                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default batchAccessLink"
                                    data-toggle="tooltip" data-placement="top" title="Batch Access" id="<?php echo $r_instance_fetch['Id']; ?>"><span class="glyphicon glyphicon-tasks" aria-hidden="true" style="color:#fa573c;"></span></button>
                                </div>

                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default departAccessLink"
                                    data-toggle="tooltip" data-placement="top" title="Department Access" id="<?php echo $r_instance_fetch['Id']; ?>"><span class="glyphicon glyphicon-tasks" aria-hidden="true" style="color:#f9d2a0;"></span></button>
                                </div>


                                <div class="btn-group" role="group">
                                    <a><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Staff Details" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;"></span></button></a>
                                </div>


                                
                                <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['username']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_username">
                                <input type="hidden" value="<?php echo $r_instance_fetch['reg_mobile_no']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_reg_mobile_no">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['reg_email_address']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_reg_email_address">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['date_of_birth']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_date_of_birth">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['staff_type']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_staff_type">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['staff_status']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_staff_status">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['departmentmaster_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_departmentmaster_Id">
                            
                            </div>

                           

                            </td>
                        </tr>



                    <?php $i++; } ?>

                </tbody>
            </table>
            </form>
        </div> <!--Col Close-->

     
        
        <div class = "col-md-3">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<!------------------------------------------------------------------------------------------------------------------------->
            <div class="panel panel-default InstanceCreate_Model">
                <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Add New Staff
                    </a>
                </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="email">Username*  </label>
                        <input type="text" class="form-control" id="add_username" name="add_username" placeholder="Enter Username">
                        <br>
                        <label for="email">Password*  </label>
                        <input type="password" class="form-control" id="add_password" name="add_password" placeholder="Enter Password">
                        <br>
                        <label for="email">Mobile No*  </label>
                        <input type="text" class="form-control" id="add_reg_mobile_no" name="add_reg_mobile_no" placeholder="Enter Mobile No">
                        <br>
                        <label for="email">Email Address*  </label>
                        <input type="text" class="form-control" id="add_reg_email_address" name="add_reg_email_address" placeholder="Enter Email Address">
                        <br>
                        <label for="email">Date Of Birth*  </label>
                        <input type="text" class="form-control" id="add_date_of_birth" name="add_date_of_birth" placeholder="Enter DOB">
                        <br>
                        <label for="email">Staff Type*  </label>
                        <select class="form-control design_sel" name="add_staff_type" id="add_staff_type" required>
                            <option value=''>Select</option>
                            <option value="1">Admin</option>
                            <option value="0">Staff</option>
                        </select>
                        <br>
                        <label for="email">Staff Login Status*  </label>
                        <select class="form-control design_sel" name="add_staff_status" id="add_staff_status" required>
                            <option value=''>Select</option>
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                        <br>
                        <label for="email">Department*  </label>
                        <select name="add_departmentmaster_Id" id="add_departmentmaster_Id" class="form-control design_sel" required>
                                <option value="">Select Department</option>
                                <?php 
                                    $depart_fetch = mysqli_query($mysqli,"SELECT setup_departmentmaster.* FROM setup_departmentmaster WHERE setup_departmentmaster.sectionmaster_Id = '$SectionMaster_Id' ");
                                    while($r_depart = mysqli_fetch_array($depart_fetch)){ ?>
                                    <option value="<?php echo $r_depart['Id']; ?>"><?php echo $r_depart['department_name']; ?></option>
                                <?php }   ?>
                        </select>

                    </div>
                </div>
                <div class="panel-footer">
                     <input type="submit" name="submit_addinstance" value="Save Changes" id="submit_addinstance" class="btn btn-primary" >  
                </div>

                </div>
            </div>
<!------------------------------------------------------------------------------------------------------------------------->           
<!------------------------------------------------------------------------------------------------------------------------->
            <div class="panel panel-default EventSelect_Model" id="Edit_Scroll">
                <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                <button type="button" class="close add_instance"><span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#3c8dbc;"></span></button>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Edit Staff
                    </a>
                </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <form id="Edit_FormData">
                <div class="panel-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     
                        <label for="email">Username*  </label>
                        <input type="text" class="form-control" id="edit_username" name="edit_username" placeholder="Enter Username">
                        <br>
                        <label for="email">Mobile No*  </label>
                        <input type="text" class="form-control" id="edit_reg_mobile_no" name="edit_reg_mobile_no" placeholder="Enter Mobile No">
                        <br>
                        <label for="email">Email Address*  </label>
                        <input type="text" class="form-control" id="edit_reg_email_address" name="edit_reg_email_address" placeholder="Enter Email Address">
                        <br>
                        <label for="email">Date Of Birth*  </label>
                        <input type="text" class="form-control" id="edit_date_of_birth" name="edit_date_of_birth" placeholder="Enter DOB">
                        <br>
                        <label for="email">Staff Type*  </label>
                        <select class="form-control design_sel" name="edit_staff_type" id="edit_staff_type" required>
                            <option value=''>Select</option>
                            <option value="1">Admin</option>
                            <option value="0">Staff</option>
                        </select>
                        <br>
                        <label for="email">Staff Login Status*  </label>
                        <select class="form-control design_sel" name="edit_staff_status" id="edit_staff_status" required>
                            <option value=''>Select</option>
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                        <br>
                        <label for="email">Department*  </label>
                        <select name="edit_departmentmaster_Id" id="edit_departmentmaster_Id" class="form-control design_sel" required>
                                <option value="">Select Department</option>
                                <?php 
                                    $depart_fetch = mysqli_query($mysqli,"SELECT setup_departmentmaster.* FROM setup_departmentmaster WHERE setup_departmentmaster.sectionmaster_Id = '$SectionMaster_Id' ");
                                    while($r_depart = mysqli_fetch_array($depart_fetch)){ ?>
                                    <option value="<?php echo $r_depart['Id']; ?>"><?php echo $r_depart['department_name']; ?></option>
                                <?php }   ?>
                        </select>

                    </div>
                </div>
                </form>
                <div class="panel-footer">
                     <input type="submit" name="submit_editinstance" value="Save Changes" id="submit_editinstance" class="btn btn-primary">  
                </div>
                </div>
            </div>

<!------------------------------------------------------------------------------------------------------------------------->
        </div>
        </div> <!--Close Col-->

      </div> <!--Div Wrap Close-->

    </div><!--Row Close-->

</div><!--Close Container-->

   

<script>

$('#edit_date_of_birth').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});

$('#add_date_of_birth').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});

$('div').removeClass("modal-backdrop");
$('.InstanceCreate_Model').collapse('show');
$('.EventSelect_Model').hide();


$('.add_instance').click(function(event){

    $('.EventSelect_Model').hide();
    $('.InstanceCreate_Model').collapse('show');

});



//Instance Edit----------------------------------------------------------------------------------------------------
$('.edit_instance_btn').click(function(event){
    var edit_instance_Id = $(this).attr('id');
    $('.EventSelect_Model').show();
    $('.InstanceCreate_Model').collapse('hide');

    //Disabled All Checkbox 
    $(".all_fields").prop('disabled', true);  

    //Enable Selected one
    $('.' + edit_instance_Id).prop('disabled', false);  

    var Edit_Form = $('#All_instance_form').serialize();

    //Creating Fake Url And Appending Data
    var fakeURL = "http://www.example.com/t.html?" +  Edit_Form;
    var createURL = new URL(fakeURL)


    var fetch_Edited_Id = createURL.searchParams.get('fetch_edit_Id');
    var fetch_Edited_username = createURL.searchParams.get('fetch_edit_username');
    var fetch_Edited_reg_mobile_no = createURL.searchParams.get('fetch_edit_reg_mobile_no');
    var fetch_Edited_reg_email_address = createURL.searchParams.get('fetch_edit_reg_email_address');
    var fetch_Edited_staff_type = createURL.searchParams.get('fetch_edit_staff_type');
    var fetch_Edited_staff_status = createURL.searchParams.get('fetch_edit_staff_status');
    var fetch_Edited_departmentmaster_Id = createURL.searchParams.get('fetch_edit_departmentmaster_Id');
    var fetch_Edited_date_of_birth = createURL.searchParams.get('fetch_edit_date_of_birth');


    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_username').val(fetch_Edited_username);
    $('#edit_reg_mobile_no').val(fetch_Edited_reg_mobile_no);
    $('#edit_reg_email_address').val(fetch_Edited_reg_email_address);
    $('#edit_staff_type').val(fetch_Edited_staff_type);
    $('#edit_staff_status').val(fetch_Edited_staff_status);
    $('#edit_departmentmaster_Id').val(fetch_Edited_departmentmaster_Id);
    $('#edit_date_of_birth').val(fetch_Edited_date_of_birth);

});
//Instance Edit Close----------------------------------------------------------------------------------------------------




//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

var EditData = $('#Edit_FormData').serializeArray();

$("#loader").css("display", "block");
$("#DisplayDiv").css("display", "none");

$.ajax({
    url:'./user_management/user_management_api.php?Edit_USERInstance='+'u',
    type:'POST',
    data: EditData,
    dataType: "json",
    success:function(edit_instance_res){  
        if(edit_instance_res == '200'){
            $.ajax({
                url:'./user_management/homescreen.php',
                type:'GET',
                success:function(sd_logs){
                    $('#DisplayDiv').html(sd_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                    iziToast.success({
                        title: 'Success',
                        message: 'User Edited',
                    });
                },
            });   

        }else{

            $.ajax({
                url:'./user_management/homescreen.php',
                type:'GET',
                success:function(sd_logs){
                    $('#DisplayDiv').html(sd_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                    iziToast.error({
                        title: 'Invalid Input All field is mandatory',
                        message: 'Enter All Details',
                    });
                },
            });  

        }

    },//Close Success
});// close Ajax 

});

//Instance Edit Submit Close----------------------------------------------------------------------------------------------------

//INSTANCE ADD-----------------------------------------------------------------------------------------------------------

$('#submit_addinstance').click(function(event){
   var add_username = $('#add_username').val();
   var add_password = $('#add_password').val();
   var add_reg_mobile_no = $('#add_reg_mobile_no').val();
   var add_reg_email_address = $('#add_reg_email_address').val();
   var add_date_of_birth = $('#add_date_of_birth').val();
   var add_staff_type = $('#add_staff_type').val();
   var add_staff_status = $('#add_staff_status').val();
   var add_departmentmaster_Id = $('#add_departmentmaster_Id').val();

    if(add_username == '' || add_password == '' || add_reg_mobile_no == '' || add_reg_email_address == '' || add_date_of_birth == '' || add_staff_type == '' || add_staff_status == '', add_departmentmaster_Id == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'All fields is mandatory',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url:'./user_management/user_management_api.php?Add_USERInstance='+'u',
        type:'POST',
        data: {add_username:add_username,add_password:add_password,add_reg_mobile_no:add_reg_mobile_no,add_reg_email_address:add_reg_email_address, add_date_of_birth:add_date_of_birth, add_staff_type:add_staff_type, add_staff_status:add_staff_status, add_departmentmaster_Id:add_departmentmaster_Id},
        dataType: "json",
        success:function(add_instance_res){  
            if(add_instance_res == '200'){
                $.ajax({
                    url:'./user_management/homescreen.php',
                    type:'GET',
                    success:function(st_logs){
                        $('#DisplayDiv').html(st_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'User Added',
                        });
                    },
                });   

            }

        },//Close Success
    });// close Ajax 

});

//Instance Add Close----------------------------------------------------------------------------------------------------

//Manage Instance Btn----------------------------------------------------------------------------------------------------------
$('.menuAccessLink').click(function(event){
    var selected_instance_Id = $(this).attr('id');
    $.ajax({
        url:'./user_management/page_access.php?user_stafflogin_id='+ selected_instance_Id,
        type:'GET',
        success:function(si_logs){
            $('#DisplayDiv').html(si_logs);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });  

});
//Manage Instance Btn close----------------------------------------------------------------------------------------------------------

//Manage Instance Btn----------------------------------------------------------------------------------------------------------
$('.batchAccessLink').click(function(event){
    var selected_instance_Id = $(this).attr('id');
    $.ajax({
        url:'./user_management/control_batch_access.php',
        type:'GET',
        success:function(si_logs){
            $('#DisplayDiv').html(si_logs);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });  

});
//Manage Instance Btn close----------------------------------------------------------------------------------------------------------




$('#import_file_submit').on('click', function(event){
    event.preventDefault();

    $('#contact_dialog').modal('hide');
    let form = $('#import_file_form')[0];
    let data = new FormData(form);

    setTimeout(function(){
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        jQuery.ajax({
            url: './user_management/user_management_api.php?Add_USERInstance_InBulk=u',
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: data,
            dataType: "json",
            success: function (update_instance_res, textStatus, xhr) {
                
   
              
                    var i = 0;
                    var successString = '';
                    
            

                    update_instance_res['displayMessage'].forEach(function(entry) {
            
                            successString = successString.concat( update_instance_res['displayMessage'][i], "</br>");

                        i++;

                    });

                    if(successString != ''){
            
                        // getAddAlert(successString, data, false, '');
						iziToast.success({
							title: 'Update Log',
							message: successString,
						});
            
                    }



					jQuery.ajax({
						url: './user_management/homescreen.php',
						type: "GET",
						success:function(data){
							$('#DisplayDiv').html(data);
							$("#loader").css("display", "none");
							$("#DisplayDiv").css("display", "block");
							}
					});


            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    
    }, 180);
});



$('#online_table').DataTable( {
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


</script>

<style>
.font_all{
    font-family: 'Josefin Sans', sans-serif;
}

input[type=text]{
border:0px;
border-bottom: 2px solid #3c8dbc;
}

.design_btn{
    background-color:white;
    color: black;
    border:2px solid #3c8dbc;

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

.detailsinput{
    border-bottom: 2px solid #3c8dbc;
    font-weight: bold;
}


.design_sel{
    height: 34px;
    border:0px;
    background-color: transparent;
    text-align: center;
    border-bottom: 2px solid #3c8dbc;
    -webkit-appearance: none;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: 1ex;
    background-origin: content-box;
    background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIgogICB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiCiAgIHhtbG5zOnN2Zz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgdmVyc2lvbj0iMS4xIgogICBpZD0ic3ZnMiIKICAgdmlld0JveD0iMCAwIDM1Ljk3MDk4MyAyMy4wOTE1MTgiCiAgIGhlaWdodD0iNi41MTY5Mzk2bW0iCiAgIHdpZHRoPSIxMC4xNTE4MTFtbSI+CiAgPGRlZnMKICAgICBpZD0iZGVmczQiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAyLjAxNDUxLC00MDcuMTIyMjUpIgogICAgIGlkPSJsYXllcjEiPgogICAgPHRleHQKICAgICAgIGlkPSJ0ZXh0MzMzNiIKICAgICAgIHk9IjYyOS41MDUwNyIKICAgICAgIHg9IjI5MS40Mjg1NiIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDpub3JtYWw7Zm9udC1zaXplOjQwcHg7bGluZS1oZWlnaHQ6MTI1JTtmb250LWZhbWlseTpzYW5zLXNlcmlmO2xldHRlci1zcGFjaW5nOjBweDt3b3JkLXNwYWNpbmc6MHB4O2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MXB4O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICB4bWw6c3BhY2U9InByZXNlcnZlIj48dHNwYW4KICAgICAgICAgeT0iNjI5LjUwNTA3IgogICAgICAgICB4PSIyOTEuNDI4NTYiCiAgICAgICAgIGlkPSJ0c3BhbjMzMzgiPjwvdHNwYW4+PC90ZXh0PgogICAgPGcKICAgICAgIGlkPSJ0ZXh0MzM0MCIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTo0MHB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6Rm9udEF3ZXNvbWU7LWlua3NjYXBlLWZvbnQtc3BlY2lmaWNhdGlvbjpGb250QXdlc29tZTtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjFweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIj4KICAgICAgPHBhdGgKICAgICAgICAgaWQ9InBhdGgzMzQ1IgogICAgICAgICBzdHlsZT0iZmlsbDojMzMzMzMzO2ZpbGwtb3BhY2l0eToxIgogICAgICAgICBkPSJtIDIzNy41NjY5Niw0MTMuMjU1MDcgYyAwLjU1ODA0LC0wLjU1ODA0IDAuNTU4MDQsLTEuNDczMjIgMCwtMi4wMzEyNSBsIC0zLjcwNTM1LC0zLjY4MzA0IGMgLTAuNTU4MDQsLTAuNTU4MDQgLTEuNDUwOSwtMC41NTgwNCAtMi4wMDg5MywwIEwgMjIwLDQxOS4zOTM0NiAyMDguMTQ3MzIsNDA3LjU0MDc4IGMgLTAuNTU4MDMsLTAuNTU4MDQgLTEuNDUwODksLTAuNTU4MDQgLTIuMDA4OTMsMCBsIC0zLjcwNTM1LDMuNjgzMDQgYyAtMC41NTgwNCwwLjU1ODAzIC0wLjU1ODA0LDEuNDczMjEgMCwyLjAzMTI1IGwgMTYuNTYyNSwxNi41NDAxNyBjIDAuNTU4MDMsMC41NTgwNCAxLjQ1MDg5LDAuNTU4MDQgMi4wMDg5MiwwIGwgMTYuNTYyNSwtMTYuNTQwMTcgeiIgLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=");
}

.design_sel::-ms-expand {
	display: none;
}

.Update_Link {
    font-weight:bold;
    text-decoration:underline;
    cursor:pointer;	
    text-shadow: 1px 1px 1px #337ab7;
}
.Update_Link:hover{
    font-size:13px;
}

.fixed-padding {
  padding-right: 0px !important;
}

table.dataTable thead th {
  border-bottom: 0;
}
table.dataTable tfoot th {
  border-top: 0;
}

.return_btn{
    box-shadow: 0px 17px 10px -10px rgba(0,0,0,0.4);
    transition: all ease-in-out 300ms;
    color: #f44336;
    cursor: pointer;
}

.return_btn:hover {
  box-shadow: 0px 37px 20px -15px rgba(0,0,0,0.2);
  transform: translate(0px, -10px);
}

input[type=password]{
border:0px;
border-bottom: 2px solid #3c8dbc;
}

</style>
