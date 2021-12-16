<?php
include_once("../helpers/dictionary.php");
include_once("../helpers/conn.php");
session_start();

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

$sql = "SELECT dispos.name AS name, dispos.description AS description, " .
  "dispos.type AS type, dispos.code AS code, user_dispos.id AS id, user_dispos.is_on AS is_on " .
  "FROM user_dispos, users, dispos " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "users.utulisateur=:u ORDER BY user_dispos.created_at DESC LIMIT 0, 5;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$disposTable = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once("common.php");
include_once("../views/user/dispos.view.php");
