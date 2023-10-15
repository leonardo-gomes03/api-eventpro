<?php
// Conexão com o banco de dados (substitua pelas suas credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdeventpro";

// ID da proposta que você deseja excluir (pode ser passado via GET ou POST)
if (isset($_GET['idproposta'])) {
    $idproposta = $_GET['idproposta'];

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Prepara e executa a consulta SQL para excluir o registro com base no ID
    $sql = "DELETE FROM tbproposta WHERE idproposta = $idproposta";

    if ($conn->query($sql) === TRUE) {
        // Registro excluído com sucesso
        $response = array("message" => "Proposta excluída com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na exclusão
        $response = array("message" => "Erro ao excluir proposta: " . $conn->error);
        echo json_encode($response);
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    // ID inválido ou ausente
    $response = array("message" => "ID de proposta inválido ou ausente.");
    echo json_encode($response);
}
?>
