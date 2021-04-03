<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

?>



<div class="container">

    <div class="row">
        <div class="col-md-11"><h3 style="font-weight:bold;font-style:italic;" class="font_all"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i>  Result Assessment Pattern Master</h3></div>   
    </div>


    <form id="FilterForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">program:</label>
                </div>
                
                <div class="col-md-2"> 
                    <select name="program_sel" id="program_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select program ----</option>
                                <?php 
                                
                                    $batch_fetch = mysqli_query($mysqli,"SELECT setup_programmaster.* FROM setup_programmaster JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' ");
                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['Id']; ?>"  
                                    <?php if($_GET['program_sel'] == $r_batch['Id']){ echo 'Selected'; } ?>
                                    ><?php echo $r_batch['program_name']; ?></option>
                                <?php }   ?>
                    </select>
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="submit" name="login" id="filter_result" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
        </form> 


<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){


        $program_sel = $_GET['program_sel'];

?>



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
                                    <th>Exam Header No(Use No From Exam Option)</th>
                                    <th>Course No(Use No From Course Option)</th>
                                    <th>Out of Marks</th>
                                    <th>Passing Marks</th>
                                    <th></th>
                                    <th>Please remove the below data before importing:</th>
                                    <th>Please remove the below data before importing:</th>
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
                                <td>Semester No</td>
                                <td>Semester Name</td>
                            </tr>

                            <?php
                                 $query_de = "SELECT result_exam_header.* FROM result_exam_header Where result_exam_header.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){  ?>

                                   <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $run_d['Id']; ?></td>
                                    <td><?php echo $run_d['Header_Name']; ?></td>
                                   </tr>

                                 
                                 <?php
                                 }
                            ?>


                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                
                                <tr>
                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Course No</td>
                                    <td>Course Name</td>
                                </tr>

                            <?php
                                 $query_de = "SELECT setup_coursemaster.* FROM `setup_coursemaster` WHERE setup_coursemaster.programmaster_Id = '$program_sel'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){  ?>

                                   <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $run_d['Id']; ?></td>
                                    <td><?php echo $run_d['course_name']; ?></td>
                                   </tr>

                                 
                                 <?php
                                 }
                            ?>
                                
                            </tbody>
                        </table>
                </div>
            </div>
            </div> <!--Close Panel -->
        </div>	

        <div class="col-md-2">
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
                            
                            <input type="hidden" name="program_sel_import" id="program_sel_import" val="">
                            
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
                 <tr><th>Sr No.</th><th style="text-align:left;">Exam Header Name</th><th>Course Name</th><th>Out of Marks</th><th>Passing Marks</th><th>Operations</th></tr>
             </thead>
             <tbody>
                 <?php  $instance_fetch_q = mysqli_query($mysqli,"SELECT result_assessment_pattern.*, setup_coursemaster.course_name, result_exam_header.Header_Name FROM result_assessment_pattern JOIN setup_coursemaster ON setup_coursemaster.Id = result_assessment_pattern.coursemaster_Id JOIN result_exam_header ON result_exam_header.Id = result_assessment_pattern.examheader_Id WHERE setup_coursemaster.programmaster_Id = '$program_sel'  ");


                 $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>
                     <tr> 
                         <td style="width:10%"><?php echo $i; ?></td>
                         <td style="width:20%;text-align:left;"><?php echo $r_instance_fetch['Header_Name']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['course_name']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['out_of_marks']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['passing_marks']; ?></td>
        
                         <td style="width:20%">
                         <div class="btn-group btn-group-xs" role="group" aria-label="...">
                            
                             <div class="btn-group" role="group">
                                 <a href="#Edit_Scroll"><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Result Assessment Instance" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;"></span></button></a>
                             </div>
                             <div class="btn-group" role="group">
                                 <button type="button" class="btn btn-default delete_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Delete Result Assessment Instance" data-toggle="tooltip"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#ff3547;"></span></button>
                             </div>
                             </div>

                             <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['examheader_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_examheader_Id">
                             <input type="hidden" value="<?php echo $r_instance_fetch['coursemaster_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_coursemaster_Id">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['out_of_marks']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_out_of_marks">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['passing_marks']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_passing_marks">
                           
                             <input type="hidden"  value="<?php echo $r_instance_fetch['programmaster_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_programmaster_Id">
                            
                            

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
                 Create New Result Assessment Instance
                 </a>
             </h4>
             </div>
             <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
             <div class="panel-body">
                 <div class="form-group">
                     <label for="email">Enter Exam Header Name*  </label>
                     <select class="form-control drop_sel" id="add_examheader_Id" name="add_examheader_Id">
                        <option value="">Select Exam Header</option>
                         <?php
                                 $query_de = "SELECT result_exam_header.* FROM result_exam_header Where result_exam_header.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Header_Name']."</option>";
                                 }
                         ?>

                     </select>
                     <br>
                     <label for="email">Enter Course Name*  </label>
                     <select class="form-control drop_sel" id="add_coursemaster_Id" name="add_coursemaster_Id">
                        <option value="">Select Course Name</option>
                         <?php
                                 $query_de = "SELECT setup_coursemaster.* FROM `setup_coursemaster` WHERE setup_coursemaster.programmaster_Id = '$program_sel'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['course_name']."</option>";
                                 }
                         ?>

                     </select>
                     <br>
                     <label for="email">Enter Out Of Marks*  </label>
                     <input type="text" class="form-control" id="add_out_of_marks" name="add_out_of_marks" placeholder="Enter Out of Marks">
                     <br>
                     <label for="email">Enter Passing Marks*  </label>
                     <input type="text" class="form-control" id="add_passing_marks" name="add_passing_marks" placeholder="Enter Passing Marks">
                  
                     <br>
            
                     <label for="email">program*  </label>
                     <select class="form-control drop_sel" id="add_programmaster_Id" name="add_programmaster_Id" readonly style="pointer-events: none;">
                         <?php
                                 $query_de = "SELECT setup_programmaster.* FROM setup_programmaster JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['program_name']."</option>";
                                 }
                         ?>

                     </select>
                    
                 </div>
             </div>
             <div class="panel-footer">
                  <input type="submit" name="submit_addinstance" value="Save Changes" id="submit_addinstance" class="btn btn-primary">  
             </div>

             </div>
         </div>
