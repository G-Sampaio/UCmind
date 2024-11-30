<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turma = $_POST['id_turma'];
    $id_professor = $_POST['id_professor'];

    $query = "UPDATE turmas SET id_professor = '$id_professor' WHERE id_turma = '$id_turma'";
    if ($conn->query($query)) {
        echo "Professor alterado com sucesso!";
    } else {
        echo "Erro ao alterar professor: " . $conn->error;
    }
}

$turmas = $conn->query("SELECT * FROM turmas");
$professores = $conn->query("SELECT * FROM usuarios WHERE nivel_acesso = 'professor'");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Professor da Turma</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Alterar Professor de uma Turma</h1>
    <form method="POST">
        <select name="id_turma" required>
            <option value="">Selecione a Turma</option>
            <?php while ($turma = $turmas->fetch_assoc()) { ?>
                <option value="<?php echo $turma['id_turma']; ?>"><?php echo $turma['nome']; ?></option>
            <?php } ?>
        </select>

        <select name="id_professor" required>
            <option value="">Selecione o Novo Professor</option>
            <?php while ($professor = $professores->fetch_assoc()) { ?>
                <option value="<?php echo $professor['id_usuario']; ?>"><?php echo $professor['nome']; ?></option>
            <?php } ?>
        </select>
        
        <button type="submit">Alterar Professor</button>
    </form>
</body>
</html>
