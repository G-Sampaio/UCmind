<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['nivel_acesso'] == 'admin') {
    $nome_turma = $_POST['nome_turma'];
    $id_professor = $_POST['id_professor'];
    
    // Criar nova turma
    $query = "INSERT INTO turmas (nome, id_professor, data_criacao) VALUES ('$nome_turma', '$id_professor', NOW())";
    $conn->query($query);
}

// Listar turmas
if ($_SESSION['nivel_acesso'] == 'admin') {
    $turmas = $conn->query("SELECT * FROM turmas");
} else {
    // Professor só pode ver suas turmas
    $id_professor = $_SESSION['id_usuario'];
    $turmas = $conn->query("SELECT * FROM turmas WHERE id_professor = $id_professor");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Turmas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Gestão de Turmas</h1>
    
    <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
    <!-- Formulário para criar novas turmas -->
    <form method="POST">
        <input type="text" name="nome_turma" placeholder="Nome da Turma" required>
        <select name="id_professor" required>
            <?php
            // Listar todos os professores
            $professores = $conn->query("SELECT id_usuario, nome FROM usuarios WHERE nivel_acesso = 'professor'");
            while ($professor = $professores->fetch_assoc()) {
                echo "<option value='" . $professor['id_usuario'] . "'>" . $professor['nome'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Criar Turma</button>
    </form>
    <?php } ?>

    <!-- Listar as turmas -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome da Turma</th>
                <th>Professor</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($turma = $turmas->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $turma['id_turma']; ?></td>
                <td><?php echo $turma['nome']; ?></td>
                <td><?php 
                    $professor = $conn->query("SELECT nome FROM usuarios WHERE id_usuario = " . $turma['id_professor']);
                    echo $professor->fetch_assoc()['nome']; 
                ?></td>
                <td><?php echo $turma['data_criacao']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>