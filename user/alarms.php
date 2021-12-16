<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

$alarmsSQL = "SELECT device_readings.reading AS reading, device_calibrations.message AS message,  " .
  "device_alarms.created_at AS created_at, user_dispos.id AS id " .
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
$alarmsTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once("common.php");
include_once("../views/user/alarms.view.php");
