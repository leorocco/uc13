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
                    echo 'Endereço: ' . $contato['endereco'] . '<br>';;
                    // Link de Edição 
                    echo '<a href="editar_contato.php?id=' . $contato['id'] . '" class="btn btn-warning btn-sm mt-2">Editar</a>';
                     // Botão de Exclusão 
                    echo '<a href="#" class="btn btn-danger btn-sm mt-2 ml-2" data-toggle="modal" data-target="#confirmarExclusao' . $contato['id'] .'">Excluir</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="confirmarExclusao" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="contatoExclusao"></p>
                <input type="hidden" id="contatoIdExclusao">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarExclusaoBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Captura o ID do contato quando o botão de exclusão é clicado
        $(".excluirContato").click(function(){
            var contatoId = $(this).data('id');
            var contatoNome = $(this).closest('li').find('strong').text();
            $("#contatoExclusao").text("Tem certeza de que deseja excluir o contato '" + contatoNome + "'?");
            $("#contatoIdExclusao").val(contatoId);
            $("#confirmarExclusao").modal('show');
        });

        // Ao clicar no botão de confirmação de exclusão, redireciona para a página de exclusão com o ID do contato
        $("#confirmarExclusaoBtn").click(function(){
            var contatoId = $("#contatoIdExclusao").val();
            window.location.href = "excluir_contato.php?id=" + contatoId;
        });
    });
</script>


</body>
</html>
