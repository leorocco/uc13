<?php
session_start();

// Verifica se o formulário de login foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Obtém dados do formulário
    $usuarioDigitado = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
    $senhaDigitada = isset($_POST["senha"]) ? $_POST["senha"] : "";

    // Consulta para verificar o usuário e senha
    $sql = "SELECT * FROM usuarios WHERE username = '$usuarioDigitado' AND password = '$senhaDigitada'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Usuário autenticado, redireciona para a página home
        $row = $result->fetch_assoc();
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['usuario_autenticado'] = $row['username'];
        header("Location: home.php");
        exit();
    } else {
        // Exibe mensagem de erro se a autenticação falhar
        echo '<div class="alert alert-danger" role="alert">Usuário ou senha incorretos.</div>';
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form method="post" action="index.php">
                        <div class="form-group">
                            <label for="usuario">Usuário:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
