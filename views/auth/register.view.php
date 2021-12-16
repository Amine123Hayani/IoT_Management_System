<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">

  <title>Register</title>
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
            <form action="/auth/register.php" method="POST">
              <div class="form-group row text-center">
                <label for="firstName" class="col-form-label col-12">First Name</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="text" id="firstName" value="<?php echo isset($_SESSION['form']['firstName']) ? $_SESSION['form']['firstName'] : '' ?>" name="firstName" maxlength="100" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="lastName" class="col-form-label col-12">Last Name</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="text" id="lastName" value="<?php echo isset($_SESSION['form']['lastName']) ? $_SESSION['form']['lastName'] : '' ?>" name="lastName" maxlength="100" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="utulisateur" class="col-form-label col-12">utulisateur</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="text" id="utulisateur" value="<?php echo isset($_SESSION['form']['utulisateur']) ? $_SESSION['form']['utulisateur'] : '' ?>" name="utulisateur" maxlength="125" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="email" class="col-form-label col-12">Email</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="email" id="email" value="<?php echo isset($_SESSION['form']['email']) ? $_SESSION['form']['email'] : '' ?>" name="email" maxlength="125" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="password" class="col-form-label col-12">Password</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="password" id="password" name="password" maxlength="125" class="form-control">
                </div>
              </div>
              <div class="form-group row text-center">
                <label for="retypePassword" class="col-form-label col-12">Retype Password</label>
                <div class="text-center col-12 col-md-8 offset-0 offset-md-2">
                  <input type="password" maxlength="125" id="retypePassword" name="retypePassword" class="form-control">
                </div>
              </div>
              <div class="text-center">
                <button class="btn btn-success px-4" id="registerBtn" type="submit">
                  Register
                </button>
              </div>
            </form>
            <div class="text-center mt-2">
              <p class="text-center m-0">
                <span>Having an account?</span>
                <a href="/auth/login.php">Login</a>
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
      var firstName = $("#firstName");
      var lastName = $("#lastName");
      var email = $("#email");
      var utulisateur = $("#tulisateur");
      var password = $("#password");
      var retypePassword = $("#retypePassword");
      var registerBtn = $("#registerBtn");

      registerBtn.click(function(e) {
        var erreurs = [];
        var erreursContainer = $("#erreursContainer");
        erreursContainer.html("");
        if (!erreursContainer.hasClass("d-none"))
          erreursContainer.addClass("d-none");

        var firstNameValue = firstName.val().trim().length;
        var lastNameValue = lastName.val().trim().length;
        var emailValue = email.val().trim().length;
        var utulisateurValue = utulisateur.val().trim().length;
        var passwordValue = password.val().trim().length;
        var retypePasswordValue = retypePassword.val().trim().length;
        var noPasswordErreurs = false;
        var noRePasswordErreurs = false;

        if (firstNameValue <= 0) {
          erreurs.push("<?php echo VALID_FIRSTNAME_REQ; ?>");
        } else if (utulisateurValue > 100) {
          erreurs.push("<?php echo VALID_FIRSTNAME_MAX; ?>");
        }

        if (lastNameValue > 100) {
          erreurs.push("<?php echo VALID_LASTNAME_MAX; ?>");
        }

        if (utulisateurValue <= 0) {
          erreurs.push("<?php echo VALID_USERNAME_REQ; ?>");
        } else if (utulisateurValue < 6) {
          erreurs.push("<?php echo VALID_USERNAME_MIN; ?>");
        } else if (utulisateurValue > 125) {
          erreurs.push("<?php echo VALID_USERNAME_MAX; ?>");
        }

        if (emailValue <= 0) {
          erreurs.push("<?php echo VALID_EMAIL_REQ; ?>");
        } else if (emailValue < 5) {
          erreurs.push("<?php echo VALID_EMAIL_MIN; ?>");
        } else if (emailValue > 125) {
          erreurs.push("<?php echo VALID_EMAIL_MAX; ?>");
        }

        if (passwordValue <= 0) {
          erreurs.push("<?php echo VALID_PASSWORD_REQ; ?>");
        } else if (passwordValue < 6) {
          erreurs.push("<?php echo VALID_PASSWORD_MIN; ?>");
        } else if (passwordValue > 125) {
          erreurs.push("<?php echo VALID_PASSWORD_MAX; ?>");
        } else {
          noPasswordErreurs = true;
        }

        if (retypePasswordValue <= 0) {
          erreurs.push("<?php echo VALID_REPASSWORD_REQ; ?>");
        } else if (retypePasswordValue < 6) {
          erreurs.push("<?php echo VALID_REPASSWORD_MIN; ?>");
        } else if (retypePasswordValue > 125) {
          erreurs.push("<?php echo VALID_REPASSWORD_MAX; ?>");
        } else {
          noRePasswordErreurs = true;
        }

        if (noPasswordErreurs && noRePasswordErreurs) {
          if (passwordValue != retypePasswordValue) {
            erreurs.push("<?php echo VALID_PASSWORD_MATCH; ?>");
          }
        }

        if (erreurs.length > 0) {
          e.preventDefault();

          var allErreursHTML = "";
          for (var i = 0; i < erreurs.length; i++) {
            allErreursHTML += generateErrorItem(erreurs[i]);
          }
          ErreursContainer.html(allErreursHTML);
          ErreursContainer.removeClass("d-none");
        }
      });
    });
  </script>

</body>

</html>