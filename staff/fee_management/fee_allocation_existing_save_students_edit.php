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
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Edit Fee Allocation for Existing Students - (Batch : <?php echo $r_batch_fetch['batch_name']; ?>)</b></i></h4></div>


<input type="hidden" name="batch_sel" id="batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="selecteSBM_Id" id="selected_SBM_Id" class="form-control" value="<?php echo $selected_SBM_Id; ?>">



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
?>

<?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->


<script>

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
        url:'./fee_management/fee_allocation_existing_save_students_edit.php?Generate_View='+'u',
        type:'POST',
        data: {FS_sel:FS_sel, FS_txt:FS_txt, selected_SBM_Id:selected_SBM_Id, return_batch_sel:batch_sel},
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
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

