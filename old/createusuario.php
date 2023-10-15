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
    $nomeusuario = $data->nomeusuario;
    $datanascusuario = $data->datanascusuario;
    $telefoneusuario = $data->telefoneusuario;
    $generousuario = $data->generousuario;
    $emailusuario = $data->emailusuario;
    $cpfusuario = $data->cpfusuario;
    $senhausuario = $data->senhausuario;
    $statususuario = $data->statususuario;
    $freelancerusuario = $data->freelancerusuario;

    // Campos opcionais
    $experienciausuario = isset($data->experienciausuario) ? $data->experienciausuario : null;
    $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

    // Prepara e executa a consulta SQL para inserir o usuário
    $sql = "INSERT INTO tbusuario (nomeusuario, datanascusuario, telefoneusuario, generousuario, experienciausuario, emailusuario, cpfusuario, senhausuario, statususuario, freelancerusuario, fotoperfilusuario) 
    VALUES ('$nomeusuario', '$datanascusuario', '$telefoneusuario', '$generousuario', '$experienciausuario', '$emailusuario', '$cpfusuario', '$senhausuario', '$statususuario', $freelancerusuario, '$fotoperfilusuario')";

    if ($conn->query($sql) === TRUE) {
        // Registro de usuário inserido com sucesso

        // Verifica se é um freelancer (freelancerusuario igual a 1)
        if ($freelancerusuario == 1) {
            // Obtém o ID do usuário inserido
            $idusuario = $conn->insert_id;

            if (isset($data->codservico) && is_array($data->codservico)) {
                foreach ($data->codservico as $codservico) {
                    $sqlServico = "INSERT INTO tbfreelancerservico (codfreelancer, codservico) VALUES ('$idusuario', '$codservico')";
                    $conn->query($sqlServico);
                }
            }
        }

        $response = array("message" => "Usuário cadastrado com sucesso.");
        echo json_encode($response);
    } else {
        // Erro na inserção do usuário
        $response = array("message" => "Erro ao cadastrar usuário: " . $conn->error);
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
