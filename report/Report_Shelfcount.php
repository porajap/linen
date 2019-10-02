<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/", $eDate);
$eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];

$dept = $_GET['dept'];
$DocNo = $_GET['DocNo'];
$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $datetime = new DatetimeTH();
    $language = $_GET['lang'];
    $datetime = new DatetimeTH();
    $eDate = $_GET['eDate'];
    $eDate = explode("/", $eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 12);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", $array['printdate'][$language] . ' ' . $printdate), 0, 0, 'R');
      $this->Ln(10);
      // Title
      $this->SetFont('THSarabun', 'b', 21);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r4'][$language]), 0, 0, 'C');
      // Line break
      $this->Ln(10);
    } else {
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $datetime = new DatetimeTH();
    $language = $_GET['lang'];
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 13);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", $array['page'][$language]) . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $issue = $inner_array[$field[2]] - $inner_array[$field[3]];
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", "  " . $inner_array[$field[2]]), 1, 0, 'L');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]] . " "), 1, 0, 'R');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $issue . " "), 1, 0, 'R');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $issue . " "), 1, 0, 'R');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[6]] . " "), 1, 0, 'R');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[7]] . " "), 1, 0, 'R');
        $this->Ln();
        $count++;
      }
    }


    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
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
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT   site.$HptName,
        department.DepName,
        shelfcount.DocNo,
        shelfcount.CycleTime,
        DATE_FORMAT(shelfcount.DocDate,'%d-%m-%Y')AS DocDate,
        shelfcount.Total,
        users.FName,
        TIME(shelfcount.Modify_Date) AS xTime,
        shelfcount.IsStatus
        FROM shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        INNER JOIN users ON shelfcount.Modify_Code = users.ID
        WHERE shelfcount.DocNo = '$DocNo'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $CycleTime = $Result['CycleTime'];
}

$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $HptName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['department'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", ": " . $DepName), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $DocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", ": " . $DocDate), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $FirstName . " " . $LastName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", ": " . $xTime), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['Cycle'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $CycleTime), 0, 0, 'L');
$pdf->Ln(15);
$query = "SELECT
item.ItemName,
item.weight,
IFNULL(shelfcount_detail.ParQty,0) AS ParQty,
IFNULL(shelfcount_detail.CcQty,0) AS CcQty,
IFNULL(shelfcount_detail.TotalQty,0) AS TotalQty,
IFNULL(shelfcount_detail.Over,0) AS OverPar,
IFNULL(shelfcount_detail.Short,0) AS Short,
IFNULL(item.Weight,0) AS Weight
FROM
shelfcount
INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
INNER JOIN department ON shelfcount.DepCode = department.DepCode
WHERE shelfcount.DocNo = '$DocNo'
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = ",ItemName,ParQty,CcQty,,,Short,OverPar";
// Table header
$header = array($array['no'][$language], $array['itemname'][$language], $array['parqty'][$language], $array['shelfcount1'][$language], $array['max'][$language], $array['issue'][$language], $array['short'][$language], $array['over'][$language]);
// width of column table
$width = array(10, 40, 140 / 6, 140 / 6, 140 / 6, 140 / 6, 140 / 6, 140 / 6);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// // Get $totalsum
// $totalsum = 0;
// if(is_array($result)){
//   foreach($result as $result=>$inner_array){
//     $totalsum += $inner_array['Qty'];
//   }
// }
// // Footer Table
// $footer = array('','','รวม',$totalsum,'');
// $pdf->SetFont('THSarabun','b',14);
// for($i=0;$i<count($footer);$i++)
//   $pdf->Cell($width[$i],7,iconv("UTF-8","TIS-620",$footer[$i]),1,0,'C');
// $pdf->Ln(8);


// $pdf->SetFont('THSarabun','b',10);
// $pdf->setFillColor(230,230,230);
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'พร7A'),'LTR',1,'L');
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'A TEC Trimavie 13-09'),'LR',1,'L');
// $pdf->Cell(25,5,iconv("UTF-8","TIS-620",'เตรียม: ศุภานัน '),'L',0,'L');
// $pdf->Cell(25,5,iconv("UTF-8","TIS-620",' ตรวจ: เล็กข้างกาด'),'R',1,'R');
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'ห่อ : รจนา  ฆ่าเชื้อ : รจนา'),'LR',1,'L');
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'เครื่อง :SA & FH /รอบ: 1 No.001659'),'LR',1,'L');
// $pdf->SetFont('THSarabun','b',12);
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'EXP : 11/02/63'),'LR',1,'R');
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'ผลิต 16/08/62'),'LR',1,'R');
// $pdf->Cell(50,5,iconv("UTF-8","TIS-620",),'LBR',1,'L');

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Stock_' . $ddate . '.pdf');
