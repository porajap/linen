<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
    if ($_POST['FUNC_NAME'] == 'getitemExcel') {
      getitemExcel($conn);
    } else if ($_POST['FUNC_NAME'] == 'GetSite') {
      GetSite($conn);
    } else if ($_POST['FUNC_NAME'] == 'CreateDocument') {
      CreateDocument($conn);
    }   else if ($_POST['FUNC_NAME'] == 'SaveDocument') {
      SaveDocument($conn);
    }  else if ($_POST['FUNC_NAME'] == 'ShowDocument') {
      ShowDocument($conn);
    }  else if ($_POST['FUNC_NAME'] == 'SelectDocument') {
      SelectDocument($conn);
    }  else if ($_POST['FUNC_NAME'] == 'DeleteDocument') {
      DeleteDocument($conn);
    }  else if ($_POST['FUNC_NAME'] == 'SaveDocumentNew') {
      SaveDocumentNew($conn);
    } 
}

function getitemExcel($conn)
{
    $Site = $_POST['Site'];
    $DocNo = $_POST['DocNo'];
    
    $return = array();

    $Sql = "SELECT
              item.ItemCode,
              item.ItemName 
            FROM
              item 
            WHERE
              item.isSAP = 1 
              AND HptCode = '$Site' ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($row = mysqli_fetch_assoc($meQuery)) {
       $itemcode =   $row['ItemCode'];
        // $insert = "INSERT INTO calexcel_detail SET ItemCode = '$itemcode' , DocNo = '$DocNo'  ";
        // mysqli_query($conn, $insert);
        $return[] = $row;
    }

    echo json_encode($return);
    mysqli_close($conn);
    die;
}

