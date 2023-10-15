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

// Verifica se os dados estão presentes e são válidos
if (
    isset($data->idproposta) &&
    isset($data->codprojeto) &&
    isset($data->codservico) &&
    isset($data->codfreelancer) &&
    isset($data->valorproposta) &&
    isset($data->statusproposta) 
) {
    // Dados do usuário recebidos
    $idproposta = $data->idproposta;
    $codprojeto = $data->codprojeto;
    $codservico = $data->codservico;
    $codfreelancer = $data->codfreelancer;
    $valorproposta = $data->valorproposta;
    $statusproposta = $data->statusproposta;


    // Campos opcionais
    $comentarioavaliacao = isset($data->comentarioavaliacao) ? $data->comentarioavaliacao : null;
    $notaavaliacao = isset($data->notaavaliacao) ? $data->notaavaliacao : null;
    $fotoavaliacao = isset($data->fotoavaliacao) ? $data->fotoavaliacao : null;
    $descricaoproposta = isset($data->descricaoproposta) ? $data->descricaoproposta : null;

    // Prepara e executa a consulta SQL para atualizar o usuário
    $sql = "UPDATE tbproposta 
            SET codprojeto = '$codprojeto', 
                codfreelancer = '$codfreelancer', 
                codservico = '$codservico', 
                statusproposta = '$statusproposta', 
                valorproposta = '$valorproposta', 
                descricaoproposta = '$descricaoproposta', 
                notaavaliacao = '$notaavaliacao', 
                comentarioavaliacao = '$comentarioavaliacao', 
                fotoavaliacao = '$fotoavaliacao' 
            WHERE idproposta = $idproposta";

    if ($conn->query($sql) === TRUE) {
        // Registro de usuário atualizado com sucesso

        $response = array("message" => "Proposta atualizado com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na atualização do usuário
        $response = array("message" => "Erro ao atualizar proposta: " . $conn->error);
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
