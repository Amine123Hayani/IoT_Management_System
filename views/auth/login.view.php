<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">

  <title>Login</title>
</head>

<body>
  <div class="container mt-10 mb-5">
    <div class="row">
      <div class="col-12 col-md-8 offset-0 offset-md-2">
        <div class="card shadow shadow-lg">
          <div class="card-body">
            <img src="/assets/images/logo.png" width="150" height="150" class="rounded-circle shadow shadow-lg logo" alt="Company Logo">
            <p class="display-4 m-0 text-center mt-5">
              IoT Webreathe
            </p>
            <hr>
            <?php if (isset($_SESSION["success"]) && strlen($_SESSION['success']) > 0) { ?>
              <div class="alert alert-success">
                <p class="m-0">
                  <i class="fa fa-caret-right mr-1"></i>
                  <?php echo $_SESSION['success']; ?>
                </p>
                <?php unset($_SESSION["success"]); ?>
              </div>
            <?php } ?>

            <div class="alert alert-danger <?php echo (isset($_SESSION['erreurs']) && count($_SESSION['erreurs']) > 0) ? '' : 'd-none'; ?>" id="erreursContainer">
              <?php if (isset($_SESSION["erreurs"]) && count($_SESSION['erreurs']) > 0) { ?>
                <?php foreach ($_SESSION["erreurs"] as $error) { ?>
                  <p class="m-0">
                    <i class="fa fa-caret-right mr-1"></i>
                    <?php echo $error; ?>
                  </p>
                <?php } ?>
                <?php unset($_SESSION["erreurs"]); ?>
              <?php } ?>
            </div>
            <form action="/auth/login.php" method="POST">
              <div class="form-group row text-center">
                <label for="utulisateur" class="col-form-label col-12">utulisateur</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="text" id="utulisateur" name="utulisateur" value="<?php echo isset($_SESSION['form']['utulisateur']) ? $_SESSION['form']['utulisateur'] : '' ?>" maxlength="125" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="password" class="col-form-label col-12">Password</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="password" id="password" maxlength="125" name="password" class="form-control">
                </div>
              </div>
              <div class="text-center">
                <button class="btn btn-success px-4" id="loginBtn" type="submit">
                  Login
                </button>
              </div>
            </form>
            <div class="text-center mt-2">
              <p class="text-center m-0">
                <span>Does not have an account?</span>
                <a href="/auth/register.php">Register</a>
              </p>
              <p class="text-center m-0">
                <span>Return</span>
                <a href="/">Home</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/js/popper.min.js"></script>
  <script src="/assets/js/bootstrap.min.js"></script>
  <script src="/assets/js/all.min.js"></script>
  <script src="/assets/js/app.js"></script>

  <script>
    $(document).ready(function() {
      var utulisateur = $("#utulisateur");
      var password = $("#password");
      var loginBtn = $("#loginBtn");

      loginBtn.click(function(e) {
        var erreurs = [];
        var erreursContainer = $("#erreursContainer");
        erreursContainer.html("");
        if (!erreursContainer.hasClass("d-none"))
          erreursContainer.addClass("d-none");

        var utulisateurValue = utulisateur.val().trim().length;
        var passwordValue = password.val().trim().length;

        if (utulisateurValue <= 0) {
          erreurs.push("<?php echo VALID_USERNAME_REQ; ?>");
        } else if (utulisateurValue < 6) {
          erreurs.push("<?php echo VALID_USERNAME_MIN; ?>");
        } else if (utulisateurValue > 125) {
          erreurs.push("<?php echo VALID_USERNAME_MAX; ?>");
        }

        if (passwordValue <= 0) {
          erreurs.push("<?php echo VALID_PASSWORD_REQ; ?>");
        } else if (passwordValue < 6) {
          erreurs.push("<?php echo VALID_PASSWORD_MIN; ?>");
        } else if (passwordValue > 125) {
          erreurs.push("<?php echo VALID_PASSWORD_MAX; ?>");
        }

        if (erreurs.length > 0) {
          e.preventDefault();

          var allErreursHTML = "";
          for (var i = 0; i < erreurs.length; i++) {
            allErreursHTML += generateErrorItem(erreurs[i]);
          }
          erreursContainer.html(allErreursHTML);
          erreursContainer.removeClass("d-none");
        }
      });
    });
  </script>

</body>

</html>