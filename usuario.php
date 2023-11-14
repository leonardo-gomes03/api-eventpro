<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': { {
        // Consulta SQL para recuperar dados da tabela tbusuario e, se freelancerusuario for 1, fazer um JOIN com tbfreelancerservico
        $idusuario = isset($_GET['idusuario']);
        $username = isset($_GET['username']);

        if (strlen($idusuario) > 0) {
          $sql = "SELECT u.*, AVG(notaavaliacao) as media 
                  from tbusuario u 
                  LEFT JOIN tbavaliacao av ON av.codavaliado = u.idusuario
                  where idusuario = '$_GET[idusuario]'";

          // Executa a consulta SQL
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();

            if ($response["freelancerusuario"] == "1") {

              $sqlServicos = "SELECT s.* FROM tbfreelancerservico fs
                        INNER JOIN tbservico s on s.idservico = fs.codservico
                        WHERE codfreelancer = {$response['idusuario']}";

              $servicosResult = $conn->query($sqlServicos);

              $servicosArray = array();

              // Adiciona cada serviço ao array
              while ($row = $servicosResult->fetch_assoc()) {
                $servicosArray[] = $row;
              }

              // Adiciona o array de serviços à resposta
              $response['servicos'] = $servicosArray;
            }

            // Retorna os resultados como JSON
            echo json_encode($response);
          } else {
            // Não foram encontrados registros correspondentes
            echo json_encode(array("message" => "Nenhum registro encontrado."));
          }
          return;
        }

        if (strlen($username) > 0) {
          $sql = "SELECT u.*, AVG(notaavaliacao) as media 
                  from tbusuario u 
                  LEFT JOIN tbavaliacao av ON av.codavaliado = u.idusuario
                  where username = '$_GET[username]'";

          // Executa a consulta SQL
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();

            $sqlServicos = "SELECT s.* FROM tbfreelancerservico fs
                        INNER JOIN tbservico s on s.idservico = fs.codservico
                        WHERE codfreelancer = {$response['idusuario']}";

            $servicosResult = $conn->query($sqlServicos);

            $servicosArray = array();

            // Adiciona cada serviço ao array
            while ($row = $servicosResult->fetch_assoc()) {
              $servicosArray[] = $row;
            }

            // Adiciona o array de serviços à resposta
            $response['servicos'] = $servicosArray;

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
        $biousuario = isset($data->biousuario) ? $data->biousuario : null;
        $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

        //Valida Email
        $sql = "SELECT * FROM tbusuario u 
                WHERE u.emailusuario = '$emailusuario'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse email já está em uso!");
          echo json_encode($response);
          return;
        }

        //Valida Username
        $sql = "SELECT * FROM tbusuario u 
                WHERE u.username = '$username'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse nome de usuario já está em uso!");
          echo json_encode($response);
          return;
        }

        //Valida CPF
        $sql = "SELECT * FROM tbusuario u 
        WHERE u.cpfusuario = '$cpfusuario'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse CPF já está em uso!");
          echo json_encode($response);
          return;
        }

        // Prepara e executa a consulta SQL para inserir o usuário
        $sql = "INSERT INTO tbusuario (nomeusuario, username, datanascusuario, telefoneusuario, generousuario, emailusuario, cpfusuario, senhausuario, statususuario, freelancerusuario, fotoperfilusuario, biousuario) 
            VALUES ('$nomeusuario', '$username', '$datanascusuario', '$telefoneusuario', '$generousuario', '$emailusuario', '$cpfusuario', '$senhausuario', '1', $freelancerusuario, '$fotoperfilusuario', '$biousuario');";

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


      $idusuario = isset($_GET['idusuario']);

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
        $biousuario = isset($data->biousuario) ? $data->biousuario : null;
        $fotoperfilusuario = isset($data->fotoperfilusuario) ? $data->fotoperfilusuario : null;

        //Valida Email
        $sql = "SELECT * FROM tbusuario u 
                WHERE u.emailusuario = '$emailusuario'
                AND NOT u.idusuario = $_GET[idusuario]";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse email já está em uso!");
          echo json_encode($response);
          return;
        }

        //Valida Username
        $sql = "SELECT * FROM tbusuario u 
                WHERE u.username = '$username'
                AND NOT u.idusuario = $_GET[idusuario]";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse nome de usuario já está em uso!");
          echo json_encode($response);
          return;
        }

        //Valida CPF
        $sql = "SELECT * FROM tbusuario u 
        WHERE u.cpfusuario = '$cpfusuario'
        AND NOT u.idusuario = $_GET[idusuario]";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $response = array("message" => "Esse CPF já está em uso!");
          echo json_encode($response);
          return;
        }

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
                        biousuario = '$biousuario'
                    WHERE idusuario=$_GET[idusuario];";

        if ($conn->query($sql) === TRUE) {
          // Registro de usuário atualizado com sucesso

          // Verifica se é um freelancer (freelancerusuario igual a 1)
          if ($freelancerusuario == 1) {
            // Atualiza os serviços oferecidos pelo freelancer na tabela tbfreelancerservico
            if (isset($data->codservico) && is_array($data->codservico)) {
              // Primeiro, exclua todos os registros existentes para este usuário
              $sqlDeleteServicos = "DELETE FROM tbfreelancerservico WHERE codfreelancer = $_GET[idusuario]";
              $conn->query($sqlDeleteServicos);

              // Em seguida, insira os novos registros
              foreach ($data->codservico as $codservico) {
                $sqlServico = "INSERT INTO tbfreelancerservico (codfreelancer, codservico) VALUES ($_GET[idusuario], $codservico)";
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