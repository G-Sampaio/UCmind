<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel_acesso'] != 'professor') {
    header('Location: index.php');
    exit;
}

// Obter o ID da turma
$id_turma = $_GET['id_turma'] ?? null;

if (!$id_turma) {
    echo "Turma inválida.";
    exit;
}

// Verificar se a turma pertence ao professor logado
$id_professor = $_SESSION['id_usuario'];
$query_verificar_turma = "SELECT id_turma FROM turmas WHERE id_turma = '$id_turma' AND id_professor = '$id_professor'";
$result_verificar = $conn->query($query_verificar_turma);

if ($result_verificar->num_rows == 0) {
    echo "Acesso negado.";
    exit;
}

// Obter os alunos da turma
$query_alunos = "SELECT id_usuario, nome, email FROM usuarios WHERE nivel_acesso = 'aluno' AND id_turma = '$id_turma'";
$result_alunos = $conn->query($query_alunos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos da Turma</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary">Alunos da Turma</h1>

        <?php if ($result_alunos->num_rows > 0) { ?>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($aluno = $result_alunos->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $aluno['id_usuario']; ?></td>
                                <td><?php echo $aluno['nome']; ?></td>
                                <td><?php echo $aluno['email']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning text-center mt-4" role="alert">
                Nenhum aluno encontrado para esta turma.
            </div>
        <?php } ?>

        <div class="d-flex justify-content-between mt-4">
            <button onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <a href="dashboard.php" class="btn btn-primary">Voltar para o Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
