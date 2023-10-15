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

// Recebe os dados JSON da solicitação POST
$data = json_decode(file_get_contents("php://input"));

// Verifica se o ID do usuário está presente
if (isset($data->idusuario)) {
    $idusuario = $data->idusuario;

    // Prepara e executa a consulta SQL para atualizar o campo statususuario para 0
    $sql = "UPDATE tbusuario SET statususuario = 0 WHERE idusuario = $idusuario";

    if ($conn->query($sql) === TRUE) {
        // Registro de usuário atualizado com sucesso
        $response = array("message" => "Status de usuário atualizado para 0 com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na atualização
        $response = array("message" => "Erro ao atualizar o status de usuário: " . $conn->error);
        echo json_encode($response);
    }
} else {
    // Dados ausentes ou inválidos
    $response = array("message" => "Dados inválidos ou ausentes.");
    echo json_encode($response);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
