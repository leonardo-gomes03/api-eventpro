<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      $sql = "SELECT  p.*, u.*, s.nomeservico, pj.tituloprojeto, pj.datahorafim
              FROM tbproposta p
              INNER JOIN tbusuario u ON p.codfreelancer=u.idusuario
              INNER JOIN tbservico s ON s.idservico=p.codservico
              INNER JOIN tbprojeto pj ON p.codprojeto = pj.idprojeto ";


      $freelancerId = isset($_GET['idusuario']);
      $clienteId = isset($_GET['idcliente']);
      $status = isset($_GET['status']);

      if ($freelancerId || $clienteId || $status) {
        $sql .= "WHERE 1 = 1 ";

        if ($freelancerId) {
          $sql .= " AND p.codfreelancer = $_GET[idusuario] ";
        }

        if ($clienteId) {
          $sql .= " AND pj.codcliente = $_GET[idcliente] ";
        }

        if ($status) {
          if ($_GET['status'] == "analise") {
            $sql .= " AND p.statusproposta = 'Em análise'";
          }

          if ($_GET['status'] == "aceita") {
            $sql .= " AND p.statusproposta = 'Aceita'";
          }

          if ($_GET['status'] == "recusada") {
            $sql .= " AND p.statusproposta = 'Recusada'";
          }
        }
      }

      $sql .= " ORDER BY p.idproposta DESC";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $propostas = array();

        // Coleta os dados das propostas em um array
        while ($row = $result->fetch_assoc()) {
          $proposta = array(
            "idproposta" => $row["idproposta"],
            "codprojeto" => $row["codprojeto"],
            "tituloprojeto" => $row["tituloprojeto"],
            "codfreelancer" => $row["codfreelancer"],
            "nomeusuario" => $row["nomeusuario"],
            "username" => $row["username"],
            "datanascusuario" => $row["datanascusuario"],
            "telefoneusuario" => $row["telefoneusuario"],
            "contato" => $row["contato"],
            "generousuario" => $row["generousuario"],
            "emailusuario" => $row["emailusuario"],
            "cpfusuario" => $row["cpfusuario"],
            // "senhausuario" => $row["senhausuario"],
            "statususuario" => $row["statususuario"],
            "fotoperfilusuario" => $row["fotoperfilusuario"],
            "biousuario" => $row["biousuario"],
            "codservico" => $row["codservico"],
            "nomeservico" => $row["nomeservico"],
            "statusproposta" => $row["statusproposta"],
            "descricaoproposta" => $row["descricaoproposta"],
            "valorproposta" => $row["valorproposta"],
            "datahorafim" => $row["datahorafim"]
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
        isset($data->codservico) &&
        isset($data->contato) &&
        isset($data->valorproposta)
      ) {
        // Dados recebidos
        $codprojeto = $data->codprojeto;
        $codfreelancer = $data->codfreelancer;
        $codservico = $data->codservico;
        $contato = $data->contato;
        $valorproposta = $data->valorproposta;

        // Verifica se outros campos opcionais estão definidos
        $descricaoproposta = isset($data->descricaoproposta) ? $data->descricaoproposta : null;

        // Prepara e executa a consulta SQL para inserir a proposta
        $sql = "INSERT INTO tbproposta (codprojeto, codfreelancer, codservico, contato, statusproposta, valorproposta, descricaoproposta) 
                VALUES ($codprojeto, $codfreelancer, $codservico, '$contato', 'Em análise', '$valorproposta', '$descricaoproposta')";

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
    
      $idproposta = $_GET['idproposta'];

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($idproposta) &&
        isset($data->statusproposta)
      ) {
        // Dados do usuário recebidos
        $statusproposta = $data->statusproposta;


        // Campos opcionais
        $descricaoproposta = isset($data->descricaoproposta) ? $data->descricaoproposta : null;

        // Prepara e executa a consulta SQL para atualizar o usuário
        $sql = "UPDATE tbproposta 
                SET statusproposta = '$statusproposta'
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
