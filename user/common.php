<?php
if (strcmp(
  realpath(__FILE__),
  realpath($_SERVER["SCRIPT_FILENAME"])
) == 0) {
  header("Location: /");
  return;
}

if (!(isset($_SESSION['auth']) && isset($_SESSION['auth']['utulisateur']))) {
  header("Location: /auth/login.php");
  return;
}

$sql = "SELECT dispos.name AS name, user_dispos.id AS id " .
  "FROM user_dispos, users, dispos " .
  "WHERE user_dispos.user_id = users.id AND " .
  "user_dispos.device_id = dispos.id AND " .
  "users.utulisateur=:u ORDER BY user_dispos.created_at DESC LIMIT 0, 5;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':u' => $_SESSION["auth"]["utulisateur"],
]);
$dispos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$_SESSION["dispos"] = $dispos;


$sql = "SELECT info_value FROM website_information WHERE info_key='logo';";
$stmt = $pdo->query($sql);
$logo = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION["logo"] = $logo['info_value'];
