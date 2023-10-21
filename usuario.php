<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      // Consulta SQL para recuperar dados da tabela tbusuario e, se freelancerusuario for 1, fazer um JOIN com tbfreelancerservico
      $idusuario = isset($_GET['idusuario']);
      $username = isset($_GET['username']);

      if (strlen($idusuario) > 0) {
        $sql = "SELECT * from tbusuario where idusuario = '$_GET[idusuario]'";

        // Executa a consulta SQL
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = $result->fetch_assoc();

          // Retorna os resultados como JSON
          echo json_encode($response);
        } else {
          // Não foram encontrados registros correspondentes
          echo json_encode(array("message" => "Nenhum registro encontrado."));
        }
        return;
      }

      if (strlen($username) > 0) {
        $sql = "SELECT * from tbusuario where username = '$_GET[username]'";

        // Executa a consulta SQL
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = $result->fetch_assoc();

          // Retorna os resultados como JSON
          echo json_encode($response);
        } else {
          // Não foram encontrados registros correspondentes
          echo json_encode(array("message" => "Nenhum registro encontrado."));
        }
        return;
      }

      $sql = "SELECT * from tbusuario";

      // Executa a consulta SQL
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $response = array();

        while ($row = $result->fetch_assoc()) {
          // Para cada linha retornada, adicione-a à resposta
          $response[] = $row;
        }

        // Retorna os resultados como JSON
        echo json_encode($response);
      } else {
        // Não foram encontrados registros correspondentes
        echo json_encode(array("message" => "Nenhum registro encontrado."));
      }

    }
    break;
  case 'POST': {

      // Recebe os dados JSON da solicitação POST
      $data = json_decode(file_get_contents("php://input"));

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->nomeusuario) &&
        isset($data->datanascusuario) &&
        isset($data->username) &&
        isset($data->telefoneusuario) &&
        isset($data->generousuario) &&
        isset($data->emailusuario) &&
        isset($data->cpfusuario) &&
        isset($data->senhausuario) &&
        isset($data->freelancerusuario)

      ) {
        // Dados do usuário recebidos
        $nomeusuario = $data->nomeusuario;
        $username = $data->username;
        $datanascusuario = $data->datanascusuario;
        $telefoneusuario = $data->telefoneusuario;
        $generousuario = $data->generousuario;
        $emailusuario = $data->emailusuario;
        $cpfusuario = $data->cpfusuario;
        $senhausuario = $data->senhausuario;
        $freelancerusuario = $data->freelancerusuario;

        // Campos opcionais
        $experienciausuario = isset($data->experienciausuario) ? $data->experienciausuario : null;
        $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

        // Prepara e executa a consulta SQL para inserir o usuário
        $sql = "INSERT INTO tbusuario (nomeusuario, username, datanascusuario, telefoneusuario, generousuario, emailusuario, cpfusuario, senhausuario, statususuario, freelancerusuario, fotoperfilusuario, experienciausuario) 
            VALUES ('$nomeusuario', '$username', '$datanascusuario', '$telefoneusuario', '$generousuario', '$emailusuario', '$cpfusuario', '$senhausuario', '1', $freelancerusuario, '$fotoperfilusuario', '$experienciausuario');";

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
    }
    break;
  case 'PUT': {
      // Recebe os dados JSON da solicitação POST
      $data = json_decode(file_get_contents("php://input"));

      $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $pathSegments = explode('/', $_SERVER['REQUEST_URI_PATH']);
      $idusuario = $pathSegments[count($pathSegments) - 1];

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->nomeusuario) &&
        isset($data->username) &&
        isset($data->datanascusuario) &&
        isset($data->telefoneusuario) &&
        isset($data->generousuario) &&
        isset($data->emailusuario) &&
        isset($data->cpfusuario) &&
        isset($data->statususuario) &&
        isset($data->freelancerusuario)
      ) {
        // Dados do usuário recebidos
        $nomeusuario = $data->nomeusuario;
        $username = $data->username;
        $datanascusuario = $data->datanascusuario;
        $telefoneusuario = $data->telefoneusuario;
        $generousuario = $data->generousuario;
        $emailusuario = $data->emailusuario;
        $cpfusuario = $data->cpfusuario;
        $statususuario = $data->statususuario;
        $freelancerusuario = $data->freelancerusuario;

        // Campos opcionais
        $experienciausuario = isset($data->experienciausuario) ? $data->experienciausuario : null;
        $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

        // Prepara e executa a consulta SQL para atualizar o usuário
        $sql = "UPDATE tbusuario 
                    SET nomeusuario = '$nomeusuario', 
                        username = '$username', 
                        datanascusuario = '$datanascusuario', 
                        telefoneusuario = '$telefoneusuario', 
                        generousuario = '$generousuario', 
                        emailusuario = '$emailusuario', 
                        cpfusuario = '$cpfusuario',
                        freelancerusuario= '$freelancerusuario',
                        statususuario = '$statususuario',
                        fotoperfilusuario = '$fotoperfilusuario',
                        experienciausuario = '$experienciausuario'
                    WHERE idusuario=$idusuario;";

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
    }
    break;
  case 'DELETE': {

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
    }
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado"));
    break;
}



$conn->close();
?>