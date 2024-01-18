<?php
    session_start();
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

    <?php
    // Verifica se o formulário de login foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Variáveis de usuário e senha (substitua pelos valores desejados)
        $usuarioCorreto = "leo";
        $senhaCorreta = "leo";

        $usuarioAlternativo = "bla";
        $senhaAlternativa = "bla";

        // Obtém dados do formulário
        $usuarioDigitado = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
        $senhaDigitada = isset($_POST["senha"]) ? $_POST["senha"] : "";

        // Verifica usuário e senha
        if ( 
            ($usuarioDigitado == $usuarioCorreto && $senhaDigitada == $senhaCorreta) ||
            ($usuarioDigitado == $usuarioAlternativo && $senhaDigitada == $senhaAlternativa)
        ) {
            $_SESSION['usuario_autenticado'] = $usuarioDigitado;
            // Redireciona para a página home se a autenticação for bem-sucedida
            header("Location: home.php");
            exit();
        } else {
            // Exibe mensagem de erro se a autenticação falhar
            echo '<div class="alert alert-danger" role="alert">Usuário ou senha incorretos.</div>';
        }
    }
    ?>
    
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