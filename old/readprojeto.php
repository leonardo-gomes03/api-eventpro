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

// Consulta SQL para selecionar todos os projetos com seus serviços
$sql = "SELECT pr.*, t.nometipo, s.nomeservico, u.nomeusuario
        FROM tbprojeto pr
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
                "codtipo" => $row["codtipo"],
                "nometipo" => $row["nometipo"],
                "horainicioprojeto" => $row["horainicioprojeto"],
                "horafimprojeto" => $row["horafimprojeto"],
                "dataprojeto" => $row["dataprojeto"],
                "descricaoprojeto" => $row["descricaoprojeto"],
                "qtdpessoas" => $row["qtdpessoas"],
                "cidadeprojeto" => $row["cidadeprojeto"],
                "nomeservico" => array()
            );
        }

        // Adicione as informações do serviço a este projeto
        $projetos[$idprojeto]["nomeservico"][] = $row["nomeservico"];
    }

    // Retorna os projetos em formato JSON
    echo json_encode(array_values($projetos));
} else {
    // Não há projetos na tabela
    echo json_encode(array("message" => "Nenhum projeto encontrado."));
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
