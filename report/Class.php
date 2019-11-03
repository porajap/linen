<?php
header('Content-Type: text/html; charset=utf-8');

Class Font
{
  public function __construct($pdf)
  {
    $pdf->AddFont('THSarabun','','THSarabun.php');//ธรรมดา
    $pdf->AddFont('THSarabun','b','THSarabun_Bold.php');//หนา
    $pdf->AddFont('THSarabun','i','THSarabun_Italic.php');//อียง
    $pdf->AddFont('THSarabun','bi','THSarabun_Bold_Italic.php');//หนาเอียง
    $pdf->AliasNbPages();
  }
}

Class Data {
  public function getdata($conn,$query,$numfield,$field) {
        try {
            $field = explode(",",$field);
            $meQuery = mysqli_query($conn,$query);
            $j = 0;
            while ($Result = mysqli_fetch_assoc($meQuery)) {
              for ($i=0; $i < count($field) ; $i++) {
                if($field[$i]=="no."){
                  $Data[$j][$field[$i]] = $j+1;
                }else{
                  $Data[$j][$field[$i]] = $Result[$field[$i]];
                }
              }
              $j++;
            }
        } catch (PDOException $e) {
            echo "Exeption: " .$e->getMessage();
            $result = false;
        }
        $query = null;
        $db = null;
        return $Data;
    }
}

class DatetimeTH
{
  public function getNumber($day)
  {
    $TH = '';
    switch ($day) {
      case '1':
        $TH = '01';
        break;
      case '2':
        $TH = '02';
        break;
      case '3':
        $TH = '03';
        break;
      case '4':
        $TH = '04';
        break;
      case '5':
        $TH = '05';
        break;
      case '6':
        $TH = '06';
        break;
      case '7':
        $TH = '07';
        break;
      case '8':
        $TH = '08';
        break;
      case '9':
        $TH = '09';
        break;
      default:
        $TH = $day;
    }

    return $TH;
  }
  public function getmonthFromnum($month)
  {
    $TH = '';
    switch ($month) {
      case '1':
        $TH = 'January';
        break;
      case '2':
        $TH = 'February';
        break;
      case '3':
        $TH = 'March';
        break;
      case '4':
        $TH = 'April';
        break;
      case '5':
        $TH = 'May';
        break;
      case '6':
        $TH = 'June';
        break;
      case '7':
        $TH = 'July';
        break;
      case '8':
        $TH = 'August';
        break;
      case '9':
        $TH = 'September';
        break;
      case '10':
        $TH = 'October';
        break;
      case '11':
        $TH = 'November';
        break;
      case '12':
        $TH = 'December';
        break;
    }
    return $TH ;
  }
  public function getTHday($day)
  {
    $TH = '';
    switch ($day) {
      case 'Monday':
        $TH = 'จันทร์';
        break;
      case 'Tuesday':
        $TH = 'อังคาร';
        break;
      case 'Wednesday':
        $TH = 'พุธ';
        break;
      case 'Thursday':
        $TH = 'พฤหัสบดี';
        break;
      case 'Friday':
        $TH = 'ศุกร์';
        break;
      case 'Saturday':
        $TH = 'เสาร์';
        break;
      case 'Sunday':
        $TH = 'อาทิตย์';
        break;
    }

    return $TH;
  }

  public function getTHmonth($month)
  {
    $TH = '';
    switch ($month) {
      case 'January':
        $TH = 'มกราคม';
        break;
      case 'February':
        $TH = 'กุมภาพันธ์';
        break;
      case 'March':
        $TH = 'มีนาคม';
        break;
      case 'April':
        $TH = 'เมษายน';
        break;
      case 'May':
        $TH = 'พฤษภาคม';
        break;
      case 'June':
        $TH = 'มิถุนายน';
        break;
      case 'July':
        $TH = 'กรกฎาคม';
        break;
      case 'August':
        $TH = 'สิงหาคม';
        break;
      case 'September':
        $TH = 'กันยายน';
        break;
      case 'October':
        $TH = 'ตุลาคม';
        break;
      case 'November':
        $TH = 'พฤศจิกายน';
        break;
      case 'December':
        $TH = 'ธันวาคม';
        break;
    }
    return $TH ;
  }

  public function getTHmonthFromnum($month)
  {
    $TH = '';
    switch ($month) {
      case '1':
        $TH = 'มกราคม';
        break;
      case '2':
        $TH = 'กุมภาพันธ์';
        break;
      case '3':
        $TH = 'มีนาคม';
        break;
      case '4':
        $TH = 'เมษายน';
        break;
      case '5':
        $TH = 'พฤษภาคม';
        break;
      case '6':
        $TH = 'มิถุนายน';
        break;
      case '7':
        $TH = 'กรกฎาคม';
        break;
      case '8':
        $TH = 'สิงหาคม';
        break;
      case '9':
        $TH = 'กันยายน';
        break;
      case '10':
        $TH = 'ตุลาคม';
        break;
      case '11':
        $TH = 'พฤศจิกายน';
        break;
      case '12':
        $TH = 'ธันวาคม';
        break;
    }
    return $TH ;
  }

  public function getNumberMonth($month)
  {
    $TH = '';
    switch ($month) {
      case 'มกราคม':
        $TH = '1';
        break;
      case 'กุมภาพันธ์':
        $TH = '2';
        break;
      case 'มีนาคม':
        $TH = '3';
        break;
      case 'เมษายน':
        $TH = '4';
        break;
      case 'พฤษภาคม':
        $TH = '5';
        break;
      case 'มิถุนายน':
        $TH = '6';
        break;
      case 'กรกฎาคม':
        $TH = '7';
        break;
      case 'สิงหาคม':
        $TH = '8';
        break;
      case 'กันยายน':
        $TH = '9';
        break;
      case 'ตุลาคม':
        $TH = '10';
        break;
      case 'พฤศจิกายน':
        $TH = '11';
        break;
      case 'ธันวาคม':
        $TH = '12';
        break;
    }
    return $TH ;
  }

  public function getNUMmonth($month)
  {
    $TH = '';
    switch ($month) {
      case 'January':
        $TH = '1';
        break;
      case 'February':
        $TH = '2';
        break;
      case 'March':
        $TH = '3';
        break;
      case 'April':
        $TH = '4';
        break;
      case 'May':
        $TH = '5';
        break;
      case 'June':
        $TH = '6';
        break;
      case 'July':
        $TH = '7';
        break;
      case 'August':
        $TH = '8';
        break;
      case 'September':
        $TH = '9';
        break;
      case 'October':
        $TH = '10';
        break;
      case 'November':
        $TH = '11';
        break;
      case 'December':
        $TH = '12';
        break;
    }
    return $TH ;
  }

  public function getTHyear($year)
  {
    return($year+543);
  }

  public function getShortlyTHmonth($month)
  {
    $TH = '';
    switch ($month) {
      case '1':
        $TH = 'ม.ค.';
        break;
      case '2':
        $TH = 'ก.พ.';
        break;
      case '3':
        $TH = 'มี.ค.';
        break;
      case '4':
        $TH = 'เม.ย.';
        break;
      case '5':
        $TH = 'พ.ค.';
        break;
      case '6':
        $TH = 'มิ.ย.';
        break;
      case '7':
        $TH = 'ก.ค.';
        break;
      case '8':
        $TH = 'ส.ค.';
        break;
      case '9':
        $TH = 'ก.ย.';
        break;
      case '10':
        $TH = 'ต.ค.';
        break;
      case '11':
        $TH = 'พ.ย.';
        break;
      case '12':
        $TH = 'ธ.ค.';
        break;
    }
    return $TH ;
  }
}

?>
