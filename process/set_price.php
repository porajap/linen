<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function CreateDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $xDate = $DATA['xDate'];

    $Sql = "SELECT CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
          LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
          CURRENT_TIME() AS RecNow
          FROM category_price_time
          WHERE DocNo Like CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
          AND HptCode = '$HptCode'
          ORDER BY DocNo DESC LIMIT 1";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DocNo = $Result['DocNo'];
        $return['DocNo'] = $DocNo;
    }

    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price WHERE category_price.HptCode = '$HptCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }

    if($Cnt == 0){
        $Sql = "SELECT item_category.CategoryCode,item_category.Price
        FROM item_main_category
        INNER JOIN item_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
        WHERE item_category.IsStatus = 0";
    }else{
        $Sql = "SELECT item_category.CategoryCode,category_price.Price
        FROM category_price
        INNER JOIN site ON category_price.HptCode = site.HptCode
        INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode
        INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
        WHERE item_category.IsStatus = 0 
        AND category_price.HptCode = '$HptCode'";
    }
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $CategoryCode[$count] = $Result['CategoryCode'];
        $Price[$count] = $Result['Price'];
        $count++;
    }

    for($i=0;$i<$count;$i++){
        $Sql_Insert = "INSERT INTO category_price_time (DocNo,xDate,HptCode,CategoryCode,Price,Cnt) VALUES ('$DocNo','$xDate','$HptCode',".$CategoryCode[$i].",".$Price[$i].",$count)";
        mysqli_query($conn, $Sql_Insert);
    }

    // -------------------------------
    $insert_alert = "INSERT INTO alert_mail_price (DocNo, HptCode, day_30, day_7) VALUES ('$DocNo', '$HptCode', 0, 0) ";
    mysqli_query($conn, $insert_alert);
    // -------------------------------

    if($count>0){
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return[0]['RowID'] = "";
        $return[0]['HptName'] = "";
        $return[0]['MainCategoryName'] = "";
        $return[0]['CategoryName'] = "";
        $return[0]['Price'] = "";
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        $return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $lang = $_SESSION['lang'];
    if($HptCode != null){
      $Sql="SELECT category_price_time.DocNo,category_price_time.xDate,site.HptCode,site.HptName
        FROM category_price_time
        INNER JOIN site ON category_price_time.HptCode = site.HptCode
        WHERE site.HptCode = '$HptCode' AND category_price_time.`Status` = 0 
        GROUP BY site.HptCode,category_price_time.xDate,category_price_time.DocNo
        ORDER BY category_price_time.xDate ASC";
    }else{
      $Sql="SELECT category_price_time.DocNo,category_price_time.xDate,site.HptCode,site.HptName
        FROM category_price_time
        INNER JOIN site ON category_price_time.HptCode = site.HptCode
        WHERE category_price_time.`Status` = 0 
        GROUP BY site.HptCode,category_price_time.xDate,category_price_time.DocNo
        ORDER BY category_price_time.xDate ASC";
    }
    
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($lang =='en'){
        $date2 = explode("-", $Result['xDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
      }else if ($lang == 'th'){
        $date2 = explode("-", $Result['xDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
      }
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['xDate'] = $newdate;
        $return[$count]['HptName'] = $Result['HptName'];
        $return[$count]['HptCode'] = $Result['HptCode'];
        $count++;
    }
    $return['xCnt'] = $count;
    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "failed";
        $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowItem1($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  $CgMainID = $DATA['CgMainID'];
  $CgSubID = $DATA['CgSubID'];

  $Sql = "SELECT category_price.RowID,site.HptName,item_main_category.MainCategoryName,item_category.CategoryName,category_price.Price
  FROM category_price
  INNER JOIN site ON category_price.HptCode = site.HptCode
  INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode
  INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode ";
  if( $xHptCode != null && $CgMainID == null && $CgSubID == null ){
    $Sql .= "WHERE site.HptCode = '$xHptCode'";
  }else if($xHptCode != null && $CgMainID != null && $CgSubID == null){
    $Sql .= "WHERE site.HptCode = '$xHptCode' AND item_main_category.MainCategoryCode = $CgMainID";
  }else if($xHptCode != null && $CgMainID != null && $CgSubID != null ){
    $Sql .= "WHERE site.HptCode = '$xHptCode' AND item_main_category.MainCategoryCode = $CgMainID AND category_price.CategoryCode = $CgSubID";
  }else if($xHptCode == null && $CgMainID != null && $CgSubID == null ){
    $Sql .= "WHERE item_main_category.MainCategoryCode = $CgMainID";
  }else if($xHptCode == null && $CgMainID != null && $CgSubID != null ){
    $Sql .= "WHERE item_main_category.MainCategoryCode = $CgMainID AND category_price.CategoryCode = $CgSubID";
  }else if($xHptCode == null && $CgMainID == null && $CgSubID != null ){
    $Sql .= "WHERE category_price.CategoryCode = $CgSubID";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
	$return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['Price'] = $Result['Price'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem1";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = 'notfound';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem2($conn, $DATA)
{
    $count = 0;
    $xHptCode = $DATA['HptCode'];
    $DocNo = $DATA['DocNo'];
    if($xHptCode=="")  $xHptCode = 1;
    $Keyword = $DATA['Keyword'];
    if($Keyword=="")  $Keyword = "%";

    $Sql = "SELECT
        category_price_time.RowID,
        site.HptName,
        item_main_category.MainCategoryName,
        item_category.CategoryName,
        category_price_time.Price,
        category_price_time.CategoryCode
        FROM category_price_time
        INNER JOIN item_category ON category_price_time.CategoryCode = item_category.CategoryCode
        INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
        INNER JOIN site ON category_price_time.HptCode = site.HptCode 
        WHERE category_price_time.DocNo = '$DocNo' AND item_category.CategoryName LIKE '%$Keyword%'
        ORDER BY item_main_category.MainCategoryCode DESC, item_category.CategoryCode ASC";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['RowID'] = $Result['RowID'];
        $return[$count]['HptName'] = $Result['HptName'];
        $return[$count]['CategoryCode'] = $Result['CategoryCode'];
        $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
        $return[$count]['CategoryName'] = $Result['CategoryName'];
        $return[$count]['Price'] = $Result['Price'];
        $count++;
    }

    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function getdetail($conn, $DATA)
{
  $count = 0;
  $RowID = $DATA['RowID'];
  //---------------HERE------------------//
  $Sql = "SELECT category_price.RowID,site.HptName,item_main_category.MainCategoryName,item_category.CategoryName,category_price.Price
    FROM category_price
    INNER JOIN site ON category_price.HptCode = site.HptCode
    INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode
    INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
    WHERE category_price.RowID = $RowID";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['RowID'] = $Result['RowID'];
      $return['HptName'] = $Result['HptName'];
      $return['MainCategoryName'] = $Result['MainCategoryName'];
      $return['CategoryName'] = $Result['CategoryName'];
      $return['Price'] = $Result['Price'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getHotpital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function getCategoryMain($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_main_category.MainCategoryCode,
          item_main_category.MainCategoryName
          FROM item_main_category
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['MainCategoryCode']  = $Result['MainCategoryCode'];
    $return[$count]['MainCategoryName']  = $Result['MainCategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategoryMain";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getCategorySub($conn, $DATA)
{
  $count = 0;
  $CgrID = $DATA['CgrID'];
  $Sql = "SELECT item_category.CategoryCode,item_category.CategoryName
  FROM item_main_category
  INNER JOIN item_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
  WHERE item_category.IsStatus = 0
  AND item_category.MainCategoryCode = $CgrID";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode']  = $Result['CategoryCode'];
    $return[$count]['CategoryName']  = $Result['CategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategorySub";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function SavePrice($conn, $DATA)
{
  $RowID = $DATA['RowID'];
  $Price = $DATA['Price'];

  $Sql = "UPDATE category_price SET Price = $Price WHERE RowID = $RowID";
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "SavePrice";
    $return['msg'] = "Edit Success...";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "Edit Failed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SavePriceTime($conn, $DATA)
{
    $RowID = $DATA['RowID'];
    $Price = $DATA['Price'];
    $Sel = $DATA['Sel'];
    $DocNo = $DATA['DocNo'];

    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price_time WHERE category_price_time.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }

    $Sql = "UPDATE category_price_time SET Price = $Price WHERE RowID = $RowID";
    $return['sql'] = $Sql;
    if(mysqli_query($conn, $Sql)){
        $return['status'] = "success";
        $return['Cnt'] = $Cnt;
        $return['Sel'] = $Sel;
        $return['form'] = "SavePriceTime";
        $return['msg'] = "Save Success...";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "failed";
        $return['msg'] = "addfailed";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function CheckPrice($conn,$HptCode,$CategoryCode)
{
    $Cnt = 0;
    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    return $Cnt;
}

function UpdatePrice($conn, $DATA)
{
    $DocNo = $DATA['DocNo'];
    $CategoryCode = explode(',', $DATA['CategoryCode']);
    $Price = explode(',', $DATA['Price']);
    $RowId = explode(',', $DATA['RowId']);
    $limit = sizeof($CategoryCode, 0);
    $limitRow = sizeof($RowId, 0);
    $count = 0;
    
    $Sql = "SELECT category_price_time.HptCode,category_price_time.CategoryCode,category_price_time.Price
            FROM category_price_time
            WHERE category_price_time.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $HptCode = $Result['HptCode'];
        // $CategoryCode = $Result['CategoryCode'];
        // $Price = $Result['Price'];

        // if( CheckPrice($conn,$HptCode,$CategoryCode) == 0 ){
        //     $InsertSql = "INSERT INTO category_price (HptCode,CategoryCode,Price) VALUES ('$HptCode',$CategoryCode,$Price)";
        //     mysqli_query($conn, $InsertSql);
        // }else{
        //     $UpdateSql = "UPDATE category_price SET Price = $Price WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode";
        //     mysqli_query($conn, $UpdateSql);
        // }
        // $count++;
    }
    for($i=0; $i < $limit; $i++)
    {
        if( CheckPrice($conn,$HptCode,$CategoryCode[$i]) == 0 ){
            $InsertSql = "INSERT INTO category_price (HptCode,CategoryCode,Price) VALUES ('$HptCode',$CategoryCode[$i],$Price[$i])";
            mysqli_query($conn, $InsertSql);
        }else{
            $UpdateSql = "UPDATE category_price SET Price = $Price[$i] WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode[$i]";
            mysqli_query($conn, $UpdateSql);
        }
    }

    for($i=0; $i < $limitRow; $i++)
    {
      $Sql = "UPDATE category_price_time SET Price = $Price[$i] WHERE DocNo ='$DocNo' AND RowID = $RowId[$i]";
      $meQuery = mysqli_query($conn, $Sql);
    }

    $return['xCnt'] = $count;

        $return['status'] = "success";
        $return['form'] = "UpdatePrice";
        $return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
}

function CancelDocNo($conn,$DATA)
{
    $DocNo = $DATA['DocNo'];
    $Sql = "UPDATE category_price_time SET Status = 1 WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    $return['status'] = "success";
    $return['form'] = "CancelDocNo";
    mysqli_close($conn);
    echo json_encode($return);
}

function saveDoc($conn, $DATA)
{
  $DocNo = $DATA['DocNo'];
  $RowId = explode(',', $DATA['RowId']);
  $Price = explode(',', $DATA['Price']);
  $limit = sizeof($RowId, 0);

  for($i=0; $i < $limit; $i++)
  {
    $Sql = "UPDATE category_price_time SET Price = $Price[$i] WHERE DocNo ='$DocNo' AND RowID = $RowId[$i]";
    $meQuery = mysqli_query($conn, $Sql);
  }
  // $return['Sql'] = $Sql;
  // $return['docno'] = $DocNo;
  // $return['RowId'] = $RowId;
  // $return['limit'] = $limit;
  // $return['Price'] = $Price;
  // echo json_encode($return);
}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);
      if ($DATA['STATUS'] == 'CreateDoc') {
          CreateDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowDoc') {
          ShowDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem1') {
          ShowItem1($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem2') {
        ShowItem2($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItemPrice') {
        ShowItemPrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'UpdatePrice') {
        UpdatePrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategoryMain') {
        getCategoryMain($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategorySub') {
        getCategorySub($conn, $DATA);
      }else if ($DATA['STATUS'] == 'SavePrice') {
        SavePrice($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SavePriceTime') {
          SavePriceTime($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelDocNo') {
          CancelDocNo($conn,$DATA);
      }else if ($DATA['STATUS'] == 'saveDoc') {
        saveDoc($conn,$DATA);
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
