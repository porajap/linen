<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function ShowItem($conn, $DATA)
{
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $HptCode = $DATA['HptCode'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $column = $DATA['column']==null?'itemDate':$DATA['column'];
  $sort = $DATA['sort']==null?'DESC':$DATA['sort'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' 
          WHEN '7' THEN '3XL' 
          WHEN '8' THEN '6XL' 
          WHEN '9' THEN 'NO SIZE' END AS SizeCode,
            item.Weight,
            item.IsClean,
            item.isset,
            item.Tdas
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '' && $HptCode != '') {
      if($HptCode != ''  && $Catagory=='' ){
        $Sql .= " WHERE  HptCode = '$HptCode' AND NOT (item.ItemCode = '00001' AND item.ItemCode = '00002' AND item.ItemCode = '00003') AND item.IsActive =1";
      }else if($HptCode != ''  && $Catagory !=''){
        $Sql .= " WHERE item.CategoryCode = $Catagory AND HptCode = '$HptCode'AND NOT (item.ItemCode = '00001' AND item.ItemCode = '00002' AND item.ItemCode = '00003') AND item.IsActive =1";
      }else if($HptCode != ''  && $Catagory=='' ){
        $Sql .= " WHERE HptCode = '$HptCode'AND NOT (item.ItemCode = '00001' AND item.ItemCode = '00002' AND item.ItemCode = '00003') AND item.IsActive =1";
      }else if( $Catagory !=''){
      $Sql .= " WHERE item.CategoryCode = $Catagory  AND HptCode = '$HptCode' AND item.IsActive =1";
      }
  } else {
    $Sql .= " WHERE item.HptCode = '$HptCode' AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') AND item.IsActive = 1 ";
  }

  $return['sql']    = $Sql;
  $meQuery       = mysqli_query($conn,$Sql);
  $Num_Rows   = mysqli_num_rows($meQuery );

  $Per_Page = 100;  

  $Page =  $DATA["Page"];
  if(! $DATA["Page"])
  {
    $Page=1;
  }
  $Prev_Page = $Page-1;
  $Next_Page = $Page+1;

  $Page_Start = (($Per_Page*$Page)-$Per_Page);

if($Num_Rows<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($Num_Rows % $Per_Page)==0)
{
	$Num_Pages =($Num_Rows/$Per_Page) ;
}
else
{
	$Num_Pages =($Num_Rows/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}
$return['Num_Rows']     	    = $Num_Rows;
$return['Num_Pages']     	    = $Num_Pages;
$return['Prev_Page']      	    = $Prev_Page;
$return['Next_Page']      	    = $Next_Page;
$return['Page']      	               = $Page;

  $Sql .= " ORDER BY item.$column $sort   LIMIT $Page_Start , $Per_Page ";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode']           = $Result['ItemCode'];
    $return[$count]['ItemName']         = $Result['ItemName'];
    $return[$count]['CategoryName']   = $Result['CategoryName'];
    $return[$count]['UnitName']           = $Result['UnitName'];
    $return[$count]['SizeCode']           = $Result['SizeCode'];
    $return[$count]['Weight']             = $Result['Weight'];
    $return[$count]['IsDirtyBag']       = $Result['IsClean']==null?0:$Result['IsClean'];
    $return[$count]['isset']                = $Result['isset']==null?0:$Result['isset'];
    $return[$count]['Tdas']               = $Result['Tdas']==null?0:$Result['Tdas'];
    $count++;
  }
  if($column == 'itemDate' && $sort=='ASC'){
    $return['down'] = 1;
  }
  $return['countx'] =$count;

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";

    echo json_encode($return);
    mysqli_close($conn);
    die;

  } else {
    $return['form'] = "ShowItem";
    $return['status'] = "success";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function ShowItem2($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.Weight,
            item.IsDirtyBag,
            item.isset
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory  ";
  } else {
    $Sql .= " WHERE  item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%'";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['IsDirtyBag'] = $Result['IsDirtyBag']==null?0:$Result['isset'];
    $return[$count]['isset'] = $Result['isset']==null?0:$Result['isset'];
    $count++;
  }
  $return['RowCount'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem2";
    $return['mItemCode'] = $DATA['mItemCode'];
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItem2";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function ShowItem_Active_0($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.Weight,
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND IsActive = 0 ORDER BY item.ItemCode ASC";
  } else {
    $Sql .= " WHERE item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
                OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%' AND IsActive = 0 ";
  }
  $return['sql'] = $Sql;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['Weight'] = $Result['Weight'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem_Active_0";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItem_Active_0";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
          WHERE item_unit.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getUnit";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getCatagory($conn, $DATA)
{
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  // var_dump($Maincat); die;
  if($maincatagory != null){
    $Sql = "SELECT
            item_category.CategoryCode,
            item_category.CategoryName,
            item_category.IsStatus
            FROM
            item_category
            WHERE  item_category.IsStatus = 0 ";
    }else{
            $Sql = "SELECT
            item_category.CategoryCode,
            item_category.CategoryName,
            item_category.IsStatus
            FROM
            item_category
            WHERE item_category.IsStatus = 0 ";
    }
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $count++;
  }

  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "getCatagory";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getCatagory2($conn, $DATA)
{
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  // var_dump($Maincat); die;
  if($maincatagory != null){
  $Sql = "SELECT
          item_category.CategoryCode,
          item_category.CategoryName,
          item_category.IsStatus
          FROM
          item_category
          WHERE item_category.IsStatus = 0 ";
  }else{
          $Sql = "SELECT
          item_category.CategoryCode,
          item_category.CategoryName,
          item_category.IsStatus
          FROM
          item_category
          WHERE item_category.IsStatus = 0 ";
  }
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $count++;
  }

  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "getCatagory2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function GetHospital($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $DATA["lang"];
  $count = 0;
  if($lang == 'en'){
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }else{
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }    
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HospitalName'] = $Result['HptName'];
    $return[$count]['HospitalCode'] = $Result['HptCode'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "GetHospital";
    $return[0]['PmID']  = $PmID;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getdetail($conn, $DATA)
{
  $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $countMP = 0;
  $ItemCode = $DATA['ItemCode'];
  // ====================================================================================
  // $Sql = "INSERT INTO item_multiple_unit (MpCode , UnitCode , Multiply , ItemCode , PriceUnit) VALUES ( 1 , 1 , 1 , '$ItemCode' , 1)";
  // $meQuery = mysqli_query($conn, $Sql);

  $Sql = "SELECT
          item.ItemCode,
          item.ItemName,
          item.CategoryCode,
          item_category.Categoryname,
          item.UnitCode,
          item_unit.UnitName,
          item.SizeCode,
          item.Weight,
          item.typeLinen,
          item.numPack,
          item_multiple_unit.RowID,
          U1.UnitName AS MpCode,
          U2.UnitName AS UnitName2,
          Multiply,PriceUnit,
          item.QtyPerUnit,
          item.UnitCode2,
          IsClean,
          Tdas,
          isset,
          HptCode
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          LEFT JOIN item_unit AS item_unit2 ON item.SizeCode = item_unit2.UnitCode
          LEFT JOIN item_multiple_unit ON item_multiple_unit.ItemCode = item.ItemCode
          LEFT JOIN item_unit AS U1 ON item_multiple_unit.UnitCode = U1.UnitCode
		  LEFT JOIN item_unit AS U2 ON item_multiple_unit.MpCode = U2.UnitCode
          WHERE item.ItemCode = '$ItemCode'";

  // var_dump($Sql); die;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode']         = $Result['ItemCode'];
    $return[$count]['ItemName']         = $Result['ItemName'];
    $return[$count]['CategoryCode']     = $Result['CategoryCode'];
    $return[$count]['Categoryname']     = $Result['Categoryname'];
    $return[$count]['UnitCode']         = $Result['UnitCode'];
    $return[$count]['SizeCode']         = $Result['SizeCode'];
    $return[$count]['Weight']           = $Result['Weight'];
    $return[$count]['typeLinen']        = $Result['typeLinen'];
    $return[$count]['numPack']          = $Result['numPack'];
    $return[$count]['RowID']            = $Result['RowID'];
    $return[$count]['HptCode']          = $Result['HptCode'];
    $return[$count]['MpCode']           = $Result['MpCode'];
    $return[$count]['UnitName2']        = $Result['UnitName2'];
    $return[$count]['Multiply']         = $Result['Multiply'];
    $return[$count]['PriceUnit']        = $Result['PriceUnit']==null?0:$Result['PriceUnit'];
    $return[$count]['QtyPerUnit']       = $Result['QtyPerUnit'];
    $return[$count]['sUnitName']        = $Result['UnitCode2'];
    $return[0]['IsDirtyBag']            = $Result['IsClean']==null?0:$Result['IsClean'];
    $return[0]['isset']                 = $Result['isset']==null?0:$Result['isset'];
    $return[0]['tdas']                  = $Result['Tdas']==null?0:$Result['Tdas'];
    $count++;
    if($Result['UnitName2'] != null){
      $countMP = 1 ;
    }
  }
  $return['RowCount'] = $count;
  $return['RowCountMP'] = $countMP;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.UnitCode,
          department.DepName,
          department.IsStatus
          FROM
          department";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']       = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function AddItem($conn, $DATA)
{
  $Userid = $_SESSION['Userid'];
  if($DATA['masterItem'] == 0){
    $Del = "DELETE FROM item_set WHERE mItemCode = '" . $DATA['ItemCode'] . "'";
    mysqli_query($conn, $Del);
  }
  $return['ItemName'] = $DATA['ItemName'];
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          item
          WHERE item.ItemCode = '" . $DATA["ItemCode"] . "'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }
  if ($boolcount != 0) {
    $Sql = "UPDATE item SET
            CategoryCode  = '" . $DATA['Catagory'] . "',
            ItemName      = '" . $DATA['ItemName'] . "',
            UnitCode      = '" . $DATA['UnitName'] . "',
            SizeCode      = '" . $DATA['SizeCode'] . "',
            Weight        = '" . $DATA['Weight'] . "',
            QtyPerUnit    = '" . $DATA['qpu'] . "',
            UnitCode2     = '" . $DATA['sUnit'] . "',
            IsClean       = '" . $DATA['xCenter'] . "',  
            Tdas          = '" . $DATA['tdas'] . "',
            isset         = ". $DATA['masterItem'].",
            HptCode       = '". $DATA['hospital']."',
            Modify_Date   = NOW(),
            Modify_Code   =  $Userid,
            typeLinen     = '". $DATA['typeLinen']."',
            numPack       = '". $DATA['numPack']."'
            WHERE ItemCode = '" . $DATA['ItemCode'] . "' ";
            $return['1'] = $Sql;
            $Select = "SELECT MpCode FROM item_multiple_unit WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
            $meQueryMp = mysqli_query($conn, $Select);
            while($ResultMp = mysqli_fetch_assoc($meQueryMp)) {
              $MpCode = $ResultMp['MpCode'];
              if($MpCode == $DATA['UnitName']){
                $Sql2 = "UPDATE item_multiple_unit  SET UnitCode = '" . $DATA['UnitName'] . "', PriceUnit = 1 WHERE ItemCode = '" . $DATA['ItemCode'] . "' AND MpCode = $MpCode";
              }else{
                $Sql2 = "UPDATE item_multiple_unit  SET UnitCode = '" . $DATA['UnitName'] . "' WHERE ItemCode = '" . $DATA['ItemCode'] . "' ";
              }
              mysqli_query($conn, $Sql2);
            }
            
    if (mysqli_query($conn, $Sql) || mysqli_query($conn, $Sql2)) {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

// function CreateItemCode($conn, $DATA)
// {
//   $ItemCode = "";
//   $boolcount = 0;

//   $Sql = "    SELECT 		item_main_category.MainCategoryCode,SUBSTRING(MainCategoryName,1,3) AS MainCategoryName
//               FROM 		item_category,item_main_category
//               WHERE		item_category.CategoryCode=" . $DATA['Catagory'] . "
//               AND     item_main_category.MainCategoryCode = item_category.MainCategoryCode";
//   $meQuery = mysqli_query($conn, $Sql);
//   while ($Result = mysqli_fetch_assoc($meQuery)) {
//     $MainCatagory = $Result['MainCategoryCode'];
//     $MainCategoryName = $Result['MainCategoryName'];
//   }

//   if ($DATA['modeCode'] == '2') {
//     $Sql = "  SELECT 		CONCAT( LPAD('$MainCatagory', 2, 0),
//                                 LPAD('" . $DATA['Catagory'] . "', 2, 0),
//                                 LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(ItemCode,5,5),UNSIGNED INTEGER)),0)+1) ,5,0))
//                         AS  ItemCode
//               FROM 			item
//               WHERE 		ItemCode Like CONCAT( LPAD('$MainCatagory', 2, 0),
//                                               LPAD('" . $DATA['Catagory'] . "', 2, 0),
//                                             '%')
//               ORDER BY  ItemCode DESC LIMIT 1";

//     $meQuery = mysqli_query($conn, $Sql);
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       $ItemCode = $Result['ItemCode'];
//     }

//     if ($ItemCode != "") {
//       $boolcount = 1;
//     } else {
//       $boolcount = 0;
//     }
//   } else if ($DATA['modeCode'] == '1') {
//     if($DATA['hospitalCode'] !=""){
//     $preCode = $DATA['hospitalCode'] . "LP" . $MainCategoryName . $DATA['typeCode'] . $DATA['packCode'];
//     $Sql = "  SELECT 		CONCAT(LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(ItemCode,12,4),UNSIGNED INTEGER)),0)+1) ,4,0))
//               AS        ItemCode
//               FROM 			item
//               WHERE 		ItemCode Like CONCAT('$preCode',
//                                             '%')
//               ORDER BY  ItemCode DESC LIMIT 1";

//     $meQuery = mysqli_query($conn, $Sql);
//     }else{
//       $preCode = "";
//     }
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       $postCode = $Result['ItemCode'];
//     }
//     $ItemCode = $preCode . $postCode;
//     if ($postCode != "") {
//       $boolcount = 1;
//     } else {
//       $boolcount = 0;
//     }
//   } else {
//     $ItemCode = "";
//     $boolcount = 1;
//   }

//   if ($boolcount == 1) {
//     $return['status'] = "success";
//     $return['form'] = "CreateItemCode";
//     $return['ItemCode'] = $ItemCode;

//     echo json_encode($return);
//     mysqli_close($conn);
//     die;
//   } else {
//     // $return['status'] = "failed";
//     // $return['msg'] = "editfailed";
//     echo json_encode($return);
//     mysqli_close($conn);
//     die;
//   }
// }

function NewItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count    = 0;
  $Userid   = $_SESSION['Userid'];
  $HptCode  = $_SESSION['HptCode'];
  $HptCode2 = $DATA['HptCode'];
  $return['$HptCode'] = $HptCode;

  // หาแผนกกลาง
  $Sql5 = "SELECT DepCode FROM department WHERE IsDefault = 1 AND HptCode ='$HptCode2'";
  $meQuery5 = mysqli_query($conn, $Sql5);
  while ($Result5 = mysqli_fetch_assoc($meQuery5)) {
    $DepCode = $Result5['DepCode'];
  }

  $insert ="INSERT INTO par_item_stock( ItemCode , DepCode , ParQty , TotalQty , HptCode) VALUES ('" . $DATA['ItemCode'] . "', '$DepCode' , 0 , 0 , '$HptCode2')";
  mysqli_query($conn, $insert);



  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          item
          WHERE item.ItemCode = '" . $DATA["ItemCode"] . "'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }

  $Sqlz = "SELECT category_price.Price
              FROM    item,item_stock,department,category_price
              WHERE item.ItemCode = '" . $DATA['ItemCode'] . "'
              AND category_price.CategoryCode = item.CategoryCode
              AND item_stock.ItemCode = item.ItemCode
              AND item_stock.DepCode = department.DepCode
              AND department.HptCode = '$HptCode'
              AND category_price.HptCode = '$HptCode'
              GROUP BY item.ItemCode";
  //$return['sql'] = $Sqlz;

  $meQuery = mysqli_query($conn, $Sqlz);
  $Result = mysqli_fetch_assoc($meQuery);
  $CusPrice = $Result['Price'] == null ? 0 : $Result['Price'];


  if ($boolcount == 0) {
    $count = 0;
    $Sql = "INSERT INTO item(
            ItemCode,
            CategoryCode,
            ItemName,
            UnitCode,
            SizeCode,
            Weight,
            IsActive,
            QtyPerUnit,
            UnitCode2,
            IsClean,
            itemDate,
            Tdas,
            isset,
            HptCode,
            Modify_Date,
            Modify_Code,
            typeLinen,
            numPack
           )
            VALUES
            (
              '" . $DATA['ItemCode'] . "',
              '" . $DATA['Catagory'] . "',
              '" . $DATA['ItemName'] . "',
              '" . $DATA['UnitName'] . "',
              '" . $DATA['SizeCode'] . "',
              '" . $DATA['Weight'] . "',
              1,
              '" . $DATA['qpu'] . "',
              '" . $DATA['sUnit'] . "',
              '" . $DATA['xCenter'] . "',
              NOW(),
              '" . $DATA['tdas'] . "',
              '" . $DATA['masterItem'] . "',
              '" . $DATA['HptCode'] . "',
              NOW(),
              $Userid ,        
              '" . $DATA['typeLinen'] . "',
              '" . $DATA['numPack'] . "'


            )
    ";

    $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
    ( '" . $DATA['UnitName'] . "',  '" . $DATA['UnitName'] . "', 1, '" . $DATA['ItemCode'] . "', 1) ";

    if (mysqli_query($conn, $Sql) && mysqli_query($conn, $Sql2)) {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "addsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "addfailed1";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "addfailed2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ActiveItem($conn, $DATA)
{
  if ($DATA["ItemCode"] != "") {
    $Sql = "UPDATE item SET IsActive = 1 WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "ActiveItem";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function AddUnit($conn, $DATA)
{
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  $MpCode = $DATA['MpCode'];
  $UnitCode = $DATA['UnitCode'];
  $Multiply = $DATA['Multiply'];
  $priceunit = $DATA['priceunit'];

  $countM = "SELECT COUNT(*) as cnt FROM item_multiple_unit WHERE MpCode = $MpCode AND UnitCode = $UnitCode AND ItemCode = '$ItemCode'";
  $MQuery = mysqli_query($conn, $countM);
  $return['sql'] = $countM;
  while ($MResult = mysqli_fetch_assoc($MQuery)) {

    if ($MResult['cnt'] == 0) {
      if($UnitCode == $MpCode){
        $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
        ($MpCode, $UnitCode, $Multiply, '$ItemCode' , 1) ";
      }else{
          $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
            ($MpCode, $UnitCode, $Multiply, '$ItemCode' , $priceunit) ";
      }
      mysqli_query($conn, $Sql2);
    } else {
      if($UnitCode == $MpCode){
        $Sql1 = "UPDATE item_multiple_unit SET  MpCode = $MpCode , UnitCode = $UnitCode , Multiply = $Multiply , ItemCode = '$ItemCode' , PriceUnit = $priceunit
                WHERE ItemCode = '$ItemCode' AND MpCode = $MpCode AND UnitCode  = $UnitCode  ";
      }else{
        $Sql1 = "UPDATE item_multiple_unit SET  MpCode = $MpCode , UnitCode = $UnitCode , Multiply = $Multiply , ItemCode = '$ItemCode' , PriceUnit = $priceunit
                WHERE ItemCode = '$ItemCode' AND MpCode = $MpCode AND UnitCode  = $UnitCode  ";
      }
      mysqli_query($conn, $Sql1);
    }
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "AddUnit";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function EditItem($conn, $DATA)
{
  $count = 0;
  if ($DATA["UnitCode"] != "") {
    $Sql = "UPDATE item_Unit SET
            UnitCode = '" . $DATA['UnitCode'] . "',
            UnitName = '" . $DATA['UnitName'] . "'
            WHERE UnitCode = " . $DATA['UnitCode'] . "
    ";
    // var_dump($Sql); die;
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if ($DATA["ItemCode"] != "") {
    $Sql1 = "UPDATE item SET IsActive = 0  WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
            
    $Sql2 = "DELETE FROM item_multiple_unit WHERE ItemCode = '" . $DATA['ItemCode'] . "'";

    $Sql3 = "DELETE FROM par_item_stock WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    //$return['Sql'] = $Sql;
    if (mysqli_query($conn, $Sql1)&& mysqli_query($conn, $Sql2)&& mysqli_query($conn, $Sql3)) {
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function DeleteUnit($conn, $DATA)
{
  $count = 0;
  if ($DATA["RowID"] != "") {
    $Sql = "DELETE FROM item_multiple_unit
            WHERE RowID = " . $DATA['RowID'] . "
    ";
    // var_dump($Sql); die;
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "CancelUnit";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItemMaster($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $column = $DATA['column']==null?'ItemCode':$DATA['column'];
  $sort = $DATA['sort']==null?'DESC':$DATA['sort'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.Weight,
            item.IsDirtyBag
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory  AND item.isset = 1";
  } else {
    $Sql .= " WHERE item_main_category.MainCategoryCode = $maincatagory AND item.isset = 1 AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $Sql .= " ORDER BY item.$column $sort";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['IsDirtyBag'] = $Result['IsDirtyBag']==null?0:$Result['IsDirtyBag'];
    $count++;
  }
  $return['CountRow'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItemMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItemMaster";
    $return['status'] = "success";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function ShowItemMaster2($conn, $DATA)
{ 
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $Sql = "SELECT
            item.ItemCode,  

            item.ItemName,

            item_category.CategoryName,

            item_unit.UnitName,

          CASE item.
          
              WHEN '1' THEN 'SS'
              WHEN '2' THEN 'S'
              WHEN '3' THEN 'M'
              WHEN '4' THEN 'L'
              WHEN '5' THEN 'XL'
              WHEN '6' THEN 'XXL' END AS SizeCode,

            item.Weight,

            item.Picture,

            item.IsDirtyBag

          FROM item

          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode

          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory  AND item.isset = 1";
  } else {
    $Sql .= " WHERE item_main_category.MainCategoryCode = $maincatagory AND item.isset = 1 AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['IsDirtyBag'] = $Result['IsDirtyBag']==null?0:$Result['IsDirtyBag'];
    $count++;
  }
  $return['CountRow'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['mItemCode'] = $ItemCode;
    $return['form'] = "ShowItemMaster2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItemMaster2";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getdetailMaster($conn, $DATA)
{
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  // ====================================================================================

  $countMaster = 0;
  $SqlMaster = "SELECT 
      item_set.RowID, item_set.mItemCode, item_set.ItemCode, item.ItemName, item_set.Qty
    FROM item_set 
    INNER JOIN item ON item.ItemCode = item_set.ItemCode
    WHERE item_set.mItemCode = '$ItemCode' OR item_set.ItemCode = '$ItemCode'";
  $masterQuery = mysqli_query($conn, $SqlMaster);
  while ($Result = mysqli_fetch_assoc($masterQuery)) {
    $return[$countMaster]['RowID'] = $Result['RowID'];
    $return[$countMaster]['ItemCode'] = $Result['ItemCode'];
    $mItemCode = $Result['mItemCode'];
    $return[$countMaster]['ItemName'] = $Result['ItemName'];
    $return[$countMaster]['Qty'] = $Result['Qty'];
    $countMaster++;

  }
  $return['RowMaster'] = $countMaster;
  $return['mItemCode'] = $ItemCode;

  if ($countMaster > 0) {
    $return['status'] = "success";
    $return['mItemCode'] = $DATA['ItemCode'];
    $return['form'] = "getdetailMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "getdetailMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function deleteMaster($conn, $DATA){
  $RowID = explode("," , $DATA['ArraychkArrayRow']);
  $ItemCode = $DATA['ItemCode'];
  $limit = sizeof($RowID);

  for($i=0; $i<$limit; $i++){
    $Sql = "DELETE FROM item_set WHERE RowID = $RowID[$i]";
    mysqli_query($conn, $Sql);
  }


  $count = "SELECT COUNT(*) AS cnt FROM item_set WHERE mItemCode = '$ItemCode'";
  $Query = mysqli_query($conn, $count);
  while ($Result = mysqli_fetch_assoc($Query)) {
    if($Result['cnt'] == 0){
      $update = "UPDATE item SET isset = 0 WHERE ItemCode = '$ItemCode'";
      mysqli_query($conn, $update);
      ShowItemMaster($conn, $DATA);
    }
  }
}
function SaveQtyMaster($conn, $DATA){
  $RowID = $DATA['RowID'];
  $Qty = $DATA['Qty'];
  $Sql = "UPDATE item_set SET Qty = $Qty  WHERE RowID = $RowID";
  mysqli_query($conn, $Sql);
}
function ShowItemModal($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $ItemCode = $DATA['ItemCode'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND item.isset = 0 AND NOT item.ItemCode = ('$ItemCode') AND ItemCode NOT IN (SELECT ItemCode FROM item_set )";
  } else {
    $Sql .= " WHERE item.isset = 0 
    AND NOT item.ItemCode = ('$ItemCode') AND ItemCode NOT IN (SELECT ItemCode FROM item_set ) AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $count++;
  }
  $return['RowMaster'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItemModal";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItemModal";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function AddItemMaster($conn, $DATA){
  $ItemCodeMaster = $DATA['ItemCode'];
  $ItemCode = explode(',', $DATA['ArrayItemCode']);
  $Qty = explode(',', $DATA['ArrayQty']);
  $limit = sizeof($ItemCode, 0);
  $count = 0;
  $update = "UPDATE item SET isset = 1 WHERE ItemCode = '$ItemCodeMaster'";
  mysqli_query($conn, $update);

  for($i=0; $i < $limit; $i++)
  {
    $countMaster = "SELECT COUNT(ItemCode) AS cnt FROM item_set WHERE mItemCode = '$ItemCodeMaster' AND ItemCode = '$ItemCode[$i]'";
    $meQuery = mysqli_query($conn, $countMaster);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['cnt'] == 0){
        $Sql = "INSERT INTO item_set(mItemCode, ItemCode, Qty) VALUES ('$ItemCodeMaster', '$ItemCode[$i]', $Qty[$i])";
      }else{
        $Sql = "UPDATE item_set SET Qty = $Qty[$i] + Qty WHERE mItemCode = '$ItemCodeMaster' AND ItemCode = '$ItemCode[$i]'";
      }
      mysqli_query($conn, $Sql);
      // getdetailMaster($conn, $DATA);
    }
  }

  $delChild = "DELETE FROM item_set WHERE ItemCode = '$ItemCodeMaster'";
  mysqli_query($conn, $delChild);


      $return['status'] = "success";
      $return['ItemCodeMaster'] = $DATA['ItemCode'];
      $return['form'] = "AddItemMaster";
      echo json_encode($return);
      mysqli_close($conn);
      die;

}


if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'ShowItem_Active_0') {
    ShowItem_Active_0($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getCatagory') {
    getCatagory($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getUnit') {
    getUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddItem') {
    AddItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddUnit') {
    AddUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'EditItem') {
    EditItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'CancelItem') {
    CancelItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'DeleteUnit') {
    DeleteUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getdetail') {
    getdetail($conn, $DATA);
  } else if ($DATA['STATUS'] == 'GetHospital') {
    GetHospital($conn, $DATA);
  } 
  // else if ($DATA['STATUS'] == 'CreateItemCode') {
  //   CreateItemCode($conn, $DATA);
  // }
  
  else if ($DATA['STATUS'] == 'NewItem') {
    NewItem($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ActiveItem') {
    ActiveItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'checkItemCode') {
    checkItemCode($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItemMaster') {
    ShowItemMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'getdetailMaster') {
    getdetailMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'deleteMaster') {
    deleteMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'SaveQtyMaster') {
    SaveQtyMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItemModal') {
    ShowItemModal($conn, $DATA);
  }else if ($DATA['STATUS'] == 'AddItemMaster') {
    AddItemMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItemMaster2') {
    ShowItemMaster2($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItem2') {
    ShowItem2($conn, $DATA);
  }else if ($DATA['STATUS'] == 'getcatagory2') {
    getcatagory2($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
