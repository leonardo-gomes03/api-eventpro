<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {

  case 'POST': {

      // Recebe os dados JSON da solicitação POST
      $data = json_decode(file_get_contents("php://input"));

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->emailusuario) &&
        isset($data->senhausuario)

      ) {
        // Dados do usuário recebidos
        $emailusuario = $data->emailusuario;
        $senhausuario = $data->senhausuario;

        // Prepara e executa a consulta SQL para inserir o usuário
        $sql = "SELECT idusuario,nomeusuario, username, datanascusuario, telefoneusuario, generousuario, biousuario, emailusuario, cpfusuario, statususuario, freelancerusuario, fotoperfilusuario
                FROM tbusuario WHERE emailusuario = '$emailusuario' AND senhausuario = '$senhausuario'";

        // Executa a consulta SQL
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = $result->fetch_assoc();

          // Retorna os resultados como JSON
          echo json_encode($response);
        } else {
          // Não foram encontrados registros correspondentes
          echo json_encode(array("message" => "Email ou senha incorretos"));
        }
      } else {
        // Dados ausentes ou inválidos
        $response = array("message" => "Dados inválidos ou ausentes.");
        echo json_encode($response);
      }
    }
    break;

  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>