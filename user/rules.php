<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

$rulesSQL = "SELECT dispos.code AS code, user_dispos.id AS id, " .
  "device_rules.actuator_id AS actID, operators.name AS operator, " .
  "(SELECT code FROM dispos, user_dispos WHERE dispos.id = user_dispos.device_id AND user_dispos.id=actID) AS actCode, " .
  "device_rules.value AS value, device_rules.state AS state, " .
  "device_rules.created_at AS created_at, device_rules.id AS ruleID " .
  "FROM user_dispos, users, dispos, device_rules, operators " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "operators.id = device_rules.operator_id AND " .
  "user_dispos.id = device_rules.sensor_id AND " .
  "users.utulisateur=:u;";
$stmt = $pdo->prepare($rulesSQL);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$rulesTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
include_once("common.php");
include_once("../views/user/rules.view.php");
