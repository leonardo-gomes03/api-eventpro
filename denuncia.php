<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      // Prepara e executa a consulta SQL para recuperar as denuncias
      $sql = "SELECT d.*, c.nomeusuario AS nomeautor, f.nomeusuario AS nomedenunciado
            FROM tbdenuncia d
            INNER JOIN tbusuario c ON d.codautordenuncia = c.idusuario
            INNER JOIN tbusuario f ON d.coddenunciado = f.idusuario";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $denuncias = array();

        // Coleta os dados das denuncias em um array
        while ($row = $result->fetch_assoc()) {
          $denuncia = array(
            "iddenuncia" => $row["iddenuncia"],
            "codautordenuncia" => $row["codautordenuncia"],
            "nomeautor" => $row["nomeautor"],
            "coddenunciado" => $row["coddenunciado"],
            "nomedenunciado" => $row["nomedenunciado"],
            "comentariodenuncia" => $row["comentariodenuncia"]
          );
          array_push($denuncias, $denuncia);
        }

        // Retorna as denuncias como JSON
        echo json_encode($denuncias);
      } else {
        // Se não houver denuncias, retorna uma mensagem de erro
        $response = array("message" => "Nenhuma denuncia encontrada.");
        echo json_encode($response);
      }
    }
    break;
  case 'POST': {
      $data = json_decode(file_get_contents("php://input"));

      if (
        isset($data->codautordenuncia) &&
        isset($data->coddenunciado) &&
        isset($data->comentariodenuncia)
      ) {
        $codautordenuncia = $data->codautordenuncia;
        $coddenunciado = $data->coddenunciado;
        $comentariodenuncia = $data->comentariodenuncia;

        $sql = "INSERT INTO tbdenuncia (codautordenuncia, coddenunciado, comentariodenuncia) 
                    VALUES ($codautordenuncia, $coddenunciado, '$comentariodenuncia')";

        if ($conn->query($sql) === TRUE) {
          $response = array("message" => "Denúncia cadastrada com sucesso.");
          echo json_encode($response);
        } else {
          $response = array("message" => "Erro ao cadastrar Denúncia: " . $conn->error);
          echo json_encode($response);
        }
      } else {
        $response = array("message" => "Dados inválidos ou ausentes.");
        echo json_encode($response);
      }
      break;
    }
  case 'PUT': {
      // Recebe os dados JSON da solicitação POST
      $data = json_decode(file_get_contents("php://input"));

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->iddenuncia) &&
        isset($data->codautordenuncia) &&
        isset($data->coddenunciado) &&
        isset($data->comentariodenuncia)
      ) {
        // Dados do usuário recebidos
        $iddenuncia = $data->iddenuncia;
        $codautordenuncia = $data->codautordenuncia;
        $coddenunciado = $data->coddenunciado;
        $comentariodenuncia = $data->comentariodenuncia;

        // Prepara e executa a consulta SQL para atualizar a denuncia
        $sql = "UPDATE tbdenuncia 
      SET codautordenuncia = '$codautordenuncia', 
          coddenunciado = '$coddenunciado', 
          comentariodenuncia = '$comentariodenuncia'
      WHERE iddenuncia = $iddenuncia";

        if ($conn->query($sql) === TRUE) {
          // Registro de usuário atualizado com sucesso

          $response = array("message" => "Denúncia atualizado com sucesso.");
          echo json_encode($response);
        } else {
          // Erro na atualização do usuário
          $response = array("message" => "Erro ao atualizar denuncia: " . $conn->error);
          echo json_encode($response);
        }
      } else {
        // Dados ausentes ou inválidos
        $response = array("message" => "Dados inválidos ou ausentes.");
        echo json_encode($response);
      } // Aqui voce coloca todo o codigo do PUT (Update)
    }
    break;
  case 'DELETE': {
      if (isset($_GET['iddenuncia'])) {
        $iddenuncia = $_GET['iddenuncia'];

        // Cria a conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
          die("Erro na conexão: " . $conn->connect_error);
        }

        // Prepara e executa a consulta SQL para excluir o registro com base no ID
        $sql = "DELETE FROM tbdenuncia WHERE iddenuncia = $iddenuncia";

        if ($conn->query($sql) === TRUE) {
          // Registro excluído com sucesso
          $response = array("message" => "denuncia excluída com sucesso.");
          echo json_encode($response);
        } else {
          // Erro na exclusão
          $response = array("message" => "Erro ao excluir denuncia: " . $conn->error);
          echo json_encode($response);
        }
      } else {
        // ID inválido ou ausente
        $response = array("message" => "ID de denuncia inválido ou ausente.");
        echo json_encode($response);
      } // Aqui voce coloca todo o codigo do DELETE (Delete)
    }
    break;
  default:
    echo json_encode(array("message" => "Método não registrado"));
    break;
}

$conn->close();
?>