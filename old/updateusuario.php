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
    isset($data->idusuario) &&
    isset($data->nomeusuario) &&
    isset($data->datanascusuario) &&
    isset($data->telefoneusuario) &&
    isset($data->generousuario) &&
    isset($data->emailusuario) &&
    isset($data->cpfusuario) &&
    isset($data->senhausuario) &&
    isset($data->statususuario) &&
    isset($data->freelancerusuario)
) {
    // Dados do usuário recebidos
    $idusuario = $data->idusuario;
    $nomeusuario = $data->nomeusuario;
    $datanascusuario = $data->datanascusuario;
    $telefoneusuario = $data->telefoneusuario;
    $generousuario = $data->generousuario;
    $emailusuario = $data->emailusuario;
    $cpfusuario = $data->cpfusuario;
    $senhausuario = $data->senhausuario;
    $statususuario= $data->statususuario;
    $freelancerusuario = $data->freelancerusuario;

    // Campos opcionais
    $experienciausuario = isset($data->experienciausuario) ? $data->experienciausuario : null;
    $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

    // Prepara e executa a consulta SQL para atualizar o usuário
    $sql = "UPDATE tbusuario 
            SET nomeusuario = '$nomeusuario', 
                datanascusuario = '$datanascusuario', 
                telefoneusuario = '$telefoneusuario', 
                generousuario = '$generousuario', 
                experienciausuario = '$experienciausuario', 
                emailusuario = '$emailusuario', 
                cpfusuario = '$cpfusuario', 
                senhausuario = '$senhausuario', 
                freelancerusuario= '$freelancerusuario',
                statususuario = '$statususuario',
                fotoperfilusuario = '$fotoperfilusuario' 
            WHERE idusuario = $idusuario";

    if ($conn->query($sql) === TRUE) {
        // Registro de usuário atualizado com sucesso

        // Verifica se é um freelancer (freelancerusuario igual a 1)
        if ($freelancerusuario == 1) {
            // Atualiza os serviços oferecidos pelo freelancer na tabela tbfreelancerservico
            if (isset($data->codservico) && is_array($data->codservico)) {
                // Primeiro, exclua todos os registros existentes para este usuário
                $sqlDeleteServicos = "DELETE FROM tbfreelancerservico WHERE codfreelancer = $idusuario";
                $conn->query($sqlDeleteServicos);

                // Em seguida, insira os novos registros
                foreach ($data->codservico as $codservico) {
                    $sqlServico = "INSERT INTO tbfreelancerservico (codfreelancer, codservico) VALUES ($idusuario, $codservico)";
                    $conn->query($sqlServico);
                }
            }
        }

        $response = array("message" => "Usuário atualizado com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na atualização do usuário
        $response = array("message" => "Erro ao atualizar usuário: " . $conn->error);
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
