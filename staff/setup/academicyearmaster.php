<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


$q = "SELECT setup_academicyear.* FROM setup_academicyear Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' Order By sequence_no Desc";

?>



<div class="container">

    <div class="row">
        <div class="col-md-6"><h3 style="font-weight:bold;font-style:italic;" class="font_all"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i> Academic Year Master</h3>
        </div>   
    </div>


    <div class="row">
        <div class="col-md-8">
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
                                    <th>Academic Year</th>
                                    <th>Abbrivation</th>
                                    <th>Start Date (YYYY-MM_DD) Convert to Text Format then Upload</th>
                                    <th>End Date  (YYYY-MM_DD) Convert to Text Format then Upload</th>
                                    <th>Sequence No</th>
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
                        <th>Academic Year</th>
                        <th>Abbrivation</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Sequence No</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $instance_fetch_q = mysqli_query($mysqli,$q);
                    $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <?php if($r_instance_fetch['isDefault'] == '1'){ ?>  
                                    <span  style="color:#33b5e5;"> Currently Selected</span><br>
                                <?php } ?>
                                <?php echo $r_instance_fetch['academic_year']; ?>
                            </td>
                            <td><?php echo $r_instance_fetch['abbreviation']; ?></td>
                            <td><?php echo $r_instance_fetch['start_date']; ?></td>
                            <td><?php echo $r_instance_fetch['end_date']; ?></td>
                            <td><?php echo $r_instance_fetch['sequence_no']; ?></td>
                        
                            <td>
             
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="display:flex">
                               
                               
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default delete_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Delete Academic" data-toggle="tooltip"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#ff3547;"></span></button>
                                </div>


                                <div class="btn-group" role="group">
                                    <a><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Academic" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;"></span></button></a>
                                </div>


                                <?php if($r_instance_fetch['isDefault'] == '0'){  ?>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default Default_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Make this Default Entry" data-toggle="tooltip"><span class="fa fa-check" aria-hidden="true" style="color:#f5c601;"></span></button>
                                    </div>
                                <?php } ?>

                                
                                <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                                <input type="hidden"  value="<?php echo $r_instance_fetch['academic_year']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_academic_year">
                                <input type="hidden" value="<?php echo $r_instance_fetch['abbreviation']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_abbreviation">
                                <input type="hidden" value="<?php echo $r_instance_fetch['start_date']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_start_date">
                                <input type="hidden" value="<?php echo $r_instance_fetch['sequence_no']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_sequence_no">
                                <input type="hidden" value="<?php echo $r_instance_fetch['end_date']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_end_date">
                        
                        
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
                    Add New Academic Year
                    </a>
                </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="email">Academic Year*  </label>
                        <input type="text" class="form-control" id="add_academic_year" name="add_academic_year" placeholder="Enter Department Name">
                        <br>
                        <label for="email">Abbrivation*  </label>
                        <input type="text" class="form-control" id="add_abbreviation" name="add_abbreviation" placeholder="Enter abbreviation">
                        <br>
                        <label for="email">Start Date*  </label>
                        <input type="text" class="form-control" id="add_start_date" name="add_start_date" placeholder="Enter start_date">
                        <br>
                        <label for="email">End Date*  </label>
                        <input type="text" class="form-control" id="add_end_date" name="add_end_date" placeholder="Enter end_date">
                        <br>
                        <label for="email">Sequence No*  </label>
                        <input type="text" class="form-control" id="add_sequence_no" name="add_sequence_no" placeholder="Enter Sequence No">
                       
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
                    Edit Academic Year
                    </a>
                </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <form id="Edit_FormData">
                <div class="panel-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     
                        <label for="email">Academic Year*  </label>
                        <input type="text" class="form-control" id="edit_academic_year" name="edit_academic_year" placeholder="Enter Department Name">
                        <br>
                        <label for="email">Abbrivation*  </label>
                        <input type="text" class="form-control" id="edit_abbreviation" name="edit_abbreviation" placeholder="Enter abbreviation">
                        <br>
                        <label for="email">Start Date*  </label>
                        <input type="text" class="form-control" id="edit_start_date" name="edit_start_date" placeholder="Enter start_date">
                        <br>
                        <label for="email">End Date*  </label>
                        <input type="text" class="form-control" id="edit_end_date" name="edit_end_date" placeholder="Enter end_date">
                        <br>
                        <label for="email">Sequence No*  </label>
                        <input type="text" class="form-control" id="edit_sequence_no" name="edit_sequence_no" placeholder="Enter Sequence No">
       
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

