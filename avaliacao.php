<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
            // Prepara e executa a consulta SQL para recuperar as avaliacoes
            $sql = "SELECT a.*, c.nomeusuario AS nomecliente, f.nomeusuario AS nomefreelancer
            FROM tbavaliacao a
            INNER JOIN tbusuario c ON a.codcliente = c.idusuario
            INNER JOIN tbusuario f ON a.codfreelancer = f.idusuario";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $avaliacoes = array();

                // Coleta os dados das avaliacoes em um array
                while ($row = $result->fetch_assoc()) {
                    $avaliacao = array(
                        "idavaliacao" => $row["idavaliacao"],
                        "codprojeto" => $row["codprojeto"],
                        "codfreelancer" => $row["codfreelancer"],
                        "nomefreelancer" => $row["nomefreelancer"],
                        "codcliente" => $row["codcliente"],
                        "nomecliente" => $row["nomecliente"],
                        "notaavaliacao" => $row["notaavaliacao"],
                        "comentarioavaliacao" => $row["comentarioavaliacao"],
                        "fotoavaliacao" => $row["fotoavaliacao"]
                    );
                    array_push($avaliacoes, $avaliacao);
                }

                // Retorna as avaliacoes como JSON
                echo json_encode($avaliacoes);
            } else {
                // Se não houver avaliacoes, retorna uma mensagem de erro
                $response = array("message" => "Nenhuma avaliacao encontrada.");
                echo json_encode($response);
            }
        }
        break;
    case 'POST': {
            $data = json_decode(file_get_contents("php://input"));

            if (
                isset($data->codprojeto) &&
                isset($data->codcliente) &&
                isset($data->codfreelancer) &&
                isset($data->notaavaliacao)
            ) {
                $codcliente = $data->codcliente;
                $codprojeto = $data->codprojeto;
                $codfreelancer = $data->codfreelancer;
                $notaavaliacao = $data->notaavaliacao;

                // Campos opcionais
                $comentarioavaliacao = isset($data->comentarioavaliacao) ? "'" . $data->comentarioavaliacao . "'" : "NULL";
                $fotoavaliacao = isset($data->fotoavaliacao) ? "'" . $data->fotoavaliacao . "'" : "NULL";

                $sql = "INSERT INTO tbavaliacao (codprojeto, codcliente, codfreelancer, notaavaliacao, comentarioavaliacao, fotoavaliacao) 
                    VALUES ($codprojeto, $codcliente, $codfreelancer, $notaavaliacao, '$comentarioavaliacao', $fotoavaliacao)";

                if ($conn->query($sql) === TRUE) {
                    $response = array("message" => "Avaliação cadastrada com sucesso.");
                    echo json_encode($response);
                } else {
                    $response = array("message" => "Erro ao cadastrar avaliação: " . $conn->error);
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
                isset($data->idavaliacao) &&
                isset($data->codprojeto) &&
                isset($data->codcliente) &&
                isset($data->codfreelancer) &&
                isset($data->notaavaliacao)
            ) {
                // Dados do usuário recebidos
                $idavaliacao = $data->idavaliacao;
                $codprojeto = $data->codprojeto;
                $codcliente = $data->codcliente;
                $codfreelancer = $data->codfreelancer;
                $notaavaliacao = $data->notaavaliacao;


                // Campos opcionais
                $comentarioavaliacao = isset($data->comentarioavaliacao) ? $data->comentarioavaliacao : null;
                $fotoavaliacao = isset($data->fotoavaliacao) ? $data->fotoavaliacao : null;

                // Prepara e executa a consulta SQL para atualizar o usuário
                $sql = "UPDATE tbavaliacao 
      SET codprojeto = '$codprojeto', 
          codfreelancer = '$codfreelancer', 
          codcliente = '$codcliente', 
          notaavaliacao = '$notaavaliacao', 
          comentarioavaliacao = '$comentarioavaliacao', 
          fotoavaliacao = '$fotoavaliacao'
      WHERE idavaliacao = $idavaliacao";

                if ($conn->query($sql) === TRUE) {
                    // Registro de usuário atualizado com sucesso

                    $response = array("message" => "Avaliação atualizado com sucesso.");
                    echo json_encode($response);
                } else {
                    // Erro na atualização do usuário
                    $response = array("message" => "Erro ao atualizar avaliacao: " . $conn->error);
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
            if (isset($_GET['idavaliacao'])) {
                $idavaliacao = $_GET['idavaliacao'];

                // Cria a conexão
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verifica a conexão
                if ($conn->connect_error) {
                    die("Erro na conexão: " . $conn->connect_error);
                }

                // Prepara e executa a consulta SQL para excluir o registro com base no ID
                $sql = "DELETE FROM tbavaliacao WHERE idavaliacao = $idavaliacao";

                if ($conn->query($sql) === TRUE) {
                    // Registro excluído com sucesso
                    $response = array("message" => "avaliacao excluída com sucesso.");
                    echo json_encode($response);
                } else {
                    // Erro na exclusão
                    $response = array("message" => "Erro ao excluir avaliacao: " . $conn->error);
                    echo json_encode($response);
                }
            } else {
                // ID inválido ou ausente
                $response = array("message" => "ID de avaliacao inválido ou ausente.");
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