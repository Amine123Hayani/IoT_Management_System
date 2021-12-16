<?php
if (strcmp(
  realpath(__FILE__),
  realpath($_SERVER["SCRIPT_FILENAME"])
) == 0) {
  header("Location: /");
  return;
}


define("DB_NAME", "iot_webreathe");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_HOST", "localhost");
define("DB_PORT", "3306");

//;simicolonee
$pdo = new PDO("mysql:host=" . DB_HOST . ";port="
  . DB_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
