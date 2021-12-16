<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur'])) {
  header("Location: /user/dashboard.php");
  return;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $erreurs = [];

  if (isset($_POST["utulisateur"])) {
    $utulisateur = $_POST["utulisateur"];
    if (strlen($utulisateur) <= 0) {
      array_push($erreurs, VALID_USERNAME_REQ);
    } else if (strlen($utulisateur) > 125) {
      array_push($erreurs, VALID_USERNAME_MAX);
    } else if (strlen($utulisateur) < 6) {
      array_push($erreurs, VALID_USERNAME_MIN);
    }
  } else {
    array_push($erreurs, VALID_USERNAME_REQ);
  }

  if (isset($_POST["password"])) {
    $password = $_POST["password"];
    if (strlen($password) <= 0) {
      array_push($erreurs, VALID_PASSWORD_REQ);
    } else if (strlen($password) > 125) {
      array_push($erreurs, VALID_PASSWORD_MAX);
    } else if (strlen($password) < 6) {
      array_push($erreurs, VALID_PASSWORD_MIN);
    } else {
      $passwordNoErreurs = true;
    }
  } else {
    array_push($erreurs, VALID_PASSWORD_REQ);
  }

  if (count($erreurs)) {
    $_SESSION["form"] = [
      "utulisateur" => $utulisateur,
    ];
    $_SESSION["erreurs"] = $erreurs;
    header("Location: /auth/login.php");
    return;
  } else {

    $sql = "SELECT * FROM users WHERE utulisateur=:u;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":u" => $utulisateur,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$record) {
      array_push($erreurs, WRONG_USERNAME);
      $_SESSION["erreurs"] = $erreurs;
      header("Location: /auth/login.php");
      return;
    } else {
      if (password_verify($password, $record["password"])) {
        $sql = "SELECT * FROM avatars WHERE user_id=:uid ORDER BY id DESC;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ":uid" => $record["id"],
        ]);
        $avatar = $stmt->fetch(PDO::FETCH_ASSOC);

        $token = md5(time());
        $_SESSION["auth"] = [
          "token" => $token,
          "utulisateur" => $record["utulisateur"],
          "avatar" => $avatar["name"],
          "fullName" => $record["first_name"] . " " . $record["last_name"],
        ];

        $sql = "INSERT INTO user_tokens(user_id, token) VALUES(:uid, :t);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ":uid" => $record["id"],
          ":t" => $token,
        ]);

        unset($_SESSION["form"]);
        $_SESSION["success"] = LOGIN_SUCCESS;
        header("Location: /user/dashboard.php");
        return;
      } else {
        array_push($erreurs, WRONG_AUTH);
        $_SESSION["erreurs"] = $erreurs;
        header("Location: /auth/login.php");
        return;
      }
    }
  }
}

include_once('../views/auth/login.view.php');
unset($_SESSION["form"]);
