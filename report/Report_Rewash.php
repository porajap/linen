<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/",$eDate);
$eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];

$dept = $_GET['dept'];

$language = $_GET['lang'];
if($language=="en"){
  $language = "en";
}else{
  $language = "th";
}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);

class PDF extends FPDF
{
  function Header()
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    $language = $_GET['lang'];
    $datetime = new DatetimeTH();
   
    $eDate = $_GET['eDate'];
    $eDate = explode("/",$eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    $edate = $eDate[0]." ".$datetime->getTHmonthFromnum($eDate[1])." พ.ศ. ".$datetime->getTHyear($eDate[2]);

    if($this->page==1){
      // Move to the right
      $this->SetFont('THSarabun','',12);
      $this->Cell(190,10,iconv("UTF-8","TIS-620",$array['printdate'][$language].' '.$printdate),0,0,'R');
      $this->Ln(10);
      // Title
      $image="../report_linen/images/Nhealth_linen 4.0.png";
      $this-> Image($image,10,10,43,15);
      $this->Ln(10);
      $this->SetFont('THSarabun','b',21);
      $this->Cell(80);
      $this->Cell(30,10,iconv("UTF-8","TIS-620",$array['r6'][$language]),0,0,'C');
      // Line break
      $this->Ln(10);
    }else{
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {if ($this->isFinished) {
    $xml = simplexml_load_file('../report_linen/xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $this->SetFont('THSarabun', '', 10);
    $this->SetY(-27);
    $language = $_GET['lang'];
    $this->SetFont('THSarabun', 'b', 11);
    
    $this->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['comlinen'][$language] . "............................................"), 0, 0, 'L');
    $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['comlaundry'][$language] . "........................................"), 0, 0, 'L');
    $this->Ln(7);
    
    $this->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . "..................................................................."), 0, 0, 'L');
    $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . ".........................................................."), 0, 0, 'L');
    $this->Ln(7);
  }
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('THSarabun', '', 9);
      // Page number
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "") . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {$xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    $language = $_GET['lang'];
    $total = 0;
    $wtotal = 0;
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun','b',16);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun','',14);
    if(is_array($data)){
    foreach($data as $data=>$inner_array){
      $this->Cell($w[0],6,iconv("UTF-8","TIS-620",$count+1),1,0,'C');
      $this->Cell($w[1],6,iconv("UTF-8","TIS-620","  ".$inner_array[$field[1]]),1,0,'L');
      $this->Cell($w[2],6,iconv("UTF-8","TIS-620",$inner_array[$field[2]]." "),1,0,'C');
      $this->Cell($w[3],6,iconv("UTF-8","TIS-620",$inner_array[$field[4]]." "),1,0,'C');
      $this->Cell($w[4],6,iconv("UTF-8","TIS-620",$inner_array[$field[3]]." "),1,0,'C');
      $this->Ln();
      $total=$inner_array[$field[3]] + $total;
      $count++;
    }
  }

  $this->Cell($w[0]+$w[1]+$w[2]+$w[3],6,iconv("UTF-8","TIS-620",$array['total'][$language]),1,0,'C');
  $this->Cell($w[3],6,iconv("UTF-8","TIS-620",number_format($total,2)." "),1,0,'C');
  $this->Ln();  
  }

  }

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

$DocNo = $_GET['DocNo'];

// Using Coding
$pdf->AddPage();

if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
  $Perfix = THPerfix;
  $Name = THName;
  $LName = THLName;
} else {
  $HptName = HptName;
  $FacName = FacName;
  $Perfix = EngPerfix;
  $Name = EngName;
  $LName = EngLName;
}

$Sql = "SELECT   site.$HptName,
        department.DepName,
        clean.DocNo,
        DATE_FORMAT(clean.DocDate,'%d-%m-%Y')AS DocDate,
        clean.Total,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
        TIME(clean.Modify_Date) AS xTime,
        clean.RefDocNo
        FROM clean
        INNER JOIN department ON clean.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        INNER JOIN users ON clean.Modify_Code = users.ID
        WHERE clean.RefDocNo = '$DocNo'";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $RefDocNo = $Result['RefDocNo'];
}

$pdf->SetFont('THSarabun','b',16);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['hospital'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620"," : ".$HptName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['department'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620"," : ".$DepName),0,0,'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docno'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620"," : ".$DocNo),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docdate'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620"," : ".$DocDate),0,0,'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['refdocno'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620"," : ".$RefDocNo),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['time'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620"," : ".$xTime),0,0,'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['user'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620"," : ".$FirstName),0,0,'L');

$pdf->Ln();
$pdf->Ln(5);
$query = "SELECT
          clean_detail.ItemCode,
          item.ItemName,
          item_unit.UnitName,
          sum(clean_detail.Qty) as Qty ,
          sum(clean_detail.Weight) as Weight
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          INNER JOIN clean_detail ON clean_detail.ItemCode = item.ItemCode
          WHERE clean_detail.DocNo = '$DocNo'
		       GROUP BY item.ItemCode
          ORDER BY item.ItemName ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemName,Qty,Weight,UnitName";
// Table header
$header = array($array['no'][$language],$array['itemname'][$language],$array['qty'][$language],$array['unit'][$language],$array['weight'][$language]);
// width of column table
$width = array(15,60,35,40,40); 
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',12);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->isFinished = true;
$ddate = date('d_m_Y');
$pdf->Output('I','Report_Rewash_'.$ddate.'.pdf');
?>
