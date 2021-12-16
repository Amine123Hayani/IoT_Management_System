<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $erreurs = [];

  if (isset($_POST["deviceCard"])) {
    $deviceCard = $_POST["deviceCard"];
    if (strlen($deviceCard) <= 0) {
      array_push($erreurs, VALID_DEVICECARD_REQ);
    } else if (strlen($deviceCard) > 15) {
      array_push($erreurs, VALID_DEVICECARD_MAX);
    } else if (strlen($deviceCard) < 8) {
      array_push($erreurs, VALID_DEVICECARD_MIN);
    }
  } else {
    array_push($erreurs, VALID_DEVICECARD_REQ);
  }

  if (count($erreurs)) {
    $_SESSION["form"] = [
      "deviceCard" => $deviceCard,
    ];
    $_SESSION["erreurs"] = $erreurs;
    header("Location: /user/add-device.php");
    return;
  } else {
    $sql = "SELECT COUNT(*) AS K FROM dispos WHERE code=:c;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':c' => $deviceCard,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfDispos = $record["K"];
    if ($noOfDispos <= 0) {
      array_push($erreurs, VALID_DEVICECARD_FOUND);
    }

    if (count($erreurs) > 0) {
      $_SESSION["form"] = [
        "deviceCard" => $deviceCard,
      ];
      $_SESSION["erreurs"] = $erreurs;
      header("Location: /user/add-device.php");
      return;
    }

    $sql = "SELECT COUNT(*) AS K FROM user_dispos WHERE " .
      "device_id=(SELECT id FROM dispos WHERE code=:c);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':c' => $deviceCard,
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $noOfUsedDispos = $record["K"];
    if ($noOfUsedDispos > 0) {
      array_push($erreurs, VALID_DEVICECARD_USED);
    }

    if (count($erreurs) > 0) {
      $_SESSION["form"] = [
        "deviceCard" => $deviceCard,
      ];
      $_SESSION["erreurs"] = $erreurs;
      header("Location: /user/add-device.php");
      return;
    }

    $sql = "INSERT INTO user_dispos (user_id, device_id) VALUES " .
      "((SELECT id FROM users WHERE utulisateur=:u), (SELECT id FROM dispos WHERE code=:c));";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':u' => $_SESSION['auth']['utulisateur'],
      ':c' => $deviceCard,
    ]);

    unset($_SESSION["form"]);
    $_SESSION['success'] = DEVICE_ADDED;
    if (isset($_POST["btnChoice"]) && $_POST["btnChoice"] == 1) {
      $sql = "SELECT id FROM user_dispos WHERE " .
        "device_id=(SELECT id FROM dispos WHERE code=:c);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':c' => $deviceCard,
      ]);
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $d = $record["id"];
      header("Location: /user/device.php?d=$d&op=show");
    } else {
      header("Location: /user/dispos.php");
    }
    return;
  }
}

include_once("common.php");
include_once("../views/user/add-device.view.php");
unset($_SESSION["form"]);
