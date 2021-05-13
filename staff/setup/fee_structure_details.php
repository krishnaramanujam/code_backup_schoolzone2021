<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';
 $FS_Id = $_GET['FS_Id'];

  $q = "SELECT fee_structure_details.*, fee_structure_master.Fee_Structure_Name,setup_academicyear.abbreviation FROM `fee_structure_details` JOIN fee_structure_master ON fee_structure_master.Id = fee_structure_details.Fee_Structure_Id JOIN setup_academicyear ON setup_academicyear.Id = fee_structure_details.academicYearId WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' AND  fee_structure_details.academicYearId = '$Acadmic_Year_ID' AND fee_structure_details.Fee_Structure_Id  = '$FS_Id' ";

?>



<div class="container">

        
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b> Fee Structure Details Master </b></i></h4></div>




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
                                    <th>Name</th>
                                    <th>Abbreviation</th>
                                    <th>Payable Date  <br>(DD/MM/YYYY)</th>
                                    <th>Last Date  <br>(DD/MM/YYYY)</th>       
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
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
                            <input type="hidden" name="FS_Id" id="FS_Id" value="<?php echo $FS_Id; ?>">
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
                        <th>Fee Structure Name</th>
                        <th>Fee Structure Details Name</th>
                        <th>Abbreviation</th>
                        <th>Payable Date</th>
                        <th>Last Date </th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $instance_fetch_q = mysqli_query($mysqli,$q);
                    $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $r_instance_fetch['Fee_Structure_Name']; ?></td>
                            <td><?php echo $r_instance_fetch['Name']; ?></td>
                            <td><?php echo $r_instance_fetch['Abbreviation']; ?></td>
                            <td><?php echo date('d/m/Y',strtotime(str_replace('/','-',$r_instance_fetch['Payable_date']))); ?></td>
                            <td><?php echo date('d/m/Y',strtotime(str_replace('/','-',$r_instance_fetch['Last_Date']))); ?></td>
                           
                            <td>
             
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="display:flex">
                               
                                
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default delete_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Delete Fee Structure Details" data-toggle="tooltip"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#ff3547;"></span></button>
                                </div>


                                <div class="btn-group" role="group">
                                    <a><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Fee Structure Details" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;"></span></button></a>
                                </div>


                                
                                <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['Fee_Structure_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Fee_Structure_Id">
                                <input type="hidden" value="<?php echo $r_instance_fetch['Name']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Name">
                                <input type="hidden" value="<?php echo $r_instance_fetch['Abbreviation']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Abbreviation">
                                <input type="hidden" value="<?php echo $r_instance_fetch['Payable_date']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Payable_date">
                                <input type="hidden" value="<?php echo $r_instance_fetch['Last_Date']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Last_Date">
                                <input type="hidden" value="<?php echo $r_instance_fetch['academicYearId']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_academicYearId">
                        
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
                    Add New Fee Structure Details
                    </a>
                </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="email">Select Fee Structure Name*  </label>
                        <select class="form-control drop_sel" id="add_Fee_Structure_Id" name="add_Fee_Structure_Id"  readonly>
                        <?php
                                 $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' AND fee_structure_master.Id  = '$FS_Id' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Fee_Structure_Name']."</option>";
                                 }
                        ?>
                         </select>
                        <br>
                        <label for="email">Select Fee Structure Details Name*  </label>
                        <input type="text" class="form-control" id="add_Name" name="add_Name" placeholder="Enter Name">
                        <br>
                        <label for="email">Abbreviation*  </label>
                        <input type="text" class="form-control" id="add_Abbreviation" name="add_Abbreviation" placeholder="Enter Abbreviation">
                        <br>
                        <label for="email">Payable Date*  </label>
                        <input type="text" class="form-control" id="add_Payable_date" name="add_Payable_date" placeholder="Enter Payable Date">
                        <br>
                        <label for="email">Last Date*  </label>
                        <input type="text" class="form-control" id="add_Last_Date" name="add_Last_Date" placeholder="Enter Last Date">
                        <br>
                        <label for="email">Academic Year*  </label>
                        <select class="form-control drop_sel" id="add_academicYearId" name="add_academicYearId" readonly>
                        <?php
                                 $query_de = "SELECT  setup_academicyear.* FROM `setup_academicyear` WHERE  setup_academicyear.sectionmaster_Id = '$SectionMaster_Id'  AND setup_academicyear.Id ='$Acadmic_Year_ID' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['abbreviation']."</option>";
                                 }
                        ?>
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
                    Edit Fee Structure Details
                    </a>
                </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <form id="Edit_FormData">
                <div class="panel-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     
                        <label for="email">Select Fee Structure Name*  </label>
                        <select class="form-control drop_sel" id="edit_Fee_Structure_Id" name="edit_Fee_Structure_Id" readonly>
                        <?php
                                 $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' AND fee_structure_master.Id  = '$FS_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Fee_Structure_Name']."</option>";
                                 }
                        ?>
                         </select>
                        <br>
                        <label for="email">Select Fee Structure Details Name*  </label>
                        <input type="text" class="form-control" id="edit_Name" name="edit_Name" placeholder="Enter Name">
                        <br>
                        <label for="email">Abbreviation*  </label>
                        <input type="text" class="form-control" id="edit_Abbreviation" name="edit_Abbreviation" placeholder="Enter Abbreviation">
                        <br>
                        <label for="email">Payable Date*  </label>
                        <input type="text" class="form-control" id="edit_Payable_date" name="edit_Payable_date" placeholder="Enter Payable Date">
                        <br>
                        <label for="email">Last Date*  </label>
                        <input type="text" class="form-control" id="edit_Last_Date" name="edit_Last_Date" placeholder="Enter Last Date">
                        <br>
                        <label for="email">Academic Year*  </label>
                        <select class="form-control drop_sel" id="edit_academicYearId" name="edit_academicYearId" readonly  >
                        <?php
                                 $query_de = "SELECT  setup_academicyear.* FROM `setup_academicyear` WHERE  setup_academicyear.sectionmaster_Id = '$SectionMaster_Id'  AND setup_academicyear.Id ='$Acadmic_Year_ID'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['abbreviation']."</option>";
                                 }
                        ?>
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



