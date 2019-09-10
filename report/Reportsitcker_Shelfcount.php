<?php

date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
require '../connect/connect.php';

$DocNo = $_GET['DocNo'];
$lang = $_GET['lang'];
$UserID = $_SESSION['PmID'];
$count = 0;

$Sql = "SELECT
  shelfcount_detail.ItemCode,
  item.ItemName,
  item_unit.UnitName,
  shelfcount_detail.ParQty,
  shelfcount_detail.CcQty,
  shelfcount_detail.TotalQty,
  users.FName,
	(
		SELECT users.FName FROM users WHERE ID = 98
	) AS UserC
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
	INNER JOIN users ON users.ID = shelfcount.Modify_Code
  WHERE shelfcount_detail.DocNo = '$DocNo'
  GROUP BY item.ItemCode
  ORDER BY item.ItemName ASC ";


  
  try {
    $fp = pfsockopen("192.168.1.61",9100);
    
    $print_data = "SIZE 50 mm,48 mm  \r\n";
    fputs($fp, $print_data);
    $print_data = "GAP 3 mm,0 mm  \r\n";
    fputs($fp, $print_data);
    $print_data = "DIRECTION 1,0  \r\n";
    fputs($fp, $print_data);
    $print_data = "CLS  \r\n";
    fputs($fp, $print_data);

    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode = $Result['ItemCode'];
      $ItemName = $Result['ItemName'];
      $UnitName = $Result['UnitName'];
      $TotalQty = $Result['TotalQty'];
      $FName = $Result['FName'];
      $UserC = $Result['UserC'];
      $dataQR = $ItemCode;
      $print_data = "TEXT 50 ,20 ,\"2\",0,1,1,\"$ItemName\" \r\n";
      fputs($fp, $print_data);

      $print_data = "TEXT 50 ,50 ,\"2\",0,1,1,\"$ItemCode\" \r\n";
      fputs($fp, $print_data);

      $print_data = "QRCODE 50,100,Q,4,A,0,\"$dataQR\" \r\n";
      fputs($fp, $print_data);

      $print_data = "PRINT 1,1 \r\n";
      fputs($fp, $print_data);
    }   
    fclose($fp);

    array_push($resArray, array('Label_Type' => $xIpaddress));
    echo json_encode(array("result" => $resArray));
  } catch (Exception $e) {
    array_push($resArray, array('Label_Type' => 'Caught exception: ', $e->getMessage(), "\n"));
    echo json_encode(array("result" => $resArray));
  }