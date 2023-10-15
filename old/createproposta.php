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
    isset($data->codprojeto) &&
    isset($data->codfreelancer) &&
    isset($data->statusproposta) &&
    isset($data->codservico) &&
    isset($data->valorproposta)
) {
    // Dados recebidos
    $codprojeto = $data->codprojeto;
    $codfreelancer = $data->codfreelancer;
    $codservico= $data->codservico;
    $statusproposta = $data->statusproposta;
    $valorproposta= $data->valorproposta;
    
    // Verifica se outros campos opcionais estão definidos
    $descricaoproposta = isset($data->descricaoproposta) ? $data->descricaoproposta : null;
    $notaavaliacao = isset($data->notaavaliacao) ? $data->notaavaliacao : null;
    $comentarioavaliacao = isset($data->comentarioavaliacao) ? $data->comentarioavaliacao : null;
    $fotoavaliacao = isset($data->fotoavaliacao) ? $data->fotoavaliacao : null;

    // Prepara e executa a consulta SQL para inserir a proposta
    $sql = "INSERT INTO tbproposta (codprojeto, codfreelancer, codservico, statusproposta, descricaoproposta, notaavaliacao, comentarioavaliacao, valorproposta, fotoavaliacao) 
    VALUES ('$codprojeto', '$codfreelancer', '$codservico', '$statusproposta', '$descricaoproposta', '$notaavaliacao', '$comentarioavaliacao', '$valorproposta', '$fotoavaliacao')";

    if ($conn->query($sql) === TRUE) {
        // Registro inserido com sucesso
        $response = array("message" => "Proposta cadastrada com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na inserção
        $response = array("message" => "Erro ao cadastrar proposta: " . $conn->error);
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
