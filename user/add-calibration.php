<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}


if (!isset($_GET['d'])) {
  header("Location: /user/dashboard.php");
  return;
}

$d = $_GET['d'];

$sql = "SELECT COUNT(*) AS K " .
  "FROM users, user_dispos " .
  "WHERE users.id = user_dispos.user_id AND " .
  "users.utulisateur=:u AND user_dispos.id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION['auth']['utulisateur'],
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$K = $record['K'];
if ($K <= 0) {
  header("Location: /user/dashboard.php");
  return;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $erreurs = [];
  $reading = null;
  $message = null;
  $operator = null;


  if (!isset($_POST['reading'])) {
    array_push($erreurs, VALID_READING_REQ);
  } else {
    $reading = $_POST['reading'];
    if (strlen($reading) > 5) {
      array_push($erreurs, VALID_READING_MAX);
    } else if (!is_numeric($reading)) {
      array_push($erreurs, VALID_READING_NUM);
    }
  }

  if (!isset($_POST['message'])) {
    array_push($erreurs, VALID_MESSAGE_REQ);
  } else {
    $message = $_POST['message'];
    if (strlen($message) > 125) {
      array_push($erreurs, VALID_MESSAGE_MAX);
    }
  }

  if (!isset($_POST['operator'])) {
    array_push($erreurs, VALID_OPERATOR_REQ);
  } else {
    $operator = $_POST['operator'];
  }

  if (count($erreurs) > 0) {
    $_SESSION['form'] = [
      'operator' => $operator,
      'message' => $message,
      'reading' => $reading,
    ];
    $_SESSION['erreurs'] = $erreurs;
    header("Location: /user/add-calibration.php?d=$d");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K FROM operators WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':id' => $operator,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfOperators = $record["K"];
    if ($noOfOperators <= 0) {
      array_push($erreurs, VALID_OPERATOR_REQ);
    }

    if (count($erreurs) > 0) {
      $_SESSION['form'] = [
        'operator' => $operator,
        'message' => $message,
        'reading' => $reading,
      ];
      $_SESSION['erreurs'] = $erreurs;
      header("Location: /user/add-calibration.php?d=$d");
      return;
    } else {
      $sql = "INSERT INTO device_calibrations (user_device_id, value, operator_id, message) VALUES " .
        "(:d, :v, :o, :m);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':m' => $message,
        ':v' => $reading,
        ':o' => $operator,
        ':d' => $d,
      ]);

      unset($_SESSION["form"]);
      $_SESSION['success'] = CALIBRATION_SUCCESS;
      header("Location: /user/device.php?d=$d&op=show");
      return;
    }
  }
}

$sql = "SELECT * FROM dispos, user_dispos WHERE " .
  "dispos.id = user_dispos.device_id AND user_dispos.id = :d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':d' => $d,
]);
$deviceInfo = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM operators;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':u' => $_SESSION['auth']['utulisateur'],
]);
$operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once("common.php");
include_once("../views/user/add-calibration.view.php");
unset($_SESSION["form"]);
