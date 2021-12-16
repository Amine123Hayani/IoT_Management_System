<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

if (!isset($_GET['op']) || !isset($_GET['d'])) {
  header("Location: /user/dispos.php");
  return;
}

$op = $_GET['op'];
$d = $_GET['d'];

$sql = "SELECT COUNT(*) AS K " .
  "FROM users, user_dispos, device_calibrations " .
  "WHERE users.id = user_dispos.user_id AND " .
  "device_calibrations.user_device_id = user_dispos.id AND " .
  "users.utulisateur=:u AND device_calibrations.id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":u" => $_SESSION['auth']['utulisateur'],
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$K = $record['K'];
if ($K <= 0) {
  header("Location: /user/dispos.php");
  return;
}

$sql = "SELECT user_device_id FROM device_calibrations WHERE id=:d;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':d' => $d,
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$deviceID = $record['user_device_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (strcmp($op, "update") != 0) {
    header("Location: /user/dispos.php");
    return;
  }

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
    $_SESSION['erreurs'] = $erreurs;
    header("Location: /user/calibration.php?d=$d&op=edit");
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
      $_SESSION['erreurs'] = $erreurs;
      header("Location: /user/calibration.php?d=$d&op=edit");
      return;
    } else {
      $sql = "UPDATE device_calibrations SET value=:v, operator_id=:o, message=:m " .
        "WHERE id=:d;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':m' => $message,
        ':v' => $reading,
        ':o' => $operator,
        ':d' => $d,
      ]);

      $_SESSION['success'] = CALIBRATION_UPDATE_SUCCESS;
      header("Location: /user/device.php?d=$deviceID&op=show");
      return;
    }
  }
}



if (strcmp($op, "delete") == 0) {
  $sql = "DELETE FROM device_calibrations WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_SUCCESS;
  header("Location: /user/device.php?d=$deviceID&op=show");
  return;
} else if (strcmp($op, "edit") == 0) {
  $sql = "SELECT * FROM device_calibrations WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $calibration = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM dispos, user_dispos WHERE " .
    "dispos.id = user_dispos.device_id AND user_dispos.id = :d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $deviceID,
  ]);
  $deviceInfo = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT * FROM operators;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':u' => $_SESSION['auth']['utulisateur'],
  ]);
  $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

  include_once("common.php");
  include_once("../views/user/edit-calibration.view.php");
}
