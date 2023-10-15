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

// Recebe os dados JSON da solicitação PUT
$data = json_decode(file_get_contents("php://input"));

// Verifica se os dados estão presentes e são válidos
if (
    isset($data->idprojeto) &&
    isset($data->codcliente) &&
    isset($data->codtipo) &&
    isset($data->horainicioprojeto) &&
    isset($data->horafimprojeto) &&
    isset($data->dataprojeto) &&
    isset($data->descricaoprojeto) &&
    isset($data->qtdpessoas) &&
    isset($data->cidadeprojeto) &&
    isset($data->codservico)
) {

    // Inicia uma transação
    $conn->begin_transaction();

    try {

        // Dados recebidos
        $idprojeto = $data->idprojeto;
        $codcliente = $data->codcliente;
        $codtipo = $data->codtipo;
        $horainicioprojeto = $data->horainicioprojeto;
        $horafimprojeto = $data->horafimprojeto;
        $dataprojeto = $data->dataprojeto;
        $descricaoprojeto = $data->descricaoprojeto;
        $qtdpessoas = $data->qtdpessoas;
        $cidadeprojeto = $data->cidadeprojeto;

        // Prepara e executa a consulta SQL para atualizar o projeto
        $sql = "UPDATE tbprojeto SET codcliente = '$codcliente', codtipo = '$codtipo', horainicioprojeto = '$horainicioprojeto', horafimprojeto = '$horafimprojeto', dataprojeto = '$dataprojeto', descricaoprojeto = '$descricaoprojeto', qtdpessoas = '$qtdpessoas', cidadeprojeto = '$cidadeprojeto' WHERE idprojeto = $idprojeto";

        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Erro ao atualizar projeto: " . $conn->error);
        }

        // Limpa a tabela tbfreelancerservico para este freelancer
        $sqlDeleteServicos = "DELETE FROM tbprojetoservico WHERE codprojeto = $idprojeto";

        if ($conn->query($sqlDeleteServicos) !== TRUE) {
            throw new Exception("Erro ao excluir serviços antigos: " . $conn->error);
        }

        // Insira os serviços oferecidos pelo freelancer na tabela tbfreelancerservico
        foreach ($data->codservico as $codservico) {
            $sqlServico = "INSERT INTO tbprojetoservico (codprojeto, codservico) VALUES ($idprojeto, $codservico)";

            if ($conn->query($sqlServico) !== TRUE) {
                throw new Exception("Erro ao inserir serviços: " . $conn->error);
            }
        }

        // Confirma a transação
        $conn->commit();

        $response = array("message" => "Projeto atualizado com sucesso.");
        echo json_encode($response);
    } catch (Exception $e) {
        // Em caso de erro, faz rollback na transação
        $conn->rollback();

        $response = array("message" => $e->getMessage());
        echo json_encode($response);
    }
} else {
    // Dados ausentes ou inválidos
    $response = array("message" => "Dados inválidos ou ausentes.");
    echo json_encode($response);
}

// Fecha a conexão com o banco de dados
$conn->close();
