<?php

//Fetching User Details
$data_query = "SELECT user_stafflogin.username,setup_sectionmaster.abbreviation As section_abbreviation FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$ActiveStaffLogin_Id' AND setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);


//Fetching Academic Year
$AY_array_result = mysqli_query( $mysqli, "SELECT `academic_year` as Academic_Year FROM `setup_academicyear` Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' Order By sequence_no Asc " );
$AY_array = array();
while ( $row_AY = $AY_array_result->fetch_assoc() ) $AY_array[] = $row_AY;

//finding accessible pages to this particular id
$query = "
SELECT `setup_links_access`.`function_id`
FROM `user_stafflogin`
    LEFT JOIN `setup_links_access` on `user_stafflogin`.`Id`=`setup_links_access`.`user_id`
WHERE `user_stafflogin`.`Id`='" . $ActiveStaffLogin_Id . "'
";

$result1 = mysqli_query( $mysqli, $query );


//array of accessible functions
$function_array = array();

if ( $result1 != null )
{
  while ( $res = mysqli_fetch_assoc( $result1 ) )
  {
    $function_array[] = $res['function_id'];
  }
}
// check if a permission is set
function hasAccess($permission = [])
{
  global $function_array;
  // check if user has a specific privilege
  return array_intersect( $permission, $function_array );
}


?>


<nav>
    <div class="nav-wrapper" style="display: list-item;">
      <a href="#!" class="brand-logo">SCHOOLZONE</a>
      <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large"><i class="material-icons">menu</i></a>
      <ul class="right">
        <li><span id="UTCtime" style="color: #fff" class="hide-on-med-and-down"></span></li>
        <li><a class="dropdown-trigger" href="#!" data-target="dropdown1" style="width: 110px;">
          <img src="https://www.shareicon.net/data/256x256/2016/09/15/829459_man_512x512.png" alt="" class="circle responsive-img z-depth-3 hide-on-med-and-down" style="max-width: 38px;margin-top: 11px;float: inline-end;">
          <i class="material-icons right">arrow_drop_down</i></a>
        </li>
      </ul>
    </div>
</nav>


<ul id="dropdown1" class="dropdown-content">
  <li><a href="#!">Change Personal Details</a></li>
  <li class="divider"></li>
  <li><a href="#!">Logout</a></li>
</ul>

<ul id='acdemic_dropdown1' class='dropdown-content'>
    <?php $j = count( $AY_array );
        for ( $i = 0; $i < $j; $i++ )
        {
            if ( $AY_array[$i]['Academic_Year'] == $Academic_Year )
            {
            echo '<li class="active"><a href="#">' . $AY_array[$i]['Academic_Year'] . '</a></li>';
            }
            else
            {
            ?>
            <li>
                <a class="waves-effect waves-light modal-trigger" href="#GlobalAcademicmodal1" 
                onclick="$('#modal_ay_change_pass').val('<?php echo $AY_array[$i]['Academic_Year']; ?>');;">
                <?php echo $AY_array[$i]['Academic_Year']; ?>
                </a>
            </li>
            <?php
            }
        }
    ?>
  </ul>


  <!-- Academic Modal Structure -->
  <div id="GlobalAcademicmodal1" class="modal modal-fixed-footer" >
  <form class="col s12" id="adminAY_change_form"  method="POST" onsubmit="return false">
    <div class="modal-content">
      <h4>Change Academic Year</h4>
       <div class="row">

                    <div class="input-field col s12">
                    <input placeholder="Placeholder" id="modal_ay_change_pass" name="modal_ay_change_pass" type="text" class="grey lighten-3" readonly>
                    <label for="modal_ay_change_pass">Select Academic Year</label>
                    </div>
           
                    <div class="input-field col s12">
                    <input id="passworday" type="password" name="passworday" class="validate" required>
                    <label for="passworday">Password</label>
                    </div>
        </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
      <button type="submit" id="submitForm" class="btn waves-effect waves-green btn-flat">Submit</button>
    </div>
    </form>
  </div>


<ul id="slide-out" class="sidenav">
  <li><div class="user-view">
    <div class="background">
      <img src="https://blog.investisdigital.com/wp-content/uploads/2020/10/blue-orange-bg.png" class="bg-image">
    </div>
    <a href="#user"><img class="circle" src="https://www.shareicon.net/data/256x256/2016/09/15/829459_man_512x512.png"></a>
    <a href="#name"><span class="white-text name" style="text-transform: uppercase;">User Name: <?php echo $r_Staffdata_fetch['username']; ?></span></a>
    <a href="#email"><span class="white-text email" style="text-transform: uppercase;">Section Name: <?php echo $r_Staffdata_fetch['section_abbreviation']; ?></span></a>
  </div></li>
  <li><a href="#!" class='dropdown-trigger'  data-target='acdemic_dropdown1'>Academic Year: <?php echo $Academic_Year; ?></a></li>
  <li><div class="divider"></div></li>
  <li><a class="subheader">Subheader</a></li>
  <!-- <li><a class="waves-effect" href="#!">Third Link With Waves</a></li> -->







</ul>


<script>

// SIDEBAR
$(document).ready(function(){
    $('.sidenav').sidenav();
    $('.dropdown-trigger').dropdown();
    $('.modal').modal();
});


function display_c() {
      var reschoolzone = 1000; // Reschoolzone rate in milli seconds
      mytime = setTimeout('display_ct()', reschoolzone)
}

function display_ct() {
    var strcount;
    var x = new Date();
    var NewFormatDate = x.toString().slice(0, 24);
    document.getElementById('UTCtime').innerHTML = NewFormatDate;
    tt = display_c();
}

display_c();


$('#adminAY_change_form').submit(function (event) {
      var passworday = $('#passworday').val();
      if(passworday == ''){
        alert('Password is Required');
        return false;
      }

      $.ajax({
        url: './auth/onlinelogin_api.php?Change_Academic_Year=u',
        type: 'post',
        dataType: 'html',   //expect return data as html from server
        data: $('#adminAY_change_form').serialize(),
        success: function (response, textStatus, jqXHR) {
          if (response == 'Success')
          {
            location.reload();
          }else{
            alert('Invalid Password');
          }
          
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log('error(s):' + textStatus, errorThrown);
        }
      });
});
</script>

<style>
.bg-image{
  max-width: 100%;
} 

#GlobalAcademicmodal1{
    width: 30vw;
    height: 50%;
}

@media only screen and (max-width: 600px) {
  #GlobalAcademicmodal1 {
    width: 80vw;
  }
}
</style>
