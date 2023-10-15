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

// Verifica se foi passado um ID válido para exclusão
if (isset($_GET['idprojeto'])) {
    $idprojeto = $_GET['idprojeto'];

    // Primeiro, exclua os registros relacionados na tabela tbprojetoservico
    $sqlExcluirServicos = "DELETE FROM tbprojetoservico WHERE codprojeto = $idprojeto";

    if ($conn->query($sqlExcluirServicos) === TRUE) {
        // Registros relacionados excluídos com sucesso, agora exclua o projeto principal
        $sqlExcluirProjeto = "DELETE FROM tbprojeto WHERE idprojeto = $idprojeto";

        if ($conn->query($sqlExcluirProjeto) === TRUE) {
            // Projeto excluído com sucesso
            $response = array("message" => "Projeto e registros relacionados excluídos com sucesso.");
            echo json_encode($response);
        } else {
            // Erro na exclusão do projeto principal
            $response = array("message" => "Erro ao excluir projeto: " . $conn->error);
            echo json_encode($response);
        }
    } else {
        // Erro na exclusão dos registros relacionados
        $response = array("message" => "Erro ao excluir registros relacionados: " . $conn->error);
        echo json_encode($response);
    }
} else {
    // ID inválido ou ausente
    $response = array("message" => "ID inválido ou ausente.");
    echo json_encode($response);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
