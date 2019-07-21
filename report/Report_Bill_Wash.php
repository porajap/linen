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
    $datetime = new DatetimeTH();
    $eDate = $_GET['eDate'];
    $eDate = explode("/",$eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    $edate = $eDate[0]." ".$datetime->getTHmonthFromnum($eDate[1])." พ.ศ. ".$datetime->getTHyear($eDate[2]);

    if($this->page==1){
      // Move to the right
      $this->SetFont('THSarabun','',10);
      $this->Cell(190,10,iconv("UTF-8","TIS-620",$printdate),0,0,'R');
      $this->Ln(5);
      // Title
      $this->SetFont('THSarabun','b',14);
      $this->Cell(80);
      $this->Cell(30,10,iconv("UTF-8","TIS-620","Washing Bill"),0,0,'C');
      // Line break
      $this->Ln(7);
    }else{
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('THSarabun','i',9);
      // Page number
      $this->Cell(0,10,iconv("UTF-8","TIS-620",'').$this->PageNo().'/{nb}',0,0,'R');
  }

  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun','b',10);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun','',10);
    if(is_array($data)){
    foreach($data as $data=>$inner_array){
      $this->Cell($w[0],6,iconv("UTF-8","TIS-620",$count+1),1,0,'C');
      $this->Cell($w[1],6,iconv("UTF-8","TIS-620",$inner_array[$field[1]]),1,0,'C');
      $this->Cell($w[2],6,iconv("UTF-8","TIS-620","  ".$inner_array[$field[2]]),1,0,'L');
      $this->Cell($w[3],6,iconv("UTF-8","TIS-620",$inner_array[$field[3]]." "),1,0,'R');
      $this->Cell($w[4],6,iconv("UTF-8","TIS-620",$inner_array[$field[4]]),1,0,'C');
      $this->Cell($w[5],6,iconv("UTF-8","TIS-620",$inner_array[$field[5]]." "),1,0,'R');
      $this->Cell($w[6],6,iconv("UTF-8","TIS-620",$inner_array[$field[6]]." "),1,0,'R');
      $this->Ln();
      $count++;
    }
  }

    // Closing line
    $pdf->Cell(array_sum($w),0,'','T');
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
$pdf->AddPage("L","A5");


$Sql = "SELECT
        billwash.DocNo,
        DATE_FORMAT(billwash.DocDate,'%d-%m-%Y') AS DocDate,
        site.HptName,
        department.DepName,
        employee.FirstName,
        employee.LastName,
        billwash.Total,
        billwash.IsStatus,
        TIME(billwash.Modify_Date) AS xTime
        FROM
        billwash
        INNER JOIN billwash_detail ON billwash.DocNo = billwash_detail.DocNo
        INNER JOIN site ON billwash.HptCode = site.HptCode
        INNER JOIN department ON billwash.DepCode = department.DepCode
        INNER JOIN item ON billwash_detail.ItemCode = item.ItemCode
        INNER JOIN users ON billwash.Modify_Code = users.ID
        INNER JOIN employee ON users.ID = employee.EmpCode
        WHERE billwash.DocNo = '$DocNo'
        ";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result['HptName'];
  $DepName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FirstName'];
  $LastName = $Result['LastName'];
  $xTime = $Result['xTime'];
}

$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['site'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$HptName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['department'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DepName),0,0,'L');
$pdf->Ln(5);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docno'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$DocNo),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docdate'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DocDate),0,0,'L');
$pdf->Ln(5);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['user'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$FirstName." ".$LastName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['time'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$xTime),0,0,'L');
$pdf->SetMargins(15,0,0);
$pdf->Ln(12);
$query = "SELECT
          billwash_detail.ItemCode,
          item.ItemName,
          item_unit.UnitName,
					billwash_detail.Qty1,
					billwash_detail.Weight,
					billwash_detail.Total
          FROM billwash_detail
					INNER JOIN item_unit ON billwash_detail.UnitCode2 = item_unit.UnitCode
					INNER JOIN item ON item.ItemCode = billwash_detail.ItemCode
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          WHERE billwash_detail.DocNo = '$DocNo'
					GROUP BY item.ItemCode
          ORDER BY item.ItemName ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemCode,ItemName,Qty1,UnitName,Weight,Total";
// Table header
$header = array($array['no'][$language],$array['itemcode'][$language],$array['itemname'][$language],$array['amount'][$language],$array['unit'][$language],$array['weight'][$language],$array['price'][$language]);
// width of column table
$width = array(15,35,50,20,20,20,20);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
// Get $totalsum
$totalsum = 0;
if(is_array($result)){
  foreach($result as $result=>$inner_array){
    $totalsum += $inner_array['Total'];
  }
}
// Footer Table
$footer = array('','','','','','รวม',number_format($totalsum,2));
$pdf->SetFont('THSarabun','b',10);
for($i=0;$i<count($footer);$i++)
  $pdf->Cell($width[$i],7,iconv("UTF-8","TIS-620",$footer[$i]." "),1,0,'R');
$pdf->Ln(8);

$ddate = date('d_m_Y');
$pdf->Output('I','Report_Stock_'.$ddate.'.pdf');
?>
