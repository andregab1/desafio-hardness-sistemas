<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <style>

        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f8f8; color: #333; }
        h1, h3 { color:rgb(48, 25, 178); }
        form { background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; margin-bottom: 30px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease; }
        button:hover { background: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { border: 1px solid #e0e0e0; padding: 12px 15px; text-align: left; }
        th { background-color: #e9e9e9; font-weight: bold; color: #444; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-edit, .btn-delete { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: white; display: inline-block; margin-right: 5px; transition: background-color 0.3s ease; }
        .btn-edit { background-color: #007bff; }
        .btn-edit:hover { background-color: #0056b3; }
        .btn-delete { background-color: #dc3545; }
        .btn-delete:hover { background-color: #c82333; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; text-align: center; }
        .message.success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .message.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <?php
    include 'conexao.php';

    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'excluido_sucesso') {
            echo "<p class='message success'>Cliente excluído com sucesso!</p>";
        } elseif ($_GET['status'] == 'excluido_erro') {
            $mensagem_erro = isset($_GET['mensagem']) ? htmlspecialchars(urldecode($_GET['mensagem'])) : "falha ao se conectar com o banco de dados";
            echo "<p class='message error'>Não foi possível excluir o cliente: {$mensagem_erro}</p>";
        } elseif ($_GET['status'] == 'atualizado_sucesso') {
            echo "<p class='message success'>Cliente atualizado com sucesso!</p>";
        } elseif ($_GET['status'] == 'atualizado_erro') {
            $mensagem_erro = isset($_GET['mensagem']) ? htmlspecialchars(urldecode($_GET['mensagem'])) : "Erro ao atualizar.";
            echo "<p class='message error'>Problema para atualizar o cliente: {$mensagem_erro}</p>";
        } elseif ($_GET['status'] == 'cadastro_sucesso') {
             echo "<p class='message success'>Cliente cadastrado com sucesso! Bem-vindo(a)!</p>";
        } elseif ($_GET['status'] == 'cadastro_erro') {
            $mensagem_erro = isset($_GET['mensagem']) ? htmlspecialchars(urldecode($_GET['mensagem'])) : "Não foi possivel cadastrar.";
            echo "<p class='message error'>Erro ao cadastrar cliente: {$mensagem_erro}</p>";
        }
    }

    echo "<h1>Cadastro de Clientes</h1>";

    if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $stmt = $conn->prepare("INSERT INTO tb_clientes (nome, telefone, endereco) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $telefone, $endereco);

        if ($stmt->execute()) {

            header("Location: index.php?status=cadastro_sucesso");
            exit();
        } else {
            header("Location: index.php?status=cadastro_erro&mensagem=" . urlencode($stmt->error));
            exit();
        }
        $stmt->close(); 
    }
    ?>

    <h3>Cadastrar Novo Cliente</h3>
    <form action="index.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone"><br>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco"><br>

        <button type="submit" name="cadastrar">Cadastrar</button>
    </form>

    <?php
    ?>
    <h3>Clientes Cadastrados</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th> <th>Nome</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Ações</th> </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nome, telefone, endereco FROM tb_clientes";
            $result = $conn->query($sql); 

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>"; 
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";       
                    echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";     
                    echo "<td>" . htmlspecialchars($row["telefone"]) . "</td>"; 
                    echo "<td>" . htmlspecialchars($row["endereco"]) . "</td>"; 
                    echo "<td>"; 
                        echo "<a href='editar.php?id=" . htmlspecialchars($row["id"]) . "' class='btn-edit'>Editar</a> ";
                        echo "<a href='excluir.php?id=" . htmlspecialchars($row["id"]) . "' class='btn-delete' onclick='return confirm(\"Tem certeza que deseja excluir este cliente?\")'>Excluir</a>";
                    echo "</td>";
                    echo "</tr>"; 
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum cliente cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>