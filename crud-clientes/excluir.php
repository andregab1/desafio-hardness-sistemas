<?php
include 'conexao.php'; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tb_clientes WHERE id = ?");
    $stmt->bind_param("i", $id); 

    if ($stmt->execute()) {
        header("Location: index.php?status=excluido_sucesso");
        exit(); 
    } else {
        header("Location: index.php?status=excluido_erro&mensagem=" . urlencode($stmt->error));
        exit();
    }
    $stmt->close();
} else {
    header("Location: index.php?status=excluido_erro&mensagem=" . urlencode("ID do cliente não fornecido."));
    exit();
}

$conn->close(); 
?>