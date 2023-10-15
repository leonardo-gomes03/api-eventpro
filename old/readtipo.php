<?php
// Conexão com o banco de dados (substitua pelas suas credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdeventpro";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT * from tbtipo";

// Executa a consulta SQL
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $response = array();

    while ($row = $result->fetch_assoc()) {
        // Para cada linha retornada, adicione-a à resposta
        $response[] = $row;
    }

    // Retorna os resultados como JSON
    echo json_encode($response);
} else {
    // Não foram encontrados registros correspondentes
    echo json_encode(array("message" => "Nenhum registro encontrado."));
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
