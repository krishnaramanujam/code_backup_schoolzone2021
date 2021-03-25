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

$q = "SELECT setup_sectionmaster.* FROM setup_sectionmaster Where setup_sectionmaster.Id = '$SectionMaster_Id'";


?>


<div class="container-fuild">

    <div class="row">
        <div class="col-md-6"><h3 style="font-weight:bold;font-style:italic;" class="font_all"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i> Section Master</h3>
        </div>   
    </div>




    <div class="row">
    
       <div class="col-md-12"> 

        <div class="col-md-12">
            <form id="All_instance_form">
            <table class="table table-striped" id="InstanceMaster_Table" style="width:100%;">
                <thead>

                    <tr>
                        <th colspan="17">   <button type="button" class="btn btn-link" data-toggle="modal" data-target="#myAddModal"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add New Section Entry</button> </th>
                    </tr>
                    
                    <tr>
                        <th>Sr No.</th>
                        <th>Logo</th>
                        <th>section Name</th>
                        <th>Abbreviation</th>
                        <th>Contact No</th>
                        <th>Login Status</th>


                        <th>School Type</th>
                        <th>Board</th>
                        <th>Address</th>
                        <th>Principal</th>
                        <th>Principal Contact No</th>
                        <th>Principal Mobile</th>
                        <th>Principal Email</th>
                        <th>Website</th>
                        <th>Udise No</th>
                        <th>Funding Type</th>


                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $instance_fetch_q = mysqli_query($mysqli,$q);
                    $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  

                        if($r_instance_fetch['open_login'] == '1'){$LoginStatus = 'Active';}else{$LoginStatus = 'Disabled';}
                    ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $r_instance_fetch['username']; ?></td>
                            <td><?php echo $r_instance_fetch['section_name']; ?></td>
                            <td><?php echo $r_instance_fetch['abbreviation']; ?></td>
                            <td><?php echo $r_instance_fetch['contact_no']; ?></td>
                            <td><?php echo $LoginStatus; ?></td>


                            <td><?php echo $r_instance_fetch['school_type']; ?></td>
                            <td><?php echo $r_instance_fetch['board']; ?></td>
                            <td><?php echo $r_instance_fetch['address']; ?></td>
                            <td><?php echo $r_instance_fetch['principal']; ?></td>
                            <td><?php echo $r_instance_fetch['principal_contact_no']; ?></td>
                            <td><?php echo $r_instance_fetch['principal_mobile']; ?></td>
                            <td><?php echo $r_instance_fetch['principal_email']; ?></td>
                            <td><?php echo $r_instance_fetch['website']; ?></td>
                            <td><?php echo $r_instance_fetch['udise_no']; ?></td>
                            <td><?php echo $r_instance_fetch['funding_type']; ?></td>
                     

                            <td>
             
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="display:flex">
                               
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default delete_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Delete Section Details" data-toggle="tooltip"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#ff3547;"></span></button>
                                </div>


                                <div class="btn-group" role="group">
                                    <a><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Section Details" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;" data-toggle="modal" data-target="#myEditModal"></span></button></a>
                                </div>


                                
                                <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['section_name']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_section_name">
                                <input type="hidden" value="<?php echo $r_instance_fetch['abbreviation']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_abbreviation">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['school_type']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_school_type">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['board']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_board">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['address']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_address">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['contact_no']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_contact_no">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['principal']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_principal">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['principal_contact_no']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_principal_contact_no">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['principal_mobile']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_principal_mobile">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['principal_email']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_principal_email">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['website']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_website">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['udise_no']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_udise_no">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['funding_type']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_funding_type">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['open_login']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_open_login">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['maintenance_message']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_maintenance_message">
                            
                            </div>

                           

                            </td>
                        </tr>



                    <?php $i++; unset($LoginStatus); } ?>

                </tbody>
            </table>
            </form>
        </div> <!--Col Close-->

     
     
      </div> <!--Div Wrap Close-->

    </div><!--Row Close-->

