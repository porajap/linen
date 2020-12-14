<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

if ($Userid == "") {
  header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}
require 'updatemouse.php';

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requests</title>
  <?php include_once('../assets/import/css.php'); ?>


</head>

<body>

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Create Requests</a></li>
    <li class="breadcrumb-item active">การร้องขออื่นๆ</li>
  </ol>

  <div class="col-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true">การร้องขออื่นๆ</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="tab_head2" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab_head2" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane show active fade" id="tab1" role="tabpanel" aria-labelledby="tab1">
        <div class="col-md-12">
          <div class="container-fluid">
            <div class="card-body mt-3">
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['side'][$language]; ?>
                    </label>
                    <select class="form-control col-sm-7 icon_select " id="selectSite"></select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['department'][$language]; ?>
                    </label>
                    <select class="form-control col-sm-7 icon_select" id="selectDep"></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['docdate'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " disabled="true" id="txtDocDate" placeholder="<?php echo $array['docdate'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['docno'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  only1" disabled="true" id="txtDocNo" placeholder="<?php echo $array['docno'][$language]; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['employee'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1" disabled="true" id="txtCreate" placeholder="<?php echo $array['employee'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['time'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  only1" disabled="true" id="txtTime" placeholder="<?php echo $array['docno'][$language]; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                        Process
                    </label>
                    <input type="text" id="txtStatus" style="font-size:22px;" class="form-control col-sm-7   only1" disabled="true">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                        ลงชื่อผู้ขอเบิก
                    </label>
                    <input type="text" autocomplete="off" id="txtName"  style="font-size:22px;" class="form-control col-sm-7 " placeholder="ลงชื่อผู้ขอเบิก">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      นามสกุลผู้ขอเบิก
                    </label>
                    <input type="text" autocomplete="off" id="txtLastName"  style="font-size:22px;" class="form-control col-sm-7 " placeholder="นามสกุลผู้ขอเบิก">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class=" mt-3  d-flex justify-content-end col-12">
          <div class="d-flex justify-content-center " style="margin-right: 6rem!important;">
            <div class="circle1 d-flex justify-content-center">
              <button class="btn" disabled>
                <i class="fas fa-file-medical"></i>
                <div>
                  <?php echo $array['createdocno'][$language]; ?>
                </div>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-center " style="margin-right: 6rem!important;">
            <div class="circle4 d-flex justify-content-center ">
              <button class="btn" disabled="true">
                <div id="icon_edit">
                  <i class="fas fa-save"></i>
                  <div>
                    <?php echo $array['save'][$language]; ?>
                  </div>
                </div>

              </button>
            </div>
          </div>

          <div class="d-flex justify-content-center " style="margin-right: 6rem!important;">
            <div class="circle3 d-flex justify-content-center ">
              <button class="btn" disabled="true">
                <i class="fas fa-trash-alt"></i>
                <div>
                  <?php echo $array['delitem'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <table class="table table-fixed table-condensed table-striped mt-3" id="tableItem" width="100%" cellspacing="0" role="grid">
              <thead id="theadsum" style="font-size:24px;">
                <tr role="row" id='tr_1'>
                  <th nowrap><?php echo $array['sn'][$language]; ?></th>
                  <th nowrap><?php echo $array['item'][$language]; ?></th>
                  <th nowrap>
                    <center><?php echo $array['parsc'][$language]; ?></center>
                  </th>
                  <th nowrap>
                    <center><?php echo $array['count'][$language]; ?></center>
                  </th>
                </tr>
              </thead>
              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;"></tbody>
            </table>
          </div>
        </div>



      </div>

      <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
        <div class="row mt-3">
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchSite">
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchDep">
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here " id="txtsDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchStatus">
                <option value="0"><?php echo $array['processchooce'][$language]; ?></option>
                <option value="1">on process</option>
                <option value="2">completed</option>
                <option value="3">cancel </option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['search'][$language]; ?>" class="form-control" id="txtSearch">
            </div>
          </div>
          <div class="col-md-2">
            <div class="row">
              <div class="search_custom ">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>
              <div class="search_custom ">
                <div class="circle11 d-flex justify-content-start">
                  <button class="btn">
                    <i class="fas fa-paste mr-2 pt-1"></i>
                    <?php echo $array['show'][$language]; ?>
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument" width="100%" cellspacing="0" role="grid">
              <thead id="theadsum" style="font-size:24px;">
                <tr role="row" id='tr_1'>
                  <th nowrap style="width:15%;"><center><?php echo $array['docdate'][$language]; ?></center></th>
                  <th nowrap style="width:15%;"><center><?php echo $array['docno'][$language]; ?></center></th>
                  <th nowrap style="width:15%;"><center><?php echo $array['department'][$language]; ?></center></th>
                  <th nowrap style="width:15%;"><center><?php echo $array['employee'][$language]; ?></center></th>
                  <th nowrap style="width:15%;"><center><?php echo $array['time'][$language]; ?></center></th>
                  <th nowrap style="width:15%;"><center><?php echo $array['setcount'][$language]; ?></center></th>
                  <th nowrap style="width:10%;"><center><?php echo $array['status'][$language]; ?></center></th>
                </tr>
              </thead>
              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;"></tbody>
            </table>
          </div>
        </div>





      </div>


    </div>

  </div>


  <?php include_once('../assets/import/js.php'); ?>
</body>

</html>