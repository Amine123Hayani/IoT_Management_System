<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <title>Settings</title>
</head>

<body>

  <?php include_once("common/header.view.php"); ?>

  <div class="container my-5">
    <div class="row">
      <div class="col-12">
        <div class="text-center">
          <img src="/uploads/avatars/<?php echo $_SESSION['auth']['avatar'] ? $_SESSION['auth']['avatar'] : 'default.png'; ?>" width="200" height="200" class="rounded-circle shadow shadow-lg border border-dark" alt="">
          <h1 class="display-4 text-center">
            <?php echo $_SESSION['auth']['fullName']; ?>
          </h1>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="text-center card-title">
              My Settings
            </h3>
            <hr width="75%">
            <div class="row">
              <div class="col-12 col-md-8 offset-0 offset-md-2">
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
                <form action="/user/settings.php" method="POST">
                  <div class="form-group row">
                    <label for="" class="col-form-label col-12 col-md-3">
                      utulisateur
                    </label>
                    <div class="col-12 col-md-9">
                      <span id="utulisateurSpan">
                        <?php echo $userInfo['utulisateur']; ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-form-label col-12 col-md-3">
                      Email
                    </label>
                    <div class="col-12 col-md-9">
                      <span id="emailSpan">
                        <?php echo $userInfo['email']; ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="password" class="col-form-label col-12 col-md-3">
                      Password
                    </label>
                    <div class="col-12 col-md-9">
                      <span id="passwordSpan" class="toggle-span">[HIDDEN]</span>
                      <input type="password" name="oldPassword" placeholder="Enter your old password" id="oldPassword" class="form-control d-none toggle-input">
                      <br>
                      <input type="password" name="password" placeholder="Enter your new password" id="password" class="form-control d-none toggle-input">
                      <br>
                      <input type="password" name="retypePassword" placeholder="Retype your new password" id="retypePassword" class="form-control d-none toggle-input">
                    </div>
                  </div>

                  <div class="text-right">
                    <button class="btn btn-primary toggle-span" id="editBtn">
                      <i class="fa fa-edit"></i>
                      Edit
                    </button>
                    <button class="btn btn-danger d-none toggle-input" id="cancelBtn">
                      <i class="fa fa-trash"></i>
                      Cancel
                    </button>
                    <button class="btn btn-success d-none toggle-input" type="submit" id="updateBtn">
                      <i class="fa fa-check"></i>
                      Update
                    </button>
                  </div>
                </form>
              </div>
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
      var editBtn = $("#editBtn");
      var updateBtn = $("#updateBtn");
      var cancelBtn = $("#cancelBtn");
      var password = $("#password");
      var retypePassword = $("#retypePassword");
      var oldPassword = $("#oldPassword");

      updateBtn.click(function(e) {
        var noPasswordErreurs = false;
        var noRePasswordErreurs = false;

        var erreurs = [];
        var erreursContainer = $("#erreursContainer");
        erreursContainer.html("");
        if (!erreursContainer.hasClass("d-none"))
          erreursContainer.addClass("d-none");

        var passwordValue = password.val().trim();
        var retypePasswordValue = retypePassword.val().trim();
        var oldPasswordValue = oldPassword.val().trim().length;

        if (oldPasswordValue <= 0) {
          Erreurs.push("<?php echo VALID_OLDPASSWORD_REQ; ?>");
        } else if (oldPasswordValue < 6) {
          Erreurs.push("<?php echo VALID_OLDPASSWORD_MIN; ?>");
        } else if (oldPasswordValue > 125) {
          Erreurs.push("<?php echo VALID_OLDPASSWORD_MAX; ?>");
        }

        if (passwordValue.length <= 0) {
          Erreurs.push("<?php echo VALID_PASSWORD_REQ; ?>");
        } else if (passwordValue.length < 6) {
          Erreurs.push("<?php echo VALID_PASSWORD_MIN; ?>");
        } else if (passwordValue.length > 125) {
          Erreurs.push("<?php echo VALID_PASSWORD_MAX; ?>");
        } else {
          noPasswordErreurs = true;
        }

        if (retypePasswordValue.length <= 0) {
          Erreurs.push("<?php echo VALID_REPASSWORD_REQ; ?>");
        } else if (retypePasswordValue.length < 6) {
          Erreurs.push("<?php echo VALID_REPASSWORD_MIN; ?>");
        } else if (retypePasswordValue.length > 125) {
          Erreurs.push("<?php echo VALID_REPASSWORD_MAX; ?>");
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
          erreursContainer.removeClass("d-none");
        }
      });

      editBtn.click(function(e) {
        e.preventDefault();
        $(".toggle-input").removeClass("d-none");
        $(".toggle-span").addClass("d-none");
      });

      cancelBtn.click(function(e) {
        e.preventDefault();
        $(".toggle-input").addClass("d-none");
        $(".toggle-span").removeClass("d-none");
      });
    });
  </script>
</body>

</html>