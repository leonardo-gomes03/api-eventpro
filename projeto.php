<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      // Consulta SQL para selecionar todos os projetos com seus serviços
      $sql = "SELECT pr.*, t.nometipo, s.nomeservico, u.nomeusuario, c.nomecidade, ps.codservico
              FROM tbprojeto pr
              INNER JOIN tbcidade c on pr.codcidade = c.idcidade
              INNER JOIN tbtipo t ON pr.codtipo = t.idtipo
              INNER JOIN tbprojetoservico ps ON pr.idprojeto = ps.codprojeto
              INNER JOIN tbservico s ON ps.codservico = s.idservico
              INNER JOIN tbusuario u ON pr.codcliente=u.idusuario";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $projetos = array();

        while ($row = $result->fetch_assoc()) {
          $idprojeto = $row["idprojeto"];

          // Verifica se já existe um array para este projeto
          if (!isset($projetos[$idprojeto])) {
            // Se não existir, crie um array para este projeto
            $projetos[$idprojeto] = array(
              "idprojeto" => $row["idprojeto"],
              "codcliente" => $row["codcliente"],
              "nomeusuario" => $row["nomeusuario"],
              "codcidade" => $row["codcidade"],
              "nomecidade" => $row["nomecidade"],
              "codtipo" => $row["codtipo"],
              "nometipo" => $row["nometipo"],
              "tituloprojeto" => $row["tituloprojeto"],
              "horainicioprojeto" => $row["horainicioprojeto"],
              "horafimprojeto" => $row["horafimprojeto"],
              "dataprojeto" => $row["dataprojeto"],
              "descricaoprojeto" => $row["descricaoprojeto"],
              "qtdpessoas" => $row["qtdpessoas"],
              "cidadeprojeto" => $row["cidadeprojeto"],
              "codservico" => array(),
              "nomeservico" => array()
            );
          }

          // Adicione as informações do serviço a este projeto
          $projetos[$idprojeto]["nomeservico"][] = $row["nomeservico"];
          $projetos[$idprojeto]["codservico"][] = $row["codservico"];
        }

        // Retorna os projetos em formato JSON
        echo json_encode(array_values($projetos));
      } else {
        // Não há projetos na tabela
        echo json_encode(array("message" => "Nenhum projeto encontrado."));
      }
    }
    break;
  case 'POST': {
      // Recebe os dados JSON da solicitação POST
      $data = json_decode(file_get_contents("php://input"));

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->codcliente) &&
        isset($data->codtipo) &&
        isset($data->codcidade) &&
        isset($data->tituloprojeto) &&
        isset($data->horainicioprojeto) &&
        isset($data->horafimprojeto) &&
        isset($data->dataprojeto) &&
        isset($data->qtdpessoas) &&
        isset($data->codservico)
      ) {
        // Dados recebidos
        $codcliente = $data->codcliente;
        $codtipo = $data->codtipo;
        $codcidade = $data->codcidade;
        $tituloprojeto= $data->tituloprojeto;
        $horainicioprojeto = $data->horainicioprojeto;
        $horafimprojeto = $data->horafimprojeto;
        $dataprojeto = $data->dataprojeto;
        $qtdpessoas = $data->qtdpessoas;

        // Campos opcionais
        $descricaoprojeto = isset($data->descricaoprojeto) ? $data->descricaoprojeto : null;

        // Prepara e executa a consulta SQL para inserir o projeto
        $sql = "INSERT INTO tbprojeto (codcliente, codtipo, codcidade, tituloprojeto, horainicioprojeto, horafimprojeto, dataprojeto, descricaoprojeto, qtdpessoas) 
                VALUES ($codcliente, $codtipo, $codcidade, '$tituloprojeto', '$horainicioprojeto', '$horafimprojeto', '$dataprojeto', '$descricaoprojeto', $qtdpessoas)";

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
    }
    break;
  case 'PUT': {

      // Recebe os dados JSON da solicitação PUT
      $data = json_decode(file_get_contents("php://input"));

      // Verifica se os dados estão presentes e são válidos
      if (
        isset($data->idprojeto) &&
        isset($data->codcliente) &&
        isset($data->codtipo) &&
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
    }
    break;
  case 'DELETE': {
      // Verifica se foi passado um ID válido para exclusão
      if (isset($_GET['idprojeto'])) {
        $idprojeto = $_GET['idprojeto'];

        // Primeiro, exclua os registros relacionados na tabela tbprojetoservico
        $sqlExcluirServicos = "DELETE FROM tbprojetoservico WHERE codprojeto = $idprojeto";

        if ($conn->query($sqlExcluirServicos) === TRUE) {
          // Registros relacionados excluídos com sucesso, agora exclua o projeto principal
          $sqlExcluirProjeto = "DELETE FROM tbprojeto WHERE idprojeto = $idprojeto";

          if ($conn->query($sqlExcluirProjeto) === TRUE) {
            // Projeto excluído com sucesso
            $response = array("message" => "Projeto e registros relacionados excluídos com sucesso.");
            echo json_encode($response);
          } else {
            // Erro na exclusão do projeto principal
            $response = array("message" => "Erro ao excluir projeto: " . $conn->error);
            echo json_encode($response);
          }
        } else {
          // Erro na exclusão dos registros relacionados
          $response = array("message" => "Erro ao excluir registros relacionados: " . $conn->error);
          echo json_encode($response);
        }
      } else {
        // ID inválido ou ausente
        $response = array("message" => "ID inválido ou ausente.");
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