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

if (strcmp($op, "delete-all") == 0) {
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
    header("Location: /user/dispos.php");
    return;
  }

  $sql = "DELETE FROM device_readings WHERE user_device_id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_ALL_SUCCESS;
  header("Location: /user/device.php?d=$d&op=show");
  return;
} else if (strcmp($op, "delete") == 0) {
  $sql = "SELECT COUNT(*) AS K " .
    "FROM users, user_dispos, device_readings " .
    "WHERE users.id = user_dispos.user_id AND " .
    "device_readings.user_device_id = user_dispos.id AND " .
    "users.utulisateur=:u AND device_readings.id=:d;";
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

  $sql = "SELECT user_device_id FROM device_readings WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $deviceID = $record['user_device_id'];

  $sql = "DELETE FROM device_readings WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);

  $_SESSION['success'] = DELETE_SUCCESS;
  header("Location: /user/device.php?d=$deviceID&op=show");
  return;
}
