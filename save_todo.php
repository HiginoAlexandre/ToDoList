<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    // O usuário não está autenticado, retorne um erro
    http_response_code(401);
    echo "Erro: Usuário não autenticado.";
    exit();
}

// Conecta-se ao banco de dados (substitua os valores conforme necessário)
$conn = new mysqli("localhost", "root", "", "todolist");

// Verifica se houve um erro na conexão
if ($conn->connect_error) {
    // Houve um erro na conexão, retorne um erro
    http_response_code(500);
    echo "Erro: Falha na conexão com o banco de dados.";
    exit();
}

// Prepara a declaração SQL para selecionar as tarefas do usuário do banco de dados
$stmt = $conn->prepare("SELECT task FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);

// Executa a declaração SQL
$stmt->execute();

// Obtém o resultado da consulta
$result = $stmt->get_result();

// Cria um array para armazenar as tarefas do usuário
$tarefas = array();

// Adiciona as tarefas do usuário ao array
while ($row = $result->fetch_assoc()) {
    $tarefas[] = $row;
}

// Retorna as tarefas do usuário como JSON
echo json_encode($tarefas);

// Fecha a declaração e a conexão com o banco de dados
$stmt->close();
$conn->close();
?>