<!------------------------------------------------------------------------------------------------------------------------->           
<!------------------------------------------------------------------------------------------------------------------------->
         <div class="panel panel-default EventSelect_Model" >
             <div class="panel-heading" role="tab" id="headingTwo">
             <h4 class="panel-title">
             <button type="button" class="close add_instance"><span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#3c8dbc;"></span></button>
                 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 Edit Result Assessment Instance 
                 </a>
             </h4>
             </div>
             <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
             <form id="Edit_FormData">
             <div class="panel-body">
                 <div class="form-group">
                     <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     <label for="email">Enter Exam Header Name*  </label>
                     <select class="form-control drop_sel" id="edit_examheader_Id" name="edit_examheader_Id">
                        <option value="">Select Exam Header</option>
                         <?php
                                 $query_de = "SELECT result_exam_header.* FROM result_exam_header Where result_exam_header.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Header_Name']."</option>";
                                 }
                         ?>

                     </select>
                     <br>
                     <label for="email">Enter Course Name*  </label>
                     <select class="form-control drop_sel" id="edit_coursemaster_Id" name="edit_coursemaster_Id">
                        <option value="">Select Course Name</option>
                         <?php
                                 $query_de = "SELECT setup_coursemaster.* FROM `setup_coursemaster` WHERE setup_coursemaster.programmaster_Id = '$program_sel'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['course_name']."</option>";
                                 }
                         ?>

                     </select>
                     <br>
                     <label for="email">Enter Out Of Marks*  </label>
                     <input type="text" class="form-control" id="edit_out_of_marks" name="edit_out_of_marks" placeholder="Enter Out of Marks">
                     <br>
                     <label for="email">Enter Passing Marks*  </label>
                     <input type="text" class="form-control" id="edit_passing_marks" name="edit_passing_marks" placeholder="Enter Passing Marks">
                  
                     <br>
            
                     <label for="email">program*  </label>
                     <select class="form-control drop_sel" id="edit_programmaster_Id" name="edit_programmaster_Id" readonly style="pointer-events: none;">
                         <?php
                                 $query_de = "SELECT setup_programmaster.* FROM setup_programmaster JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['program_name']."</option>";
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




<?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->
            
    </div><!--Row Close-->

</div><!--Close Container-->


<script>
$('#FilterForm').submit(function(e){
    e.preventDefault();
    var program_sel = $('#program_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./setup/assessmentpattern.php?Generate_View='+'u',
        type:'GET',
        data: {program_sel:program_sel},
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");

            $('#add_programmaster_Id').val(program_sel);
            
            $('#program_sel_import').val(program_sel);
        },
   });



});

//Model Always Show
$('div').removeClass("modal-backdrop");
$('.InstanceCreate_Model').collapse('show');
$('.EventSelect_Model').hide();


$('.add_instance').click(function(event){

    $('.EventSelect_Model').hide();
    $('.InstanceCreate_Model').collapse('show');

});


//INSTANCE ADD-----------------------------------------------------------------------------------------------------------

