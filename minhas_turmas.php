<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] == 'admin') {
    // O Admin vê todas as turmas, com informações do professor
    $query = "SELECT t.id_turma, t.nome, u.nome AS professor, t.data_criacao
              FROM turmas t
              JOIN usuarios u ON t.id_professor = u.id_usuario";
} else {
    // O Professor vê apenas suas turmas
    $id_professor = $_SESSION['id_usuario'];
    $query = "SELECT t.id_turma, t.nome, t.data_criacao
              FROM turmas t
              WHERE t.id_professor = '$id_professor'";
}

$turmas = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Turmas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Minhas Turmas</h1>
    <table>
        <thead>
            <tr>
                <th>Nome da Turma</th>
                <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                    <th>Professor</th>
                <?php } ?>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($turma = $turmas->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $turma['nome']; ?></td>
                    <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                        <td><?php echo $turma['professor']; ?></td>
                    <?php } ?>
                    <td><?php echo $turma['data_criacao']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
