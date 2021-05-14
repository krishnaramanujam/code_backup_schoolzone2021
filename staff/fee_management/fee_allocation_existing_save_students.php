<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

?>
<?php


if(isset($_POST['SBM_Id'])){
    extract($_POST);

    $formating_User_Id = implode(",",$SBM_Id);

    //Batch Details
    $q = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id JOIN comm_batch_access ON comm_batch_access.batchMaster_Id = setup_batchmaster.Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND  setup_batchmaster.Id = '$return_batch_sel'";
    $batch_fetch = mysqli_query($mysqli,$q);
    $r_batch_fetch = mysqli_fetch_array($batch_fetch);



?>
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Fee Allocation for Existing Students - (Batch : <?php echo $r_batch_fetch['batch_name']; ?>)</b></i></h4></div>


<input type="hidden" name="batch_sel" id="batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">



<?php }else{ ?>
<div class="container">

    <div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Fee Allocation for Existing Students</b></i></h4></div>

    <input type="hidden" name="batch_sel" id="batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">


    <div class="row">
        <div class="col-md-12">
             <div class="alert alert-warning" role="alert">Please GO Back And Select Student From List For Further Processing.</div>
        </div>
    </div>

</div>
<?php } ?>


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

</script>

<style>
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

.detailsinput{
    border-bottom: 2px solid #3c8dbc;
}
</style>