$('#submit_addinstance').click(function(event){
   var add_examheader_Id = $('#add_examheader_Id').val();
   var add_coursemaster_Id = $('#add_coursemaster_Id').val();
   var add_out_of_marks = $('#add_out_of_marks').val();

   var add_passing_marks = $('#add_passing_marks').val();
   var add_course_number = $('#add_course_number').val();

   var add_programmaster_Id = $('#add_programmaster_Id').val();

   var program_sel = $('#program_sel').val();


    if(add_examheader_Id == '' || add_coursemaster_Id == '' || add_out_of_marks == '' || add_programmaster_Id == '' || add_passing_marks == '' ){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'All fields is mandatory',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url:'./setup/setup_api.php?Add_ResultAssessment='+'u',
        type:'POST',
        data: {add_examheader_Id:add_examheader_Id,add_coursemaster_Id:add_coursemaster_Id,add_out_of_marks:add_out_of_marks, add_programmaster_Id:add_programmaster_Id, add_passing_marks:add_passing_marks},
        dataType: "json",
        success:function(add_instance_res){  
            if(add_instance_res['status'] == 'success'){
                $.ajax({
                    url:'./setup/assessmentpattern.php?Generate_View='+'u',
                    type:'GET',
                    data: {program_sel: program_sel},
                    success:function(st_logs){
                        $('#DisplayDiv').html(st_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'Result Assessment Instance Added',
                        });

                                    
                        $('#add_programmaster_Id').val(program_sel);
                        
                        $('#program_sel_import').val(program_sel);
                    },
                });   

            }else if(add_instance_res['status'] == 'EXISTS'){
                       
                       $("#loader").css("display", "none");
                       $("#DisplayDiv").css("display", "block");
                       iziToast.error({
                           title: 'Duplicate',
                           message: 'Result Assessment Already Exist',
                       });
           }

        },//Close Success
    });// close Ajax 

});

//Instance Add Close----------------------------------------------------------------------------------------------------


//Instance Delete----------------------------------------------------------------------------------------------------
$('.delete_instance_btn').click(function(event){
    var delete_instance_Id = $(this).attr('id');
    var program_sel = $('#program_sel').val();


    if (confirm('Are you sure you want to Delete Existing Instance?')) {
        $.ajax({
            url:'./setup/setup_api.php?Delete_ResultAssessment='+'u',
            type: 'POST',
            data: {delete_instance_Id:delete_instance_Id},
            success:function(del_msg){
                if(del_msg == '200'){
                    
                    $.ajax({
                        url:'./setup/assessmentpattern.php?Generate_View='+'u',
                        type:'GET',
                        data: {program_sel: program_sel},
                        success:function(st_logs){
                            $('#DisplayDiv').html(st_logs);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.success({
                                title: 'Success',
                                message: 'Result Assessment Deleted',
                            });

                                            
                            $('#add_programmaster_Id').val(program_sel);
                            
                            $('#program_sel_import').val(program_sel);
                        },
                    });   

                }
            },
        });
    }

});
//Instance Delete Close----------------------------------------------------------------------------------------------------

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
    var fetch_Edited_examheader_Id = createURL.searchParams.get('fetch_edit_examheader_Id');
    var fetch_Edited_coursemaster_Id = createURL.searchParams.get('fetch_edit_coursemaster_Id');
    var fetch_Edited_out_of_marks = createURL.searchParams.get('fetch_edit_out_of_marks');
    var fetch_Edited_passing_marks = createURL.searchParams.get('fetch_edit_passing_marks');
    var fetch_programmaster_Id = createURL.searchParams.get('fetch_edit_programmaster_Id');

    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_examheader_Id').val(fetch_Edited_examheader_Id);
    $('#edit_coursemaster_Id').val(fetch_Edited_coursemaster_Id);
    $('#edit_out_of_marks').val(fetch_Edited_out_of_marks);
    $('#edit_passing_marks').val(fetch_Edited_passing_marks);
    $('#edit_programmaster_Id').val(fetch_programmaster_Id);

});
//Instance Edit Close----------------------------------------------------------------------------------------------------



//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

    var EditData = $('#Edit_FormData').serializeArray();
    var program_sel = $('#program_sel').val();


    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./setup/setup_api.php?Edit_ResultAssessment='+'u',
        type:'POST',
        data: EditData,
        dataType: "json",
        success:function(edit_instance_res){  
            if(edit_instance_res == '200'){
                $.ajax({
                    url:'./setup/assessmentpattern.php?Generate_View='+'u',
                    type:'GET',
                    data: {program_sel: program_sel},
                    success:function(sd_logs){
                        $('#DisplayDiv').html(sd_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'Result Assessment Instance Edited',
                        });

                                    
                        $('#add_programmaster_Id').val(program_sel);
                        
                        $('#program_sel_import').val(program_sel);
                    },
                });   

            }else{

                $.ajax({
                    url:'./setup/assessmentpattern.php?Generate_View='+'u',
                    type:'GET',
                    data: {program_sel: program_sel},
                    success:function(sd_logs){
                        $('#DisplayDiv').html(sd_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Invalid Input All field is mandatory',
                            message: 'Enter All Details',
                        });

                                    
                        $('#add_programmaster_Id').val(program_sel);
                        
                        $('#program_sel_import').val(program_sel);
                    },
                });  

            }

        },//Close Success
    });// close Ajax 

});

