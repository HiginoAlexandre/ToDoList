<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    // O usuário não está autenticado, retorne um erro
    http_response_code(401);
    echo "Erro: Usuário não autenticado.";
    exit();
}

// Verifica se a solicitação é do tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // A solicitação não é do tipo POST, retorne um erro
    http_response_code(405);
    echo "Erro: Método não permitido.";
    exit();
}

// Verifica se o campo 'task' está presente na solicitação
if (!isset($_POST['task'])) {
    // O campo 'task' não está presente na solicitação, retorne um erro
    http_response_code(400);
    echo "Erro: Parâmetros ausentes.";
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

// Prepara a declaração SQL para inserir a nova tarefa no banco de dados
$stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
$stmt->bind_param("is", $_SESSION['user_id'], $_POST['task']);

// Executa a declaração SQL
if ($stmt->execute()) {
    // A tarefa foi adicionada com sucesso ao banco de dados
    echo "Tarefa adicionada com sucesso.";
} else {
    // Houve um erro ao adicionar a tarefa ao banco de dados, retorne um erro
    http_response_code(500);
    echo "Erro: Falha ao adicionar a tarefa ao banco de dados.";
}

// Fecha a declaração e a conexão com o banco de dados
$stmt->close();
$conn->close();
?>
