<link rel="icon" type="image/png" href="../img/pose_favicon.png">
<link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../bootstrap/css/tbody.css" rel="stylesheet">
<link href="../bootstrap/css/myinput.css" rel="stylesheet">
<link href="../template/css/sb-admin.css" rel="stylesheet">
<link href="../css/xfont.css" rel="stylesheet">
<link href="../assets-sign/css/jquery.signature.css" rel="stylesheet">
<link href="../css/menu_custom.css" rel="stylesheet">
<link href="../dist/css/sweetalert2.css" rel="stylesheet">
<link rel="stylesheet" href="../css/signature-pad2.css">
<link href="../css/responsive.css" rel="stylesheet">
<link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">

<style media="screen">


    .select3 {
      width: 255px !important;
    }

    .select2-container--default .select2-selection--single {
      height: 38px;
      border: 1px solid #aaaaaa85;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 5px;
    }

    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .btn{
      font-family: myFirstFont !important;
      font-size: 22px !important;
    }
    .modal-content1 {
      width: 72% !important;
      right: -15% !important;
      position: relative;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-direction: column;
      flex-direction: column;
      width: 100%;
      pointer-events: auto;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: .3rem;
      outline: 0;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    button,
    input[id^='qty'],
    input[id^='order'],
    input[id^='max'] {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      /* background: #4f88e3!important; */
      background-color: #1659a2;
    }

    .table th,
    .table td {
      border-top: none !important;
    }

    table tr th,
    table tr td {
      border-right: 0px solid #bbb;
      border-bottom: 0px solid #bbb;
      padding: 5px;
    }

    table tr th:first-child,
    table tr td:first-child {
      border-left: 0px solid #bbb;
    }

    table tr th {
      background: #eee;
      /* border-top: 0px solid #bbb; */
      text-align: left;
    }

    /* top-left border-radius */
    table tr:first-child th:first-child {
      border-top-left-radius: 15px;
    }

    table tr:first-child th:first-child {
      border-bottom-left-radius: 15px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 15px;
    }

    table tr:first-child th:last-child {
      border-bottom-right-radius: 15px;
    }

    /* bottom-left border-radius */
    table tr:last-child td:first-child {
      border-bottom-left-radius: 6px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }

    .btn_mhee {
      background-color: #e83530;
      color: white;
    }

    .btn_mheesave {
      background-color: #ee9726;
      color: white;
    }

    .btn_mheedel {
      background-color: #b12f31;
      color: white;
    }

    .btn_mheeIM {
      background-color: #3e3a8f;
      color: white;
    }

    .btn_mheedetail {
      background-color: #535d55;
      color: white;
    }

    .btn_mheereport {
      background-color: #d8d9db;
      color: white;
    }

    .btn_mheeCREATE {
      background-color: #1458a3;

      color: white;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    .mhee a {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .mhee button {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 23px;
      color: #2c3e50;
      display: block;
      background: none;
      box-shadow: none !important;

    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .sidenav {
      height: 100%;
      overflow-x: hidden;
      /* padding-top: 20px; */
      border-left: 2px solid #bdc3c7;
    }

    .sidenav a {
      padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .sidenav a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 44px;
    }

    .opacity {
      opacity: 0.5;
    }

    .only1:disabled,
    .form-control[readonly] {
      background-color: transparent !important;
      opacity: 1;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav a {
        font-size: 21px;

      }
    }

    .kbw-signature {
      width: 100%;
      height: 240px;
    }

    #ModalSign {
      top: 0% !important;
    }
  </style>