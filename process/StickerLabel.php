<?php

  date_default_timezone_set("Asia/Bangkok");
  $xDate = date('Y-m-d');

  try {
    $fp = pfsockopen("192.168.1.61",9100);

    $print_data = "SIZE 50 mm,48 mm  \r\n";
    fputs($fp, $print_data);
    $print_data = "GAP 3 mm,0 mm  \r\n";
    fputs($fp, $print_data);
    $print_data = "DIRECTION 0  \r\n";
    fputs($fp, $print_data);
    $print_data = "CLS  \r\n";
    fputs($fp, $print_data);

    for($i=0;$i<1;$i++){
      $print_data = "TEXT 50 ,20 ,\"2\",0,1,1,\"ทดสอบการพิมพ์...\" \r\n";
      fputs($fp, $print_data);
      

      $print_data = "QRCODE 50,100,H,4,A,0,\"ABCabc123\" \r\n";
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

//}
?>