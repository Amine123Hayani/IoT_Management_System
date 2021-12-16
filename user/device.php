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

if (strcmp($op, "show") == 0) {

  $sql = "SELECT * " .
    "FROM users, user_dispos, dispos " .
    "WHERE users.id = user_dispos.user_id AND " .
    "dispos.id = user_dispos.device_id AND " .
    "users.utulisateur=:u AND user_dispos.id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":u" => $_SESSION['auth']['utulisateur'],
    ':d' => $d,
  ]);
  $userDevice = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = "SELECT *, device_readings.id AS did FROM device_readings, user_dispos " .
    "WHERE device_readings.user_device_id=user_dispos.id AND " .
    "user_dispos.id=:d ORDER BY device_readings.created_at DESC LIMIT 0, 10;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  $readingsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $alarmsSQL = "SELECT device_readings.reading AS reading, device_calibrations.message AS message,  " .
    "device_alarms.created_at AS created_at, user_dispos.id AS id, device_alarms.id AS aid " .
    "FROM user_dispos, users, dispos, device_alarms, device_readings, device_calibrations " .
    "WHERE user_dispos.user_id = users.id AND " .
    "user_dispos.device_id = dispos.id AND " .
    "user_dispos.id = device_readings.user_device_id AND " .
    "device_readings.id = device_alarms.device_reading_id AND " .
    "device_calibrations.id = device_alarms.device_calibration_id AND " .
    "users.utulisateur=:u AND user_dispos.id=:d ORDER BY device_alarms.created_at DESC LIMIT 0, 10;";
  $stmt = $pdo->prepare($alarmsSQL);
  $stmt->execute([
    ':u' => $_SESSION["auth"]["utulisateur"],
    ':d' => $d,
  ]);
  $alarmsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $calibrationsSQL = "SELECT device_calibrations.id AS cid, " .
    "device_calibrations.value AS value, device_calibrations.message AS message, " .
    "device_calibrations.created_at AS created_at, operators.name AS name " .
    "FROM users, user_dispos, device_calibrations, operators " .
    "WHERE user_dispos.id = device_calibrations.user_device_id AND " .
    "users.id = user_dispos.user_id AND users.utulisateur=:u AND " .
    "device_calibrations.operator_id = operators.id AND user_dispos.id=:d";
  $stmt = $pdo->prepare($calibrationsSQL);
  $stmt->execute([
    ':u' => $_SESSION["auth"]["utulisateur"],
    ':d' => $d,
  ]);

  $calibrationsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

  include_once("common.php");
  include_once("../views/user/device.view.php");
} else if (strcmp($op, "delete") == 0) {
  $sql = "DELETE FROM user_dispos WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  header("Location: /user/dispos.php");
  return;
} else if (strcmp($op, "toggle") == 0) {
  $sql = "UPDATE user_dispos SET is_on=not is_on WHERE id=:d;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':d' => $d,
  ]);
  header("Location: /user/device.php?d=$d&op=show");
  return;
}
