<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-08-2019
 * Time: 03:16 PM
 */

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
session_start();
include_once '../../config/database.php';

if ( isset( $_POST['parent_id'] ) ) {
  $parent_id = $_POST['parent_id'];
  $header    = $_POST['page_name'];
  $url       = $_POST['c_url'] == 'null' || $_POST['c_url'] == 'NULL' ? NULL : $_POST['c_url'];
  $access_type = $_POST['access_type'];

  $modulelist_id = $_POST['modulelist_id'];

  if ( $parent_id ) {
    $get = QUERY::run( 'SELECT `Id`, `parent`, `depth`, `header`, `url` 
                      FROM `setup_links` 
                      WHERE `Id` = ? ',
                      [ $parent_id ] )->fetch();
    QUERY::run( 'INSERT INTO
                 `setup_links`
                 (`parent`, `header`, `depth`, `url`, link_user_type, access_type, modulelist_Id)
               VALUES
                  ( ?,  ?,  ?,  ?, ? , ?, ? )',
               [ $get['Id'], $header, ++$get['depth'], $url, 0 , $access_type, $modulelist_id ] );
  }
  else {
    QUERY::run( 'INSERT INTO
                 `setup_links`
                 (`parent`, `header`, `depth`, `url`, link_user_type, access_type, modulelist_Id)
               VALUES
                  ( ?,  ?,  ?,  ?, ? , ?, ? )', [ 0, $header, 0, $url, 0 , $access_type, $modulelist_id ] );
  }
}
else {
  ?>

  <div class="box col-xs-12" id="actions">
    <div class="box-header">
      <h3 class="box-title">Add Page</h3>
    </div>

    <div class="box-header">
      <button class=" btn btn-danger btn-md pull-right"
              style="width:100px"
              onclick="getPage('./user_management/control_url.php');">Return
      </button>
    </div>

    <div>
      <form class="form-horizontal"
            id="page_form"
            method="post"
            name="page_form"
            enctype="multipart/form-data"
            onsubmit="return false">
        <div>
        </div>
        <div class="form-group">

          <label class="col-sm-2 input-sm control-label"
                 for="inputheader"
                 style="font-size:15px">Page Name</label>
          <div class="col-sm-4">
            <input class="form-control"
                   required
                   type="text"
                   name="page_name"
                   id="page_name"
                   maxlength="120"
                   title="Enter unique page name here" />
          </div>
        </div>
        <!--                                                                                            -->
        <div class="form-group docs-buttons">
          <div class="btn-group">

            <div class="col-sm-5">
              <button type="button"
                      class="btn btn-primary"
                      data-method="insertpage"
                      data-option="file_name.php?param=value"
                      value="file_name.php?param=value"
                      data-target="#c_url"
                      title="Insert Page">

              <span class="docs-tooltip" data-toggle="tooltip" title="Insert Page">
                <span>Insert Page</span>
              </span>
              </button>
            </div>

            <div class="col-sm-5">
              <button type="button"
                      class="btn btn-primary"
                      data-method="insertmenu"
                      data-option="NULL"
                      data-target="#c_url"
                      title="Insert Menu">

                          <span class="docs-tooltip" data-toggle="tooltip" title="Insert Menu">
                            <span>Insert Menu</span>
                          </span>
              </button>
            </div>

          </div>
          <label class="col-sm-2 input-sm control-label"
                 for="inputfee_header_name"
                 style="font-size:15px">
            File Name</label>
          <div class="col-sm-4">
            <input class="form-control"
                   type="text"
                   value='<?php echo $fetch_header_result['c_url']; ?>'
                   name="c_url"
                   id="c_url"
                   title="Enter unique file name here" />
          </div>
        </div>
        <!--                                                                                            -->
        <div class="form-group">

          <label class="col-sm-2 input-sm control-label"
                 for="inputheader"
                 style="font-size:15px">Insert Page Under</label>
          <div class="col-sm-4">
            <select class="form-control"
                    required
                    name="parent_id"
                    id="parent_id"
                    title="Select parent page">

              <option value="">--Select--</option>
              <option value="0" class="strong">Root</option>

              <?php
              $pages = QUERY::run( 'SELECT * from setup_links WHERE url IS NULL AND  setup_links.link_user_type = 0' );
              foreach ( $pages as $view ) {
                echo '<option value="' . $view['Id'] . '">' . $view['header'] . '</option>';
              }
              ?>
            </select>

          </div>

          </div>

        <div class="form-group">

          <label class="col-sm-2 input-sm control-label"
                 for="inputheader"
                 style="font-size:15px">Access Type</label>
          <div class="col-sm-4">
            <select class="form-control"
                    required
                    name="access_type"
                    id="access_type"
                    title="Select Access Type">

              <option value="">--Select--</option>
              <option value="1" class="strong">Super Admin</option>
              <option value="0" class="strong">Users</option>
              ?>
            </select>

          </div>
          
          </div>


          <div class="form-group">

            <label class="col-sm-2 input-sm control-label"
                  for="inputheader"
                  style="font-size:15px">Module List</label>
            <div class="col-sm-4">
              <select class="form-control"
                      required
                      name="modulelist_id"
                      id="modulelist_id"
                      title="Select Module list">

                <option value="">--Select--</option>

                <?php
                $pages = QUERY::run( 'SELECT * from setup_modulelist' );
                foreach ( $pages as $view ) {
                  echo '<option value="' . $view['Id'] . '">' . $view['modulelist'] . '</option>';
                }
                ?>
              </select>

            </div>

            </div>


          </br>
          <div class="box-header">
            <button class="btn btn-primary pull-right" style="width:100px" type="submit">Add</button>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- <script src="datatables/jquery.dataTables.min.js"></script>
  <script src="datatables/dataTables.bootstrap.min.js"></script> -->
  <script>
    (() => {
      var actions = document.getElementById('actions');

      // Methods
      actions.querySelector('.docs-buttons').onclick = function (event) {
        var e      = event || window.event;
        var target = e.target || e.srcElement;
        var input;
        var data;

        while (target !== this) {
          if (target.getAttribute('data-method')) {
            break;
          }
          target = target.parentNode;
        }
        if (target === this || target.disabled || target.className.indexOf('disabled') > -1){
          return;
        }
        data = {
          method: target.getAttribute('data-method'),
          option: target.getAttribute('data-option') || undefined,
          target: target.getAttribute('data-target'),
        };
        if (data.method){
          if (typeof data.target !== 'undefined'){
            input = document.querySelector(data.target);
            if (!target.hasAttribute('data-option') && data.target && input) {
              try {
                data.option = input.value;
              }
              catch (e) {
                console.log(e.message);
              }
            }
          }

          switch (data.method) {
            case 'insertpage':
              try {
                //data.option = JSON.parse(data.option);
                document.getElementById('c_url').value = data.option;
              }
              catch (e) {
                console.log(e.message);
              }
              break;
            case 'insertmenu':
              try {
                document.getElementById('c_url').value = data.option;
              }
              catch (e) {
                console.log(e.message);
              }
              break;
          }
        }
      }
    })();

    $('#page_form').submit(function (event) {
      $("#loader").css("display", "block");
      $("#DisplayDiv").css("display", "none");

      $.ajax({
        url: './user_management/control_url_add.php',
        type: 'POST',
        dataType: 'html',   //expect return data as html from server
        data: $('#page_form').serialize(),

        success: function (response1, textStatus, jqXHR) {
          alert('Page Added');
          jQuery.ajax({
            url: './user_management/control_url_add.php',
            type: 'GET',
            dataType: 'html',

            success: function (response, textStatus, jqXHR) {
              $('#DisplayDiv').html(response);
              $("#loader").css("display", "none");
              $("#DisplayDiv").css("display", "block");
            },
            error: function (xhr, textStatus, errorThrown) {
              // console.log();
              $('#DisplayDiv').html(textStatus.reponseText);
              $("#loader").css("display", "none");
              $("#DisplayDiv").css("display", "block");
            }
          });
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log('error(s):' + textStatus, errorThrown);
        }
      });
    });
  </script>
  <?php
}
?>
