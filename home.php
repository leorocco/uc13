<?php
// Inicie a sessão na página home.php
session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['usuario_autenticado'])) {
    header("Location: index.php");
    exit();
}

// Configurações do banco de dados
$host = '127.0.0.1';
$user = 'root';
$password = 'senac';
$dbName = 'minha_agenda';

// Conecta ao banco de dados
$conn = new mysqli($host, $user, $password, $dbName);


// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Adicione um novo contato se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
    $telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : "";
    $endereco = isset($_POST["endereco"]) ? $_POST["endereco"] : "";

    if ($nome != "" && $telefone != "" && $endereco != "") {
        $sql = "INSERT INTO contatos (nome, telefone, endereco, id_usuario) VALUES ('$nome', '$telefone', '$endereco', '{$_SESSION['id_usuario']}')";
        if ($conn->query($sql) === TRUE) {
            // Adiciona o contato na sessão se a inserção for bem-sucedida
            $contato = array("nome" => $nome, "telefone" => $telefone, "endereco" => $endereco);
            $_SESSION['contatos'][] = $contato;
        } else {
            echo "Erro ao inserir o contato: " . $conn->error;
        }
    }
}

// Consulta SQL para obter os contatos do usuário atual
$sql = "SELECT * FROM contatos WHERE id_usuario = '{$_SESSION['id_usuario']}'";
$result = $conn->query($sql);

// Array para armazenar os contatos recuperados do banco de dados
$contatosFromDB = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contatosFromDB[] = $row;
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-4">Bem-vindo, <?php echo $_SESSION['usuario_autenticado']; ?>!</h1>
        <p class="lead">Esta é a sua página inicial.</p>
        <!-- Botão de logout -->
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h2>Adicionar Contato</h2>
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Número de Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar Contato</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2>Lista de Contatos</h2>
            <ul class="list-group">
                <?php
                foreach ($contatosFromDB as $contato) {
                    echo '<li class="list-group-item">';
                    echo '<strong>' . $contato['nome'] . '</strong><br>';
                    echo 'Telefone: ' . $contato['telefone'] . '<br>';
                    echo 'Endereço: ' . $contato['endereco'];
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
