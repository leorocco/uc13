<?php
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = 'senac';
$dbName = 'sis_agenda';

// Conectar ao servidor MySQL
$conn = new mysqli($host, $user, $password);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o servidor MySQL: " . $conn->connect_error);
}

// Criar o banco de dados
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado com sucesso.<br>";
} else {
    echo "Erro ao criar o banco de dados: " . $conn->error . "<br>";
}

// Conectar ao banco de dados recém-criado
$conn->select_db($dbName);

// Criar a tabela de usuários
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabela de usuários criada com sucesso.<br>";
} else {
    echo "Erro ao criar a tabela de usuários: " . $conn->error . "<br>";
}

// Inserir um usuário de exemplo
$sql = "INSERT INTO usuarios (id, username, password) VALUES (0, 'admin', 'admin')";
if ($conn->query($sql) === TRUE) {
    echo "Usuário de exemplo inserido com sucesso.<br>";
} else {
    echo "Erro ao inserir o usuário de exemplo: " . $conn->error . "<br>";
}

// Criar a tabela de contatos
$sql = "CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabela de contatos criada com sucesso.<br>";
} else {
    echo "Erro ao criar a tabela de contatos: " . $conn->error . "<br>";
}

// Fechar a conexão
$conn->close();
?>