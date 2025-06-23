<?php
include 'conexao.php';

$cliente = null; 


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT id, nome, telefone, endereco FROM tb_clientes WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $cliente = $result->fetch_assoc(); 
    } else {
        echo "<p style='color: red;'>Ops! Não conseguimos encontrar esse cliente. Por favor, verifique se as informações estão corretas ou se o cadastro já foi realizado.</p>";
        header("Location: index.php?status=atualizado_erro&mensagem=" . urlencode("Cliente não encontrado para edição."));
        exit();
    }
    $stmt->close(); 
}

if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];


    $stmt = $conn->prepare("UPDATE tb_clientes SET nome = ?, telefone = ?, endereco = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $telefone, $endereco, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=atualizado_sucesso");
        exit();
    } else {
        header("Location: index.php?status=atualizado_erro&mensagem=" . urlencode($stmt->error));
        exit();
    }
    $stmt->close();

if ($cliente === null && !isset($_POST['atualizar'])) {
    header("Location: index.php");
    exit();
}
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f8f8; color: #333; }
        h1 { color: #0056b3; }
        form { background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; margin-bottom: 30px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease; }
        button:hover { background: #0056b3; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #007bff; font-weight: bold; transition: color 0.3s ease; }
        .back-link:hover { color: #0056b3; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; text-align: center; }
        .message.success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .message.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Editar Cliente</h1>

    <?php
    if (isset($status_message)) {
        echo $status_message;
    }

    if ($cliente):
    ?>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($cliente['id']); ?>">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>"><br>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco']); ?>"><br>

        <button type="submit" name="atualizar">Atualizar</button>
    </form>
    <?php else: ?>
        <p>Cliente não encontrado, tente novamente</p>
    <?php endif; ?>

    <a href="index.php" class="back-link">Voltar para a Lista de Clientes</a>

    <?php
    $conn->close();
    ?>

</body>
</html>