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
    isset($data->codcliente) &&
    isset($data->codtipo) &&
    isset($data->horainicioprojeto) &&
    isset($data->horafimprojeto) &&
    isset($data->dataprojeto) &&
    isset($data->cidadeprojeto) && 
    isset($data->codservico)
) {
    // Dados recebidos
    $codcliente = $data->codcliente;
    $codtipo = $data->codtipo;
    $horainicioprojeto = $data->horainicioprojeto;
    $horafimprojeto = $data->horafimprojeto;
    $dataprojeto = $data->dataprojeto;
    $cidadeprojeto = $data->cidadeprojeto;

    // Campos opcionais
    $descricaoprojeto = isset($data->descricaoprojeto) ? $data->descricaoprojeto : null;
    $qtdpessoas = isset($data->qtdpessoas) ? $data->qtdpessoas : null;

    // Prepara e executa a consulta SQL para inserir o projeto
    $sql = "INSERT INTO tbprojeto (codcliente, codtipo, horainicioprojeto, horafimprojeto, dataprojeto, descricaoprojeto, qtdpessoas, cidadeprojeto) 
    VALUES ($codcliente, $codtipo, '$horainicioprojeto', '$horafimprojeto', '$dataprojeto', '$descricaoprojeto', $qtdpessoas, '$cidadeprojeto')";

if ($conn->query($sql) === TRUE) {
    // Registro de freelancer inserido com sucesso

    // Obtenha o ID do freelancer inserido
    $idprojeto = $conn->insert_id;

    // Insira os serviços oferecidos pelo freelancer na tabela tbfreelancerservico
    if (isset($data->codservico) && is_array($data->codservico)) {
        foreach ($data->codservico as $codservico) {
            $sqlServico = "INSERT INTO tbprojetoservico (codprojeto, codservico) VALUES ('$idprojeto', '$codservico')";
            $conn->query($sqlServico);
        }
    }

    $response = array("message" => "Projeto cadastrado com sucesso.");
    echo json_encode($response);
} else {
    // Erro na inserção do freelancer
    $response = array("message" => "Erro ao cadastrar freelancer: " . $conn->error);
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