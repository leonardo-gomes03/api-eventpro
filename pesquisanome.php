<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
            if (isset($_GET['nomeusuario'])) {
                $nomeusuario = $_GET['nomeusuario'];

                // Consulta SQL para pesquisar projetos com base no título
                $sql = "SELECT * 
                    FROM tbusuario 
                    WHERE nomeusuario LIKE '%$nomeusuario%'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $projetos = array();

                    while ($row = $result->fetch_assoc()) {
                        $projetos[] = $row;
                    }

                    // Retorna os projetos encontrados em formato JSON
                    echo json_encode($projetos);
                } else {
                    // Não foram encontrados projetos correspondentes ao título
                    echo json_encode(array("message" => "Nenhum usuário encontrado com esse título."));
                }
            } else {
                // Título de pesquisa ausente
                echo json_encode(array("message" => "Por favor, forneça um usuário para pesquisa."));
            }
        }
        break;
    default:
        echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
        break;


}

$conn->close();
?>