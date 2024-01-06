
<?php
// Inicie a sessão na página home.php
session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['usuario_autenticado'])) {
    header("Location: index.php");
    exit();
}

// Variável de sessão para armazenar os contatos
if (!isset($_SESSION['contatos'])) {
    $_SESSION['contatos'] = array();
}

// Adicione um novo contato se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
    $telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : "";
    $endereco = isset($_POST["endereco"]) ? $_POST["endereco"] : "";

    if ($nome != "" && $telefone != "" && $endereco != "") {
        $contato = array("nome" => $nome, "telefone" => $telefone, "endereco" => $endereco);
        $_SESSION['contatos'][] = $contato;
    }
}
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
                foreach ($_SESSION['contatos'] as $contato) {
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
