<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?php echo $pagetitle; ?></title>

  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Calendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/js/config.js"></script>
  <link rel="stylesheet" href="../assets/css/toast.css">

  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- DataTables -->
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

  <!-- Bootstrap -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->

  <!-- QuillJS -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-beta.0/dist/quill.snow.css" rel="stylesheet" /> -->

  <!-- TinyMCE -->
  <script type="text/javascript" src="https://cdn.tiny.cloud/1/v2ooa4tj54wbeh5fgh0x5j1j62dcp4y8ouf2p895dms4e0z6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <!-- GetOTP -->
  <script src="https://otp.dev/js/getotp.min.js"></script>
  
</head>

<style>
  .notification-circle {
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 1px solid lightgrey;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    position: relative;
  }

  .notification-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background-color: red;
    color: white;
    font-size: 10px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    text-align: center;
    line-height: 16px;
  }
</style>