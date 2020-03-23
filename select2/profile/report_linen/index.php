<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Report Linen</title>
  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/background.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Merriweather|Muli&display=swap" rel="stylesheet">
  <style>
    div.relative {
      position: relative;
      left: 30px;
      border: 3px solid rgb(253, 253, 253);
      background-color: aliceblue;
      width: 100%;
      height: 100%;
    }
    *{
      font-family: 'Muli', sans-serif;
    }

  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-bottom">
    <div class="container">

    </div>
  </nav>

  <!-- Page Content -->
  <section>
    <div class="container my-5 ">
      <div class="row">
ิ
        <div class="col-lg-12">
          <h1 class="text-center"><img src="images/logo.png"  width="355 px"></h1>
          <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt voluptates rerum eveniet sapiente repellat esse, doloremque quod recusandae deleniti nostrum assumenda vel beatae sed aut modi nesciunt porro quisquam voluptatem.</p> -->
        </div>
      </div>
    </div>
  </section>

  <div class="container mb-5">
    <?php $name = array("Record_dirty_linen_weight","Report_Claim","Report_Cleaned_Linen_Weight","Report_Daily_Issue_Request","Report_Operations_Time_Spend","Report_Rewash","Report_Shot_and_Over_item","Report_Soiled_Clean_Ratio",
    "Report_stock_count","Report_Summary_billing","Report_Summary_cleaned_linen_weight",
    "Report_Summary_Dirty","Report_Summary_Soiled_Clean_Ratio","Report_Summary","Report_Tracking_status_for_laundry_plant","Report_Tracking_status_for_linen_operation");
          $url =
            array("Report_Dirty_Linen_Weight.php","Report_Claim.php","Report_Cleaned_Linen_Weight.php","Report_Daily_Issue_Request.php",
            "Report_Operations_Time_Spend.php","Report_Rewash.php","Report_Shot_and_Over_item.php","Report_Soiled_Clean_Ratio.php",
            "Report_stock_count.php","Report_Summary_billing.php","Report_Summary_cleaned_linen_weight.php","Report_Summary_Dirty.php",
            "Report_Summary_Soiled_Clean_Ratio.php","Report_Summary.php","Report_Tracking_status_for_laundry_plant.php","Report_Tracking_status_for_linen_operation.php"
    );
    $img = array
    ("icon1.png", "icon11.png", "icon10.png", "icon2.png", "icon9.png", "icon4.png", "icon5.png", "icon6.png", "icon7.png", "icon8.png","12.png","13.png","14.png","15.png","16.png","17.png");
    ?>
              <div class="row d-flex justify-content-center">
                <?php for ($i = 0; $i < 16; $i++) { ?>
                  <div class="col-lg-3 col-md-4 col-sm-6 mb-5 ">
                      <button onclick="window.open('report/<?php echo $url[$i]; ?>', '_blank');" type="button" class="btn btn-light btn-block">
                      <img src="images/<?php echo $img[$i] ?>" width="180px">
                        <div class="text-truncate text-uppercase pt-2  "><h5><small><?php echo $name[$i]; ?></small></h5></div>
                      </button>
                  </div>
                        <?php  }?>
              </div>
  </div>ฺ
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