$('#add_Payable_date').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});

$('#add_Last_Date').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});


$('#edit_Payable_date').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true
});

$('#edit_Last_Date').datepicker({
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




$('.return_btn').click(function (event) {
    event.preventDefault();
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
      url: './setup/fee_structure_master.php',
      type: 'GET',
      success: function (response) {
        $('#DisplayDiv').html(response);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      },
    });
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
    var fetch_Edited_Fee_Structure_Id = createURL.searchParams.get('fetch_edit_Fee_Structure_Id');
    var fetch_Edited_Name = createURL.searchParams.get('fetch_edit_Name');
    var fetch_Edited_Abbreviation = createURL.searchParams.get('fetch_edit_Abbreviation');
    var fetch_Edited_Payable_date = createURL.searchParams.get('fetch_edit_Payable_date');
    var fetch_Edited_Last_Date = createURL.searchParams.get('fetch_edit_Last_Date');
    var fetch_Edited_academicYearId = createURL.searchParams.get('fetch_edit_academicYearId');

    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_Fee_Structure_Id').val(fetch_Edited_Fee_Structure_Id);
    $('#edit_Name').val(fetch_Edited_Name);
    $('#edit_Abbreviation').val(fetch_Edited_Abbreviation);
    $('#edit_Payable_date').val(fetch_Edited_Payable_date);
    $('#edit_Last_Date').val(fetch_Edited_Last_Date);
    $('#edit_academicYearId').val(fetch_Edited_academicYearId);
});
//Instance Edit Close----------------------------------------------------------------------------------------------------




//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

var EditData = $('#Edit_FormData').serializeArray();

$("#loader").css("display", "block");
$("#DisplayDiv").css("display", "none");
var FS_Id = $('#FS_Id').val();