function GetSite($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;
  if($lang == 'en')
  {
    if($PmID != 1)
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }
  else
  {
    if($PmID != 1)
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }    
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery))
  {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function CreateDocument($conn)
{
  $hotpCode1 = $_SESSION['HptCode'];
  $Site = $_POST['Site'];
  $PmID = $_SESSION['PmID'];
  $return = array();

  if($PmID ==1){
    $hotpCode = $Site ;
  }else{
    $hotpCode = $hotpCode1 ;
  }


  $Sql = "SELECT CONCAT('EX',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo
  FROM calexcel
  WHERE DocNo Like CONCAT('EX',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  ORDER BY DocNo DESC LIMIT 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {


    $DocNo = $row["DocNo"];
            $Sql = "INSERT INTO calexcel (
              DocNo,
              DocDate,
              HptCode,
              IsCancel
            )
            VALUES
              (
                '$DocNo',
                NOW(),
                '$hotpCode',
                0
              ) ";
        mysqli_query($conn, $Sql);



        array_push($return, array(
          'DocNo' => $DocNo
      ));

  }

  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function SaveDocumentNew($conn)
{
  $insert_head = $_POST['insert_head'];
  $update_head = $_POST['update_head'];
  $DocNo = $_POST['DocNo'];

  $Sql_cnt  = "SELECT
                COUNT(ItemCode) as cnt
              FROM
                calexcel_detail
              WHERE   DocNo = '$DocNo' ";
  $meQuery_cnt = mysqli_query($conn, $Sql_cnt);
  $row_cnt = mysqli_fetch_assoc($meQuery_cnt);
  $cnt = $row_cnt["cnt"];


  if($cnt > 0){
    $Sql = $update_head;
    $text = "update success";
  }else{
    $Sql = $insert_head;
    $text = "insert success";
  }

  

                    
    if(mysqli_multi_query($conn, $Sql)){
      echo "1";
    }else{
      echo "2";
    }



  exit();
  mysqli_close($conn);
  die;
}

function SaveDocument($conn)
{
  $DocNo = $_POST['DocNo'];
  $itemcode = $_POST['itemcode'];
  $itemQty = $_POST['itemQty'];
  $itemname = $_POST['itemname'];

  $input1 = $itemQty[0][0];
  $input2 = $itemQty[0][1];
  $input3 = $itemQty[0][2];
  $input4 = $itemQty[0][3];
  $input5 = $itemQty[0][4];
  $input6 = $itemQty[0][5];
  $input7 = $itemQty[0][6];
  $input8 = $itemQty[0][7];
  $input9 = $itemQty[0][8];
  $input10 = $itemQty[0][9];
  $input11 = $itemQty[0][10];
  $input12 = $itemQty[0][11];
  $input13 = $itemQty[0][12];
  $input14 = $itemQty[0][13];
  $input15 = $itemQty[0][14];
  $input16 = $itemQty[0][15];
  $input17 = $itemQty[0][16];
  $input18 = $itemQty[0][17];
  $input19 = $itemQty[0][18];
  $input20 = $itemQty[0][19];
  $input21 = $itemQty[0][20];
  $input22 = $itemQty[0][21];
  $input23 = $itemQty[0][22];
  $input24 = $itemQty[0][23];
  $input25 = $itemQty[0][24];
  $input26 = $itemQty[0][25];
  $input27 = $itemQty[0][26];
  $input28 = $itemQty[0][27];
  $input29 = $itemQty[0][28];
  $input30 = $itemQty[0][29];
  $input31 = $itemQty[0][30];

  $Sql_cnt  = "SELECT
                COUNT(ItemCode) as cnt
              FROM
                calexcel_detail
              WHERE ItemCode = '$itemname[0]'  AND DocNo = '$DocNo' ";
  $meQuery_cnt = mysqli_query($conn, $Sql_cnt);
  $row_cnt = mysqli_fetch_assoc($meQuery_cnt);
  $cnt = $row_cnt["cnt"];

  
    if($cnt >= 1){
        $Sql = "UPDATE calexcel_detail SET  
        calexcel_detail.Input1 = '$input1', 
        calexcel_detail.Input2 = '$input2', 
        calexcel_detail.Input3 = '$input3',  
        calexcel_detail.Input4 = '$input4', 
        calexcel_detail.Input5 = '$input5', 
        calexcel_detail.Input6 = '$input6', 
        calexcel_detail.Input7 = '$input7', 
        calexcel_detail.Input8 = '$input8', 
        calexcel_detail.Input9 = '$input9', 
        calexcel_detail.Input10 = '$input10', 
        calexcel_detail.Input11 = '$input11', 
        calexcel_detail.Input12 = '$input12', 
        calexcel_detail.Input13 = '$input13', 
        calexcel_detail.Input15 = '$input14', 
        calexcel_detail.Input14 = '$input15', 
        calexcel_detail.Input16 = '$input16', 
        calexcel_detail.Input17 = '$input17', 
        calexcel_detail.Input18 = '$input18', 
        calexcel_detail.Input19 = '$input19', 
        calexcel_detail.Input20 = '$input20', 
        calexcel_detail.Input21 = '$input21', 
        calexcel_detail.Input22 = '$input22', 
        calexcel_detail.Input23 = '$input23', 
        calexcel_detail.Input24 = '$input24', 
        calexcel_detail.Input25 = '$input25',  
        calexcel_detail.Input26 = '$input26', 
        calexcel_detail.Input27 = '$input27', 
        calexcel_detail.Input28 = '$input28', 
        calexcel_detail.Input29 = '$input29', 
        calexcel_detail.Input30 = '$input30', 
        calexcel_detail.Input31 = '$input31'
        WHERE ItemCode = '$itemname[0]'  AND DocNo = '$DocNo' ";   
    }else{
        $Sql = "INSERT INTO calexcel_detail ( calexcel_detail.ItemCode,
        calexcel_detail.DocNo ,
        calexcel_detail.Input1, 
        calexcel_detail.Input2, 
        calexcel_detail.Input3, 
        calexcel_detail.Input4, 
        calexcel_detail.Input5, 
        calexcel_detail.Input6, 
        calexcel_detail.Input7, 
        calexcel_detail.Input8, 
        calexcel_detail.Input9, 
        calexcel_detail.Input10, 
        calexcel_detail.Input11, 
        calexcel_detail.Input12, 
        calexcel_detail.Input13, 
        calexcel_detail.Input15, 
        calexcel_detail.Input14, 
        calexcel_detail.Input16, 
        calexcel_detail.Input17, 
        calexcel_detail.Input18, 
        calexcel_detail.Input19, 
        calexcel_detail.Input20, 
        calexcel_detail.Input21, 
        calexcel_detail.Input22, 
        calexcel_detail.Input23, 
        calexcel_detail.Input24, 
        calexcel_detail.Input25, 
        calexcel_detail.Input26, 
        calexcel_detail.Input27, 
        calexcel_detail.Input28, 
        calexcel_detail.Input29, 
        calexcel_detail.Input30, 
        calexcel_detail.Input31  ) VALUES 
        (
          '$itemcode[0]', 
          '$DocNo', 
          '$input1',
          '$input2', 
          '$input3', 
          '$input4', 
          '$input5', 
          '$input6', 
          '$input7', 
          '$input8', 
          '$input9', 
          '$input10', 
          '$input11', 
          '$input12', 
          '$input13', 
          '$input14', 
          '$input15', 
          '$input16', 
          '$input17', 
          '$input18', 
          '$input19', 
          '$input20', 
          '$input21', 
          '$input22', 
          '$input23', 
          '$input24', 
          '$input25', 
          '$input26', 
          '$input27', 
          '$input28', 
          '$input29', 
          '$input30', 
          '$input31' ) ";
    }



                    
    mysqli_query($conn, $Sql);


  // echo '<pre>';
  // print_r($itemcode);
  // echo '</pre>';
  // echo '<pre>';
  // print_r($itemQty);
  // echo '</pre>';

  exit();

  mysqli_close($conn);
  die;
}

function ShowDocument($conn)
{
  $SiteSearch = $_POST['SiteSearch'];
  $return = array();

  $Sql = "SELECT
            calexcel.DocNo, 
            DATE(calexcel.DocDate) AS DocDate,
            calexcel.HptCode
          FROM
            calexcel
          WHERE calexcel.HptCode = '$SiteSearch' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
      $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function SelectDocument($conn)
{
  $DocNo = $_POST['DocNo'];
  $HptCode = $_POST['HptCode'];

  $return = array();

  $Sql = "SELECT
            item.ItemName,
            item.ItemCode, 
            calexcel_detail.Input1, 
            calexcel_detail.Input2, 
            calexcel_detail.Input3, 
            calexcel_detail.Input4, 
            calexcel_detail.Input5, 
            calexcel_detail.Input6, 
            calexcel_detail.Input8, 
            calexcel_detail.Input7, 
            calexcel_detail.Input9, 
            calexcel_detail.Input10, 
            calexcel_detail.Input11, 
            calexcel_detail.Input12, 
            calexcel_detail.Input15, 
            calexcel_detail.Input13, 
            calexcel_detail.Input14, 
            calexcel_detail.Input16, 
            calexcel_detail.Input17, 
            calexcel_detail.Input18, 
            calexcel_detail.Input19, 
            calexcel_detail.Input20, 
            calexcel_detail.Input21, 
            calexcel_detail.Input22, 
            calexcel_detail.Input23, 
            calexcel_detail.Input24, 
            calexcel_detail.Input26, 
            calexcel_detail.Input25, 
            calexcel_detail.Input27, 
            calexcel_detail.Input28, 
            calexcel_detail.Input29, 
            calexcel_detail.Input30, 
            calexcel_detail.Input31, 
            calexcel_detail.DocNo
          FROM
          calexcel_detail
          LEFT JOIN item ON calexcel_detail.ItemCode = item.ItemCode 
          WHERE DocNo  = '$DocNo' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
      $return['data'][] = $row;
  }


  $Sql2 = "SELECT
            SUM(calexcel_detail.Input1) AS sum_Input1,
            SUM(calexcel_detail.Input2) AS sum_Input2,
            SUM(calexcel_detail.Input3) AS sum_Input3,
            SUM(calexcel_detail.Input4) AS sum_Input4,
            SUM(calexcel_detail.Input5) AS sum_Input5,
            SUM(calexcel_detail.Input6) AS sum_Input6,
            SUM(calexcel_detail.Input7) AS sum_Input7,
            SUM(calexcel_detail.Input8) AS sum_Input8,
            SUM(calexcel_detail.Input9) AS sum_Input9,
            SUM(calexcel_detail.Input10) AS sum_Input10,
            SUM(calexcel_detail.Input11) AS sum_Input11,
            SUM(calexcel_detail.Input12) AS sum_Input12,
            SUM(calexcel_detail.Input13) AS sum_Input13,
            SUM(calexcel_detail.Input14) AS sum_Input14,
            SUM(calexcel_detail.Input15) AS sum_Input15,
            SUM(calexcel_detail.Input16) AS sum_Input16,
            SUM(calexcel_detail.Input17) AS sum_Input17,
            SUM(calexcel_detail.Input18) AS sum_Input18,
            SUM(calexcel_detail.Input19) AS sum_Input19,
            SUM(calexcel_detail.Input20) AS sum_Input20,
            SUM(calexcel_detail.Input21) AS sum_Input21,
            SUM(calexcel_detail.Input22) AS sum_Input22,
            SUM(calexcel_detail.Input23) AS sum_Input23,
            SUM(calexcel_detail.Input24) AS sum_Input24,
            SUM(calexcel_detail.Input25) AS sum_Input25,
            SUM(calexcel_detail.Input26) AS sum_Input26,
            SUM(calexcel_detail.Input27) AS sum_Input27,
            SUM(calexcel_detail.Input28) AS sum_Input28,
            SUM(calexcel_detail.Input29) AS sum_Input29,
            SUM(calexcel_detail.Input30) AS sum_Input30,
            SUM(calexcel_detail.Input31) AS sum_Input31

          FROM
            calexcel_detail
          WHERE DocNo = '$DocNo' ";

  $meQuery = mysqli_query($conn, $Sql2);
  while ($row2 = mysqli_fetch_assoc($meQuery)) {
      $return['datasum'][] = $row2;
  }







  $return['docno'][] = $DocNo;
  $return['HptCode'][] = $HptCode;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function DeleteDocument($conn)
{
  $DocNo = $_POST['DocNo'];


  $Sql1 = "DELETE FROM calexcel WHERE DocNo = '$DocNo' ";
  mysqli_query($conn, $Sql1);

  $Sql2 = "DELETE FROM calexcel_detail WHERE DocNo = '$DocNo' ";
  mysqli_query($conn, $Sql2);

  mysqli_close($conn);
  die;
}