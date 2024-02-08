<?php
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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verificar se o ID do contato foi passado como parâmetro na URL
    if (isset($_GET['id'])) {
        $idContato = $_GET['id'];

        // Buscar informações do contato no banco de dados
        $query = "SELECT * FROM contatos WHERE id = $idContato";
        $resultado = mysqli_query($conn, $query);

        // Verificar se o contato foi encontrado
        if (mysqli_num_rows($resultado) == 1) {
            $contato = mysqli_fetch_assoc($resultado);
        } else {
            // Redirecionar se o contato não for encontrado
            header("Location: home.php");
            exit();
        }
    } else {
        // Redirecionar se o ID do contato não foi fornecido
        header("Location: home.php");
        exit();
    }
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter dados do formulário
    $idContato = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Atualizar informações do contato no banco de dados
    $query = "UPDATE contatos SET nome='$nome', telefone='$telefone', endereco='$endereco' WHERE id=$idContato";
    $resultado = mysqli_query($conn, $query);

    if ($resultado) {
        $_SESSION['tipo_alerta'] = 'success';
        $_SESSION['texto_alerta'] = "Contato Atualizado com sucesso!";
        header("Location: home.php");
        exit();
    } else {
        // Tratar erro, se houver
        $_SESSION['tipo_alerta'] = 'error';
        $_SESSION['texto_alerta'] = "Erro ao atualizar o contato: " . mysqli_error($conexao);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Editar Contato</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Contato</h2>
    <form method="post" action="editar_contato.php">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $contato['nome']; ?>" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $contato['telefone']; ?>" required>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $contato['endereco']; ?>" required>
        </div>
        <input type="hidden" name="id" value="<?php echo $contato['id']; ?>">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