$.ajax({
    url:'./setup/setup_api.php?Edit_FeeDetailsStructureInstance='+'u',
    type:'POST',
    data: EditData,
    dataType: "json",
    success:function(edit_instance_res){  
        if(edit_instance_res == '200'){
            $.ajax({
                url:'./setup/fee_structure_details.php',
                type:'GET',
                data: {FS_Id:FS_Id},
                success:function(sd_logs){
                    $('#DisplayDiv').html(sd_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                    iziToast.success({
                        title: 'Success',
                        message: 'Fee Structure Details Edited',
                    });
                },
            });   

        }else{

            $.ajax({
                url:'./setup/fee_structure_details.php',
                type:'GET',
                data: {FS_Id:FS_Id},
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

//Instance Delete----------------------------------------------------------------------------------------------------
$('.delete_instance_btn').click(function(event){
    var delete_instance_Id = $(this).attr('id');
    var FS_Id = $('#FS_Id').val();
    if (confirm('Are you sure you want to Delete Existing bank?')) {
        $.ajax({
            url:'./setup/setup_api.php?Delete_FeeDetailsStructureInstance='+'u',
            type: 'POST',
            data: {delete_instance_Id:delete_instance_Id},
            success:function(del_msg){
                if(del_msg == '200'){
                    
                    $.ajax({
                        url:'./setup/fee_structure_details.php',
                        type:'GET',
                        data: {FS_Id:FS_Id},
                        success:function(st_logs){
                            $('#DisplayDiv').html(st_logs);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.success({
                                title: 'Success',
                                message: 'Fee Structure Details Deleted',
                            });
                        },
                    });   

                }
            },
        });
    }

});
//Instance Delete Close----------------------------------------------------------------------------------------------------


//INSTANCE ADD-----------------------------------------------------------------------------------------------------------

$('#submit_addinstance').click(function(event){
   var add_Fee_Structure_Id = $('#add_Fee_Structure_Id').val();
   var add_Name = $('#add_Name').val();
   var add_Abbreviation = $('#add_Abbreviation').val();
   var add_Payable_date = $('#add_Payable_date').val();
   var add_Last_Date = $('#add_Last_Date').val();
   var add_academicYearId = $('#add_academicYearId').val();

   var FS_Id = $('#FS_Id').val();

    if(add_Fee_Structure_Id == '' || add_Name == '' || add_Abbreviation == '' || add_Payable_date == '' || add_Last_Date == '' || add_academicYearId == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'All fields is mandatory',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url:'./setup/setup_api.php?Add_FeeDetailsStructureInstance='+'u',
        type:'POST',
        data: {add_Fee_Structure_Id:add_Fee_Structure_Id, add_Name:add_Name, add_Abbreviation:add_Abbreviation, add_Payable_date:add_Payable_date, add_Last_Date:add_Last_Date, add_academicYearId:add_academicYearId},
        dataType: "json",
        success:function(add_instance_res){  
            console.log(add_instance_res['success']);
            if(add_instance_res['status'] == 'success'){
                $.ajax({
                    url:'./setup/fee_structure_details.php',
                    type:'GET',
                    data: {FS_Id:FS_Id},
                    success:function(st_logs){
                        $('#DisplayDiv').html(st_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'Fee Structure Details Added',
                        });
                    },
                });   

            }else if(add_instance_res['status'] == 'EXISTS'){
                       
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Duplicate',
                            message: 'Fee Structure Details Already Exist',
                        });
            }

        },//Close Success
    });// close Ajax 

});

//Instance Add Close----------------------------------------------------------------------------------------------------

$('#InstanceMaster_Table').DataTable( {
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



$('#import_file_submit').on('click', function(event){
    event.preventDefault();

    $('#contact_dialog').modal('hide');
    let form = $('#import_file_form')[0];
    let data = new FormData(form);
    var FS_Id = $('#FS_Id').val();
    setTimeout(function(){
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        jQuery.ajax({
            url: './setup/setup_api.php?Add_FeeDetailsStructureInstance_InBulk=u',
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: data,
            dataType: "json",
            success: function (update_instance_res, textStatus, xhr) {
                var UploadedFilePath = update_instance_res['UploadedFilePath'];
              
              
                    if(UploadedFilePath === 'NA' || UploadedFilePath === ''){
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");

                        alert('Not able to Create the log')
                    }else if(UploadedFilePath != '' || UploadedFilePath != 'NA'){
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        window.open(UploadedFilePath, '_blank');
                    }


					jQuery.ajax({
						url: './setup/fee_structure_details.php',
						type: "GET",
                        data: {FS_Id:FS_Id},
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