//Instance Edit Submit Close----------------------------------------------------------------------------------------------------



$('#import_file_submit').on('click', function(event){
    event.preventDefault();

    $('#contact_dialog').modal('hide');
    let form = $('#import_file_form')[0];
    let data = new FormData(form);
    var program_sel = $('#program_sel').val();
    setTimeout(function(){
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        jQuery.ajax({
            url: './setup/setup_api.php?Add_ResultAssessment_InBulk=u',
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
                        url:'./setup/assessmentpattern.php?Generate_View='+'u',
						type: "GET",
                        data: {program_sel:program_sel},
						success:function(data){
							$('#DisplayDiv').html(data);
							$("#loader").css("display", "none");
							$("#DisplayDiv").css("display", "block");
                            $('#add_programmaster_Id').val(program_sel);
                            
                            $('#program_sel_import').val(program_sel);
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

#sel_course{
    height: 34px;
    width: 200px;
    border:0px;
    border-bottom: 2px solid #3c8dbc;
    -webkit-appearance: none;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: 1ex;
    background-origin: content-box;
    background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIgogICB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiCiAgIHhtbG5zOnN2Zz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgdmVyc2lvbj0iMS4xIgogICBpZD0ic3ZnMiIKICAgdmlld0JveD0iMCAwIDM1Ljk3MDk4MyAyMy4wOTE1MTgiCiAgIGhlaWdodD0iNi41MTY5Mzk2bW0iCiAgIHdpZHRoPSIxMC4xNTE4MTFtbSI+CiAgPGRlZnMKICAgICBpZD0iZGVmczQiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAyLjAxNDUxLC00MDcuMTIyMjUpIgogICAgIGlkPSJsYXllcjEiPgogICAgPHRleHQKICAgICAgIGlkPSJ0ZXh0MzMzNiIKICAgICAgIHk9IjYyOS41MDUwNyIKICAgICAgIHg9IjI5MS40Mjg1NiIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDpub3JtYWw7Zm9udC1zaXplOjQwcHg7bGluZS1oZWlnaHQ6MTI1JTtmb250LWZhbWlseTpzYW5zLXNlcmlmO2xldHRlci1zcGFjaW5nOjBweDt3b3JkLXNwYWNpbmc6MHB4O2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MXB4O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICB4bWw6c3BhY2U9InByZXNlcnZlIj48dHNwYW4KICAgICAgICAgeT0iNjI5LjUwNTA3IgogICAgICAgICB4PSIyOTEuNDI4NTYiCiAgICAgICAgIGlkPSJ0c3BhbjMzMzgiPjwvdHNwYW4+PC90ZXh0PgogICAgPGcKICAgICAgIGlkPSJ0ZXh0MzM0MCIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTo0MHB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6Rm9udEF3ZXNvbWU7LWlua3NjYXBlLWZvbnQtc3BlY2lmaWNhdGlvbjpGb250QXdlc29tZTtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjFweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIj4KICAgICAgPHBhdGgKICAgICAgICAgaWQ9InBhdGgzMzQ1IgogICAgICAgICBzdHlsZT0iZmlsbDojMzMzMzMzO2ZpbGwtb3BhY2l0eToxIgogICAgICAgICBkPSJtIDIzNy41NjY5Niw0MTMuMjU1MDcgYyAwLjU1ODA0LC0wLjU1ODA0IDAuNTU4MDQsLTEuNDczMjIgMCwtMi4wMzEyNSBsIC0zLjcwNTM1LC0zLjY4MzA0IGMgLTAuNTU4MDQsLTAuNTU4MDQgLTEuNDUwOSwtMC41NTgwNCAtMi4wMDg5MywwIEwgMjIwLDQxOS4zOTM0NiAyMDguMTQ3MzIsNDA3LjU0MDc4IGMgLTAuNTU4MDMsLTAuNTU4MDQgLTEuNDUwODksLTAuNTU4MDQgLTIuMDA4OTMsMCBsIC0zLjcwNTM1LDMuNjgzMDQgYyAtMC41NTgwNCwwLjU1ODAzIC0wLjU1ODA0LDEuNDczMjEgMCwyLjAzMTI1IGwgMTYuNTYyNSwxNi41NDAxNyBjIDAuNTU4MDMsMC41NTgwNCAxLjQ1MDg5LDAuNTU4MDQgMi4wMDg5MiwwIGwgMTYuNTYyNSwtMTYuNTQwMTcgeiIgLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=");
}

#sel_course::-ms-expand {
	display: none;
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
</style>
