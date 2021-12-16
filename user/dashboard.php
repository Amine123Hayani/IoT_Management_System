<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

$disposSQL = "SELECT COUNT(*) AS K " .
  "FROM user_dispos, users, dispos " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "users.utulisateur=:u;";
$stmt = $pdo->prepare($disposSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$disposCount = $record["K"];

$alarmsSQL = "SELECT COUNT(*) AS K " .
  "FROM user_dispos, users, dispos, device_alarms, device_readings, device_calibrations " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "user_dispos.id = device_readings.user_device_id AND " .
  "device_readings.id = device_alarms.device_reading_id AND " .
  "device_calibrations.id = device_alarms.device_calibration_id AND " .
  "users.utulisateur=:u;";
$stmt = $pdo->prepare($alarmsSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$alarmsCount = $record["K"];

$rulesSQL = "SELECT COUNT(*) AS K " .
  "FROM user_dispos, users, dispos, device_rules " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "user_dispos.id = device_rules.sensor_id AND " .
  "users.utulisateur=:u;";
$stmt = $pdo->prepare($rulesSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
$rulesCount = $record["K"];

include_once("common.php");
include_once('../views/user/dashboard.view.php');
