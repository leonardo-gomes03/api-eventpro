<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// Conexão com o banco de dados (substitua pelas suas credenciais)
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "bdeventpro";

$servername = "mysql://root:NnAiBg69Q4lNQaywazG9@containers-us-west-111.railway.app:6371/railway";
$username = "root";
$password = "NnAiBg69Q4lNQaywazG9";
$dbname = "railway";
$port = "6371";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