$('#edit_start_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});

$('#edit_end_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});

$('#add_start_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});

$('#add_end_date').datepicker({
    format: 'yyyy-mm-dd',
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
    var fetch_Edited_academic_year = createURL.searchParams.get('fetch_edit_academic_year');
    var fetch_Edited_abbreviation = createURL.searchParams.get('fetch_edit_abbreviation');
    var fetch_Edited_start_date = createURL.searchParams.get('fetch_edit_start_date');
    var fetch_Edited_end_date = createURL.searchParams.get('fetch_edit_end_date');
    var fetch_Edited_sequence_no = createURL.searchParams.get('fetch_edit_sequence_no');

    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_academic_year').val(fetch_Edited_academic_year);
    $('#edit_abbreviation').val(fetch_Edited_abbreviation);
    $('#edit_start_date').val(fetch_Edited_start_date);
    $('#edit_end_date').val(fetch_Edited_end_date);
    $('#edit_sequence_no').val(fetch_Edited_sequence_no);

});
//Instance Edit Close----------------------------------------------------------------------------------------------------




//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

var EditData = $('#Edit_FormData').serializeArray();

$("#loader").css("display", "block");
$("#DisplayDiv").css("display", "none");

$.ajax({
    url:'./setup/setup_api.php?Edit_AcademicYearInstance='+'u',
    type:'POST',
    data: EditData,
    dataType: "json",
    success:function(edit_instance_res){  
        if(edit_instance_res == '200'){
            $.ajax({
                url:'./setup/academicyearmaster.php',
                type:'GET',
                success:function(sd_logs){
                    $('#DisplayDiv').html(sd_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                    iziToast.success({
                        title: 'Success',
                        message: 'Academic Year Edited',
                    });
                },
            });   

        }else{

            $.ajax({
                url:'./setup/academicyearmaster.php',
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

//Instance Delete----------------------------------------------------------------------------------------------------
$('.delete_instance_btn').click(function(event){
    var delete_instance_Id = $(this).attr('id');
    if (confirm('Are you sure you want to Delete Existing Academic Year?')) {
        $.ajax({
            url:'./setup/setup_api.php?Delete_AcademicYearInstance='+'u',
            type: 'POST',
            data: {delete_instance_Id:delete_instance_Id},
            success:function(del_msg){
                if(del_msg == '200'){
                    
                    $.ajax({
                        url:'./setup/academicyearmaster.php',
                        type:'GET',
                        success:function(st_logs){
                            $('#DisplayDiv').html(st_logs);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.success({
                                title: 'Success',
                                message: 'Academic Year Deleted',
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
   var add_academic_year = $('#add_academic_year').val();
   var add_abbreviation = $('#add_abbreviation').val();
   var add_start_date = $('#add_start_date').val();
   var add_end_date = $('#add_end_date').val();
   var add_sequence_no = $('#add_sequence_no').val();

    if(add_academic_year == '' || add_abbreviation == '' || add_start_date == '' || add_end_date == '' || add_sequence_no == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'All fields is mandatory',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url:'./setup/setup_api.php?Add_AcademicYearInstance='+'u',
        type:'POST',
        data: {add_academic_year:add_academic_year, add_abbreviation:add_abbreviation, add_start_date:add_start_date, add_end_date:add_end_date, add_sequence_no:add_sequence_no},  
        dataType: "json",
        success:function(add_instance_res){  
            console.log(add_instance_res['success']);
            if(add_instance_res['status'] == 'success'){
                $.ajax({
                    url:'./setup/academicyearmaster.php',
                    type:'GET',
                    success:function(st_logs){
                        $('#DisplayDiv').html(st_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'Academic Year Added',
                        });
                    },
                });   

            }else if(add_instance_res['status'] == 'EXISTS'){
                       
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Duplicate',
                            message: 'Academic Year Already Exist Or Sequence No Already Exist',
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

    setTimeout(function(){
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        jQuery.ajax({
            url: './setup/setup_api.php?Add_AcademicYearInstance_InBulk=u',
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: data,
            dataType: "json",
            success: function (update_instance_res, textStatus, xhr) {
                
                     var UploadedFilePath = update_instance_res['UploadedFilePath'];
              
              
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
						url: './setup/academicyearmaster.php',
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

//Instance Delete----------------------------------------------------------------------------------------------------
$('.Default_instance_btn').click(function(event){
    var AY_Id = $(this).attr('id');

    Status_Txt = 'Are you sure you want to make this Academic Year Entry as Default?';
   

    if (confirm(Status_Txt)) {
        
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        
        $.ajax({
            url: './setup/setup_api.php?Change_AcademicYearInstance_DefaultEntry=u',
            type: 'POST',
            data: {AY_Id:AY_Id},
            success:function(del_msg){
                if(del_msg == '200'){
                    
                    $.ajax({
                        url: './setup/academicyearmaster.php',
                        type:'GET',
                        success:function(st_logs){
                            $('#DisplayDiv').html(st_logs);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.success({
                                title: 'Success',
                                message: 'You have Successfully Changed Default Entry',
                            });
                        },
                    });   

                }
            },
        });

    }

});
//Instance Delete Close----------------------------------------------------------------------------------------------------

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
