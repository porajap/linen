<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');

class PDF extends FPDF
{

  function Header()
  {
    $datetime = new DatetimeTH();
    $eDate = $_GET['eDate'];
    $eDate = explode("/",$eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d')." ".$datetime->getTHmonth(date('F'))." พ.ศ. ".$datetime->getTHyear(date('Y'));
    $edate = $eDate[0]." ".$datetime->getTHmonthFromnum($eDate[1])." พ.ศ. ".$datetime->getTHyear($eDate[2]);

    if($this->page==1){
      // Move to the right
      $this->SetFont('THSarabun','',12);
      $this->Cell(190,10,iconv("UTF-8","TIS-620","วันที่พิมพ์รายงาน ".$printdate),0,0,'R');
      $this->Ln(10);
      // Title
      $this->SetFont('THSarabun','b',20);
      $this->Cell(80);
      $this->Cell(30,10,iconv("UTF-8","TIS-620","รายงานตรวจสอบการแก้ไขเอกสารPayout"),0,0,'C');
      // Line break
      $this->Ln(5);
    }else{
      $this->Ln(15);
    }
  }

  // function HeaderReport($printdate,$edate)
  // {
  //     // Move to the right
  //     $this->SetFont('THSarabun','',12);
  //     $this->Cell(190,10,iconv("UTF-8","TIS-620","วันที่พิมพ์รายงาน ".$printdate),0,0,'R');
  //     $this->Ln(10);
  //     // Title
  //     $this->SetFont('THSarabun','b',21);
  //     $this->Cell(80);
  //     $this->Cell(30,10,iconv("UTF-8","TIS-620","สรุปรายการนึ่งประจำวันที่ ".$edate),0,0,'C');
  //     // Line break
  //     $this->Ln(10);
  // }

  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun','i',13);
    // Page number
    $this->Cell(0,10,iconv("UTF-8","TIS-620",'หน้า ').$this->PageNo().'/{nb}',0,0,'R');
  }

  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    // $pdf->SetFont('THSarabun','b',14);
    // for($i=0;$i<count($header);$i++)
    //     $this->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
    // $this->Ln();

    // set Data Details
    $pdf->SetFont('THSarabun','',14);
    $count = 1;
    if(is_array($data)){
      foreach($data as $data=>$inner_array){
        $this->Cell($w[0],6,iconv("UTF-8","TIS-620",$count),1,0,'C');
        $this->Cell($w[1],6,iconv("UTF-8","TIS-620","  ".$inner_array[$field[1]]),1,0,'L');
        $this->Cell($w[2],6,iconv("UTF-8","TIS-620",$inner_array[$field[2]]),1,0,'C');
        $this->Cell($w[3],6,iconv("UTF-8","TIS-620",$inner_array[$field[3]]),1,0,'C');
        $this->Cell($w[4],6,iconv("UTF-8","TIS-620",$inner_array[$field[4]]),1,0,'C');
        $this->Cell($w[5],6,iconv("UTF-8","TIS-620",$inner_array[$field[5]]),1,0,'C');
        $this->Cell($w[6],6,iconv("UTF-8","TIS-620",$inner_array[$field[6]]),1,0,'C');
        $this->Cell($w[7],6,iconv("UTF-8","TIS-620",$inner_array[$field[7]]),1,0,'C');
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

$eDate = $_GET['eDate'];
$eDate = explode("/",$eDate);
$eDate = $eDate[2]."-".$eDate[1]."-".$eDate[0];

// Using Coding
$pdf->AddPage();
$pdf->Ln();
$pdf->SetFont('THSarabun','b',14);
$pdf->Cell(10);
$pdf->Cell(10,6,iconv("UTF-8","TIS-620","วันที่ค้นหา : ".$_GET['eDate']),0,0,'C');
$pdf->Ln();
$pdf->SetFont('THSarabun','b',14);
$pdf->SetMargins(15,0);
$pdf->Ln();

$Sql = "SELECT
department.DepName,
log_payout.UserCode
FROM
department
INNER JOIN payout ON department.ID = payout.DeptID
INNER JOIN log_payout ON payout.UserCode = log_payout.UserCode";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DepName = $Result['DepName'];

$pdf->SetFont('THSarabun','b',16);
$pdf->Cell(80);
$pdf->Cell(30,10,iconv("UTF-8","TIS-620","แผนก ".$DepName),0,0,'C');
$pdf->Ln(15);

$header = array('ลำดับ','แผนก','เลขเอกสาร','วันที่แก้ไขเอกสาร','ชื่อ','ชื่ออุปกรณ์','จำนวนเดิม','จำนวนที่แก้ไข');
$w = array(10,25,30,30,20,25,20,20);
$pdf->SetFont('THSarabun','b',14);
for($i=0;$i<8;$i++)
$pdf->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
$pdf->Ln();

$sum = 0;

$query = "SELECT
department.DepName,
lpo.DocNo,
lpo.CreateDateTime,
employee.FirstName,
item.itemname,
IFNULL((
  SELECT
  SUM(sendsteriledetail.Qty) AS Qty
  FROM sendsterile
  INNER JOIN sendsteriledetail ON sendsterile.DocNo = sendsteriledetail.SendSterileDocNo
  INNER JOIN itemstock ON sendsteriledetail.ItemStockID = itemstock.RowID
  WHERE sendsteriledetail.SendSterileDocNo = po.RefDocNo
  AND itemstock.ItemCode = item.itemcode
  -- GROUP BY itemstock.ItemCode
),'เพิ่ม') AS SSQty,
(
  SELECT log_payout.Qty
  FROM log_payout
  WHERE log_payout.DocNo = lpo.DocNo
  ORDER BY log_payout.ID DESC
  LIMIT 1
) AS Qty
FROM
log_payout AS lpo
INNER JOIN itemstock ON lpo.ItemStockID = itemstock.RowID
INNER JOIN item ON itemstock.ItemCode = item.itemcode
INNER JOIN users ON lpo.UserCode = users.ID
INNER JOIN employee ON users.EmpCode = employee.EmpCode
INNER JOIN department ON employee.DepID = department.ID
INNER JOIN payout AS po ON lpo.DocNo = po.DocNo
WHERE DATE(lpo.CreateDateTime) = '$eDate'
GROUP BY lpo.DocNo
ORDER BY department.DepName ASC,lpo.DocNo ASC
";
// var_dump($query); die;
// Number of column
$numfield = 8;
// Field data (Must match with Query)
$field = "countn,DepName,DocNo,CreateDateTime,FirstName,itemname,SSQty,Qty";
// Table header
$header = array('ลำดับ','แผนก','เลขเอกสาร','วันที่แก้ไขเอกสาร','ชื่อ','ชื่ออุปกรณ์','SSQty','Qty');
// width of column table
$width = array(10,25,30,30,20,25,20,20);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',12);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
}
// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I','Report_Revise_'.$ddate.'.pdf');
mysqli_close($conn);

?>