</div><!--Close Container-->



   
  <!-- Add Modal -->
  <div class="modal fade" id="myAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Add Section Entry</h4>
        </div>
        <div class="modal-body panel-body">
            <div class="form-group">
                <label for="email">Section Name*  </label>
                <input type="text" class="form-control" id="add_section_name" name="add_section_name" placeholder="Enter Section Name">
                <br>
                <label for="email">Abbreviation*  </label>
                <input type="text" class="form-control" id="add_abbreviation" name="add_abbreviation" placeholder="Enter Abbreviation">
                <br>
                <label for="email">School Type*  </label>
                <input type="text" class="form-control" id="add_school_type" name="add_school_type" placeholder="Enter School Type">
                <br>
                <label for="email">Board*  </label>
                <input type="text" class="form-control" id="add_board" name="add_board" placeholder="Enter board">
                <br>
                <label for="email">Address*  </label>
                <input type="text" class="form-control" id="add_address" name="add_address" placeholder="Enter Address">
                <br>
                <label for="email">Contact No*  </label>
                <input type="text" class="form-control" id="add_contact_no" name="add_contact_no" placeholder="Enter Contact No">
                <br>
                <label for="email">Principal*  </label>
                <input type="text" class="form-control" id="add_principal" name="add_principal" placeholder="Enter principal">
                <br>
                <label for="email">Principal Contact No*  </label>
                <input type="text" class="form-control" id="add_principal_contact_no" name="add_principal_contact_no" placeholder="Enter Principal Contact No">
                <br>
                <label for="email">Principal Mobile*  </label>
                <input type="text" class="form-control" id="add_principal_mobile" name="add_principal_mobile" placeholder="Enter Principal Mobile">
                <br>
                <label for="email">Principal Email*  </label>
                <input type="text" class="form-control" id="add_principal_email" name="add_principal_email" placeholder="Enter Principal Email">
                <br>
                <label for="email">Website*  </label>
                <input type="text" class="form-control" id="add_website" name="add_website" placeholder="Enter Website">
                <br>
                <label for="email">Udise No*  </label>
                <input type="text" class="form-control" id="add_udise_no" name="add_udise_no" placeholder="Enter udise No">
                <br>
                <label for="email">Funding Type*  </label>
                <input type="text" class="form-control" id="add_funding_type" name="add_funding_type" placeholder="Enter Funding Type">
                <br>
                <label for="email">Maintenance Message*  </label>
                <input type="text" class="form-control" id="add_maintenance_message" name="add_maintenance_message" placeholder="Enter Maintenance Message">
                <br>
                <label for="email">Section Login Status*  </label>
                <select class="form-control design_sel" name="add_open_login" id="add_open_login" required>
                    <option value=''>Select</option>
                    <option value="1">Active</option>
                    <option value="0">Disabled</option>
                </select>
                <br>



            </div>  


        </div>
        <div class="modal-footer">
            <input type="submit" name="submit_addinstance" value="Save Changes" id="submit_addinstance" class="btn btn-primary" >  
        </div>
      </div>
    </div>
  </div>




   
  <!-- Edit Modal -->
  <div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Section Entry</h4>
        </div>
        <div class="modal-body panel-body">
            <div class="form-group">
            <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     
                     <label for="email">Section Name*  </label>
                     <input type="text" class="form-control" id="edit_section_name" name="edit_section_name" placeholder="Enter Section Name" required>
                     <br>
                     <label for="email">Abbreviation*  </label>
                     <input type="text" class="form-control" id="edit_abbreviation" name="edit_abbreviation" placeholder="Enter Abbreviation" required>
                     <br>
                     <label for="email">School Type*  </label>
                     <input type="text" class="form-control" id="edit_school_type" name="edit_school_type" placeholder="Enter School Type" required>
                     <br>
                     <label for="email">Board*  </label>
                     <input type="text" class="form-control" id="edit_board" name="edit_board" placeholder="Enter board" required>
                     <br>
                     <label for="email">Address*  </label>
                     <input type="text" class="form-control" id="edit_address" name="edit_address" placeholder="Enter Address" required>
                     <br>
                     <label for="email">Contact No*  </label>
                     <input type="text" class="form-control" id="edit_contact_no" name="edit_contact_no" placeholder="Enter Contact No" required>
                     <br>
                     <label for="email">Principal*  </label>
                     <input type="text" class="form-control" id="edit_principal" name="edit_principal" placeholder="Enter principal">
                     <br>
                     <label for="email">Principal Contact No*  </label>
                     <input type="text" class="form-control" id="edit_principal_contact_no" name="edit_principal_contact_no" placeholder="Enter Principal Contact No" required>
                     <br>
                     <label for="email">Principal Mobile*  </label>
                     <input type="text" class="form-control" id="edit_principal_mobile" name="edit_principal_mobile" placeholder="Enter Principal Mobile" required>
                     <br>
                     <label for="email">Principal Email*  </label>
                     <input type="text" class="form-control" id="edit_principal_email" name="edit_principal_email" placeholder="Enter Principal Email" required>
                     <br>
                     <label for="email">Website*  </label>
                     <input type="text" class="form-control" id="edit_website" name="edit_website" placeholder="Enter Website" required>
                     <br>
                     <label for="email">Udise No*  </label>
                     <input type="text" class="form-control" id="edit_udise_no" name="edit_udise_no" placeholder="Enter udise No" required>
                     <br>
                     <label for="email">Funding Type*  </label>
                     <input type="text" class="form-control" id="edit_funding_type" name="edit_funding_type" placeholder="Enter Funding Type" required>
                     <br>
                     <label for="email">Maintenance Message*  </label>
                     <input type="text" class="form-control" id="edit_maintenance_message" name="edit_maintenance_message" placeholder="Enter Maintenance Message" required>
                     <br>
                     <label for="email">Section Login Status*  </label>
                     <select class="form-control design_sel" name="edit_open_login" id="edit_open_login" required>
                         <option value=''>Select</option>
                         <option value="1">Active</option>
                         <option value="0">Disabled</option>
                     </select>
                     <br>



            </div>  


        </div>
        <div class="modal-footer">
        <input type="submit" name="submit_editinstance" value="Save Changes" id="submit_editinstance" class="btn btn-primary">  
        </div>
      </div>
    </div>
  </div>



