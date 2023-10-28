<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {

      $idusuario = isset($_GET['idusuario']);

      if (strlen($idusuario) > 0) {
        $sql = "SELECT p.*, u.nomeusuario, s.nomeservico
        FROM tbproposta p
        INNER JOIN tbusuario u ON p.codfreelancer=u.idusuario
        INNER JOIN tbservico s ON s.idservico=p.codservico where u.idusuario = '$_GET[idusuario]'";

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
      // Prepara e executa a consulta SQL para recuperar as propostas
      $sql = "SELECT p.*, u.nomeusuario, s.nomeservico
              FROM tbproposta p
              INNER JOIN tbusuario u ON p.codfreelancer=u.idusuario
              INNER JOIN tbservico s ON s.idservico=p.codservico ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $propostas = array();

        // Coleta os dados das propostas em um array
        while ($row = $result->fetch_assoc()) {
          $proposta = array(
            "idproposta" => $row["idproposta"],
            "codprojeto" => $row["codprojeto"],
            "codfreelancer" => $row["codfreelancer"],
            "nomeusuario" => $row["nomeusuario"],
            "codservico" => $row["codservico"],
            "nomeservico" => $row["nomeservico"],
            "statusproposta" => $row["statusproposta"],
            "descricaoproposta" => $row["descricaoproposta"],
            "valorproposta" => $row["valorproposta"],
            "notaavaliacao" => $row["notaavaliacao"],
            "comentarioavaliacao" => $row["comentarioavaliacao"],
            "fotoavaliacao" => $row["fotoavaliacao"]
          );
          array_push($propostas, $proposta);
        }

        // Retorna as propostas como JSON
        echo json_encode($propostas);
      } else {
        // Se não houver propostas, retorna uma mensagem de erro
        $response = array("message" => "Nenhuma proposta encontrada.");
        echo json_encode($response);
      }
    }
    break;
  case 'POST': {
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
        $codservico = $data->codservico;
        $statusproposta = $data->statusproposta;
        $valorproposta = $data->valorproposta;

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
    }
    break;
  case 'PUT': {
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
      } // Aqui voce coloca todo o codigo do PUT (Update)
    }
    break;
  case 'DELETE': {
      // ID da proposta que você deseja excluir (pode ser passado via GET ou POST)
      if (isset($_GET['idproposta'])) {
        $idproposta = $_GET['idproposta'];

        // Cria a conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
          die("Erro na conexão: " . $conn->connect_error);
        }

        // Prepara e executa a consulta SQL para excluir o registro com base no ID
        $sql = "DELETE FROM tbproposta WHERE idproposta = $idproposta";

        if ($conn->query($sql) === TRUE) {
          // Registro excluído com sucesso
          $response = array("message" => "Proposta excluída com sucesso.");
          echo json_encode($response);
        } else {
          // Erro na exclusão
          $response = array("message" => "Erro ao excluir proposta: " . $conn->error);
          echo json_encode($response);
        }
      } else {
        // ID inválido ou ausente
        $response = array("message" => "ID de proposta inválido ou ausente.");
        echo json_encode($response);
      } // Aqui voce coloca todo o codigo do DELETE (Delete)
    }
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>