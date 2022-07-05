<?php
session_start();
require_once '../Config.php';
require_once '../App.php';
$App = new App();
if(isset($_SESSION['admin_id'])){
  header("Location: ./");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="./assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="./assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="./assets/images/favicon.ico" />
  <link rel="shortcut icon" href="./assets/css/custom.css" />
  <style>
    #login-msg {
      font-family: sans-serif;
      color: royalblue;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="../favicon.ico" id="logo-dashboard">
                <h4>ADMIN DASHBOARD - <?php echo strtoupper(PROJECT_NAME . " " . $App->app_version); ?></h4>
              </div>
              <h4 style="font-family:sans-serif">Đăng nhập để quản lý dịch vụ.</h4>
              <h6 class="font-weight-light" id="login-msg"></h6>
              <form class="pt-3" id="login-form" autocomplete="off">
                <div class="form-group">
                  <input type="username" class="form-control form-control-lg" name="username" placeholder="Username" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" autocomplete="off" required>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="btn-submit">GO GO !</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
                <!-- <div class="mb-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="mdi mdi-facebook me-2"></i>Connect using facebook </button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="register.html" class="text-primary">Create</a>
                  </div> -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="./assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="./assets/js/off-canvas.js"></script>
  <script src="./assets/js/hoverable-collapse.js"></script>
  <script src="./assets/js/misc.js"></script>
  <!-- endinject -->
</body>

</html>
<script>
  $(document).ready(function() {
    $("#btn-submit").click(function(e) {
      e.preventDefault();
      $.ajax({
        type: "GET",
        url: "../api?type=login",
        data: $("#login-form").serialize(),
        success: function(data) {
          console.log($("#login-form").serialize())
          var r = JSON.parse(data);
          console.log(r);
          if(r.type == "OK" && r.data != null){
            window.location.href = "./index.php";
          }
          else {
            $("#login-msg").text(r.message)
          }
        },
        error: function(data) {
          console.log("lỗi");
          console.log(data);
        }
      })
    })
  })
</script>