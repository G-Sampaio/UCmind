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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Turmas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary">Minhas Turmas</h1>
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Nome da Turma</th>
                        <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                            <th scope="col">Professor</th>
                        <?php } ?>
                        <th scope="col">Data de Criação</th>
                        <?php if ($_SESSION['nivel_acesso'] != 'admin') { ?>
                            <th scope="col">Ações</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($turma = $turmas->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $turma['nome']; ?></td>
                            <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                                <td><?php echo $turma['professor']; ?></td>
                            <?php } ?>
                            <td><?php echo date("d/m/Y", strtotime($turma['data_criacao'])); ?></td>
                            <?php if ($_SESSION['nivel_acesso'] != 'admin') { ?>
                                <td>
                                    <a href="alunos_da_turma.php?id_turma=<?php echo $turma['id_turma']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                       Ver Alunos
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between">
            <button onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <a href="dashboard.php" class="btn btn-primary">Voltar para o Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
