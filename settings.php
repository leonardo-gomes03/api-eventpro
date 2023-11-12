<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('Access-Control-Allow-Headers: token, Content-Type, Authorization');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header('Content-Type: text/plain');
  die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');



$auth_username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$auth_password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

// Autenticação segura
$expected_username = 'admin';
$expected_password = 'eventpro';

if (
  !hash_equals(base64_encode($expected_username), base64_encode($auth_username)) ||
  !hash_equals(base64_encode($expected_password), base64_encode($auth_password))
) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  echo json_encode(['message' => 'Não autorizado']);
  header('HTTP/1.0 401 Unauthorized');
  exit;
}

// Conexão com o banco de dados (substitua pelas suas credenciais)
$servername = "monorail.proxy.rlwy.net";
$username = "root";
$password = "d54ecf4B6-cFG55eEc-Fd1HcGfC5-E26";
$dbname = "bdeventpro";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname, 36279);

// Verifica a conexão
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}
?>