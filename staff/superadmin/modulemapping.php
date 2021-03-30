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



$selectedSection_Id = $_GET['selectedSection_Id'];

//Form Title
$form_Title_q = mysqli_query($mysqli, "SELECT setup_sectionmaster.section_name FROM setup_sectionmaster Where Id='$selectedSection_Id'");
$r_form_Title = mysqli_fetch_array($form_Title_q); 


$accessquery = "SELECT setup_modulelist.Id AS ML_Id, setup_modulelist.modulelist, setup_modulemapping.Id AS MM_Id, a.Id As SMM_Id FROM setup_modulelist LEFT JOIN setup_modulemapping ON setup_modulemapping.modulelist_Id = setup_modulelist.Id AND setup_modulemapping.sectionmaster_Id = '$selectedSection_Id' AND setup_modulemapping.userType_Id = '0' LEFT JOIN setup_modulemapping a ON a.modulelist_Id = setup_modulelist.Id AND a.sectionmaster_Id = '$selectedSection_Id' AND a.userType_Id = '1' WHERE 1 GROUP BY setup_modulelist.Id  ";

// echo $applicationquery;
$access_list_q = mysqli_query($mysqli, $accessquery);



?>

<div class="col-md-12"><h4><i><b>  Module Mapping</b></i>
     <a onclick="getPage('./superadmin/sectionmaster.php');">
        <button class="btn btn-danger pull-right">Return
        </button>
     </a>
</h4></div>



<div class="container">
    <div class="row">
       <div class="col-md-12"> 



    


<div class="col-md-6">
    <form id="ApplicationForm">
    <input type="hidden" id="selectedSection_Id" name="selectedSection_Id" value="<?php echo $selectedSection_Id; ?>" >
    <table class="table table-striped" id="ApplicationTable" style="overflow-y: scroll;overflow-x: scroll;">
        <thead>
            <tr><th style="text-align:center;" colspan="4">Section Name: <span class="text-primary"><?php echo $r_form_Title['section_name']; ?></span></th>
            </th>
            </tr>
            <tr><th>Sr No.</th><th style="text-align:left;">Module List</th><th>Staff <br>
               <input type="checkbox" class="subcheck" id="check">
            </th>
            <th>Student <br>
               <input type="checkbox" class="subchecks" id="checks">
            </th>
            </tr>
        </thead>
        <tbody>

        <?php 
                
                                      
             
                    
                    $row_access_list = mysqli_num_rows($access_list_q);

                    if($row_access_list > 0){

                        

                        $i = 1; while($r_access_list = mysqli_fetch_array($access_list_q)){ 
                  
                        ?> 

                            

                            <tr class="tr<?php echo $r_access_list['ML_Id']; ?>">    
                                <td><?php echo $i; ?></td>
                                <td style="text-align:left;"><?php echo $r_access_list['modulelist']; ?></td>
                               
                              
                                <td>
                                 
                                    


                                    <div class="pretty p-icon p-smooth">
                                             <input type="checkbox" class="subcheck sel_box" id="check<?php echo $r_access_list['ML_Id']; ?>" name="check[]" value="<?php echo $r_access_list['ML_Id']; ?>"  <?php if(!empty($r_access_list['MM_Id'])){ echo 'checked'; } ?> >
                                                <div class="state p-success">
                                                    <i class="icon fa fa-check"></i>
                                                    <label></label>
                                                </div>
                                    </div> 
                                     
                                </td>

                                <td>
                                 
                                    


                                 <div class="pretty p-icon p-smooth">
                                          <input type="checkbox" class="subchecks sel_boxs" id="checks<?php echo $r_access_list['ML_Id']; ?>" name="checks[]" value="<?php echo $r_access_list['ML_Id']; ?>"  <?php if(!empty($r_access_list['SMM_Id'])){ echo 'checked'; } ?> >
                                             <div class="state p-success">
                                                 <i class="icon fa fa-check"></i>
                                                 <label></label>
                                             </div>
                                 </div> 
                                  
                             </td>

                            </tr>  

                                <?php 
                        $i++; }

                    }//row close
                ?>

                
            
  


                      
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2"></th>
                <th><button type="button" class="btn btn-success"id="Submit_Access_User">Save Changes</button>                         
            </tr>
        </tfoot>
    
    </table>
    </form>
</div> <!--Col Close-->

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

$('#checks').click(function(){
    if($(this).prop("checked") == false){
        $(".sel_boxs").removeAttr('checked');
    
    }else{

        $(".sel_boxs").prop('checked', true);

    }   
});

$('#Submit_Access_User').click(function(e){
    e.preventDefault();
    var selectedSection_Id = $('#selectedSection_Id').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#ApplicationForm').serializeArray();
    
    $.ajax({
        url:'./superadmin/superadmin_api.php?Mapping_Module='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Mapping Successful');
                $.ajax({
                        url:'./superadmin/modulemapping.php',
                        type:'GET',
                        data: {selectedSection_Id:selectedSection_Id},
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
