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

// Fecha a conexão com o banco de dados
$conn->close();
?>
