<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

include_once '../../config/database.php';


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

?>

<div class="col-md-12"><h4><i><b>  Screen Access</b></i>
     <a onclick="getPage('./user_management/homescreen.php');">
        <button class="btn btn-danger pull-right">Return
        </button>
     </a>
</h4></div>



<div class="container">
    <div class="row">
       <div class="col-md-12"> 



        <form id="ReceiptForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Screen:</label>
                </div>
                
                <div class="col-md-3"> 
                    <select name="screen_sel" id="screen_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select Screen ----</option>
                                <?php 
                                    $batch_fetch = mysqli_query($mysqli,"SELECT
                                    child.Id AS c_id,
                                    CONCAT
                                    (
                                      ( CASE WHEN gran.header   IS NULL THEN '' ELSE CONCAT( '/', gran.header   ) END ),
                                      ( CASE WHEN parent.header IS NULL THEN '' ELSE CONCAT( '/', parent.header ) END ),
                                      '/', child.header
                                    ) AS PATH,
                                    ( 
                                      CASE 
                                        WHEN parent.header IS     NULL AND child.url IS NULL THEN '--root--' 
                                        WHEN parent.header IS NOT NULL AND child.url IS NULL THEN '-subroot-' 
                                        ELSE child.url 
                                      END 
                                    ) AS c_url,
                                    child.header AS c_head,  
                                    ( 
                                      CASE 
                                        WHEN parent.header IS NULL THEN '-'
                                        ELSE parent.header
                                      END 
                                    ) AS p_head
                                  FROM
                                    setup_links child
                                    LEFT JOIN setup_links parent    ON parent.Id    = child.parent 
                                    LEFT JOIN setup_links gran      ON gran.Id      = parent.parent
                                    LEFT JOIN setup_links greatgran ON greatgran.Id = gran.parent 
                                    JOIN setup_modulemapping ON setup_modulemapping.modulelist_Id = child.modulelist_Id
                                  WHERE parent.header IS NOT NULL AND child.link_user_type = '0' AND child.access_type = '0' AND setup_modulemapping.sectionmaster_Id = '$SectionMaster_Id' AND  setup_modulemapping.userType_Id = 0 ");
                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['c_id']; ?>" <?php if($r_batch['c_id'] == $_GET['screen_sel']){ echo 'Selected'; } ?>  class="<?php echo $r_batch['c_head']; ?>"><?php echo $r_batch['c_head']; ?></option>
                                <?php }   ?>
                                

                                
                    </select>
                    <input type="hidden" name="screen_name" id="screen_name" value="">
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="button" name="search" id="search_access" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
        </form> 



<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){

        extract($_POST);
        $screen_sel = $_GET['screen_sel'];
        $screen_name = $_GET['screen_name'];

        $accessquery = "SELECT user_stafflogin.username AS fullName, user_stafflogin.Id AS Staff_LoginId, user_stafflogin.username AS UserName, setup_links_access.id AS screenaccessid, setup_links_access.function_id AS Page_Id FROM user_stafflogin LEFT JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id LEFT JOIN setup_links_access ON setup_links_access.user_id = user_stafflogin.Id AND setup_links_access.function_id = '$screen_sel' WHERE setup_departmentmaster.sectionmaster_Id = '$SectionMaster_Id' GROUP BY user_stafflogin.Id   ";

        // echo $applicationquery;
        $access_list_q = mysqli_query($mysqli, $accessquery);



    ?>


<div class="col-md-10">
    <form id="ApplicationForm">
    <input type="hidden" id="screen_sel_val" name="screen_sel" value="<?php echo $screen_sel; ?>" >
    <table class="table table-striped" id="header_list" style="overflow-y: scroll;overflow-x: scroll;width:100%">
        <thead>
            <tr><th style="text-align:center;" colspan="5">Screen Name: <span class="text-primary"><?php echo $screen_name; ?></span></th>
            </th>
            </tr>
            <tr><th>Sr No.</th><th style="text-align:left;">Teacher Name</th><th>UserName</th><th>Access Status</th><th>Operations <br>
               <input type="checkbox" class="subcheck" id="check">
            </th>
            </tr>
        </thead>
        <tbody>

        <?php 
                
                                      
             
                    
                    $row_access_list = mysqli_num_rows($access_list_q);

                    if($row_access_list > 0){

                        

                        $i = 1; while($r_access_list = mysqli_fetch_array($access_list_q)){ 
                  
                        ?> 

                            

                            <tr class="tr<?php echo $r_access_list['Staff_LoginId']; ?>">    
                                <td><?php echo $i; ?></td>
                                <td><?php echo $r_access_list['fullName']; ?></td>
                                <td><?php echo $r_access_list['UserName']; ?></td>
                              
                                <td>
                                    <?php if(!empty($r_access_list['Page_Id'])){ ?>
                                        Activated
                                    <?php }else{ ?>
                                        Disabled
                                    <?php } ?>    
                                </td>

                                <td>
                                 
                                    


                                    <div class="pretty p-icon p-smooth">
                                             <input type="checkbox" class="subcheck sel_box" id="check<?php echo $r_access_list['Staff_LoginId']; ?>" name="check[]" value="<?php echo $r_access_list['Staff_LoginId']; ?>"  <?php if(!empty($r_access_list['Page_Id'])){ echo 'checked'; } ?> >
                                                <div class="state p-success">
                                                    <i class="icon fa fa-check"></i>
                                                    <label></label>
                                                </div>
                                    </div> 


                                  
                                    <input type="checkbox" class="subchecks sel_boxs hidden" id="checks<?php echo $r_access_list['Staff_LoginId']; ?>" name="checks[]" value="<?php echo $r_access_list['Staff_LoginId']; ?>" checked >
                                               
                                     
                                </td>

                            </tr>  

                                <?php 
                        $i++; }

                    }//row close
                ?>

                
            
  


                      
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"></th>
                <th><button type="button" class="btn btn-success"id="Submit_Access_User">Allow Access</button>                         
            </tr>
        </tfoot>
    
    </table>
    </form>
</div> <!--Col Close-->


<script>
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
{column_number: 3, filter_match_mode: "exact"}
]);


</script>


    <?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->
            
        </div>
    </div>
</div>



<script>

$('#check').click(function(){
    if($(this).prop("checked") == false){
        $(".sel_box").removeAttr('checked');
    
    }else{

        $(".sel_box").prop('checked', true);

    }   
});



$('#Submit_Access_User').click(function(e){
    e.preventDefault();
    var screen_sel = $('#screen_sel_val').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#ApplicationForm').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?Allow_Screen_Access='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully');
                $.ajax({
                        url:'./user_management/control_screen_access.php?Generate_View='+'u',
                        type:'GET',
                        data: {screen_sel:screen_sel},
                        success:function(srh_response){
                            $('#DisplayDiv').html(srh_response);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                        },
                });
                    
            }


        },
   });



});



//--------------------------------------------------------------------------------------------------------------------------------------
            //New Receipt Button Clicked
$('#search_access').click(function(e){
    e.preventDefault();

    var batchName = $('select[name="screen_sel"]').find(':selected').attr('class');

    $('#screen_name').val(batchName);

    var formdata = $('#ReceiptForm').serializeArray();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./user_management/control_screen_access.php?Generate_View='+'u',
        type:'GET',
        data: formdata,
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });



});


</script>