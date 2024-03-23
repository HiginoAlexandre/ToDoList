<?php
// Conexão com o banco de dados
// Substitua "seu_servidor", "seu_usuario", "sua_senha" e "seu_banco_de_dados" pelos valores corretos
$conn = new mysqli("localhost", "root", "", "todolist");

// Verifique se houve um erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash da senha para segurança
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar e executar a consulta SQL para inserir o novo usuário
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso.";
    } else {
        echo "Erro ao registrar o usuário: " . $conn->error;
    }

    // Fechar a declaração
    $stmt->close();
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Nome de usuário:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Senha:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