<script>



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
    var fetch_Edited_section_name = createURL.searchParams.get('fetch_edit_section_name');
    var fetch_Edited_abbreviation = createURL.searchParams.get('fetch_edit_abbreviation');
    var fetch_Edited_school_type = createURL.searchParams.get('fetch_edit_school_type');
    var fetch_Edited_board = createURL.searchParams.get('fetch_edit_board');
    var fetch_Edited_address = createURL.searchParams.get('fetch_edit_address');
    var fetch_Edited_contact_no = createURL.searchParams.get('fetch_edit_contact_no');
    var fetch_Edited_principal = createURL.searchParams.get('fetch_edit_principal');
    var fetch_Edited_principal_contact_no = createURL.searchParams.get('fetch_edit_principal_contact_no');
    var fetch_Edited_principal_mobile = createURL.searchParams.get('fetch_edit_principal');
    var fetch_Edited_principal_email = createURL.searchParams.get('fetch_edit_principal_email');
    var fetch_Edited_website = createURL.searchParams.get('fetch_edit_website');
    var fetch_Edited_udise_no = createURL.searchParams.get('fetch_edit_udise_no');
    var fetch_Edited_funding_type = createURL.searchParams.get('fetch_edit_funding_type');
    var fetch_Edited_open_login = createURL.searchParams.get('fetch_edit_open_login');
    var fetch_Edited_maintenance_message = createURL.searchParams.get('fetch_edit_maintenance_message');


    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_section_name').val(fetch_Edited_section_name);
    $('#edit_abbreviation').val(fetch_Edited_abbreviation);
    $('#edit_board').val(fetch_Edited_board);
    $('#edit_school_type').val(fetch_Edited_school_type);
    $('#edit_address').val(fetch_Edited_address);
    $('#edit_contact_no').val(fetch_Edited_contact_no);
    $('#edit_principal').val(fetch_Edited_principal);
    $('#edit_principal_contact_no').val(fetch_Edited_principal_contact_no);
    $('#edit_principal_mobile').val(fetch_Edited_principal_mobile);
    $('#edit_principal_email').val(fetch_Edited_principal_email);
    $('#edit_website').val(fetch_Edited_website);
    $('#edit_udise_no').val(fetch_Edited_udise_no);
    $('#edit_funding_type').val(fetch_Edited_funding_type);
    $('#edit_open_login').val(fetch_Edited_open_login);
    $('#edit_maintenance_message').val(fetch_Edited_maintenance_message);


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


var table = $('#InstanceMaster_Table').DataTable( {
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
    },{
        extend: 'colvis',
		text: 'Column Visiblity',
    }
    ]
} );

table.columns( [ 6,7,8,9,10,11,12,13,14,15 ] ).visible( false, false );
table.columns.adjust().draw( false ); // adjust column sizing and redraw

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
