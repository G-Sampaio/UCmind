<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor' && $_SESSION['nivel_acesso'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Listar pacientes com base no nível de acesso
if ($_SESSION['nivel_acesso'] == 'aluno') {
    $id_aluno = $_SESSION['id_usuario'];
    $query_pacientes = "SELECT * FROM pacientes WHERE id_aluno = '$id_aluno'";
} elseif ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, t.nome AS turma_nome
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        JOIN turmas t ON u.id_turma = t.id_turma
        WHERE t.id_professor = '$id_professor'
    ";
} else { // Admin
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, pr.nome AS professor_responsavel, t.nome AS turma_nome
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        LEFT JOIN turmas t ON u.id_turma = t.id_turma
        LEFT JOIN usuarios pr ON t.id_professor = pr.id_usuario
    ";
}
$query_turmas = "SELECT id_turma, nome FROM turmas";
$result_turmas = $conn->query($query_turmas);

$pacientes = $conn->query($query_pacientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pacientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        h1 {
            margin: 20px 0;
            color: #0056b3;
        }
        .table thead {
            background-color: #0056b3;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #e9f5ff;
        }
        .btn-dashboard {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .btn-dashboard:hover {
            background-color: #003f88;
            text-decoration: none;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        .filter-container input,
        .filter-container select {
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .filter-container {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <h1 class="text-center">Visualizar Pacientes</h1>
        
        <!-- Filtros -->
        <div class="filter-container d-flex justify-content-between align-items-center flex-wrap">
            <input type="text" id="search-name" class="form-control" placeholder="Buscar por Nome" style="max-width: 300px;">
            <input type="text" id="search-city" class="form-control" placeholder="Buscar por Cidade" style="max-width: 300px;">
            <?php if ($_SESSION['nivel_acesso'] != 'aluno') { ?>
                <select id="search-turma" class="form-select" style="max-width: 300px;">
                    <option value="">Filtrar por Turma</option>
                    <?php while ($turma = $result_turmas->fetch_assoc()) { ?>
                        <option value="<?php echo $turma['nome']; ?>"><?php echo $turma['nome']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            <button class="btn btn-primary" onclick="applyFilters()">Filtrar</button>
        </div>

        <!-- Tabela de Pacientes -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle" id="patients-table">
                <thead class="text-center">
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Gênero</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <?php if ($_SESSION['nivel_acesso'] != 'aluno') { ?>
                            <th>Aluno Responsável</th>
                            <th>Turma</th>
                        <?php } ?>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $paciente['nome']; ?></td>
                        <td><?php echo $paciente['data_nascimento']; ?></td>
                        <td><?php echo $paciente['genero']; ?></td>
                        <td><?php echo $paciente['cidade']; ?></td>
                        <td><?php echo $paciente['estado']; ?></td>
                        <?php if ($_SESSION['nivel_acesso'] != 'aluno') { ?>
                            <td><?php echo $paciente['aluno_responsavel']; ?></td>
                            <td><?php echo $paciente['turma_nome'] ?? 'Sem Turma'; ?></td>
                        <?php } ?>
                        <td>
                            <a href="editar_paciente.php?id_paciente=<?php echo $paciente['id_paciente']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <a href="dashboard.php" class="btn-dashboard">Voltar para o Dashboard</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function applyFilters() {
            const name = document.getElementById('search-name').value.toLowerCase();
            const city = document.getElementById('search-city').value.toLowerCase();
            const turma = document.getElementById('search-turma') ? document.getElementById('search-turma').value.toLowerCase() : '';
            const rows = document.querySelectorAll('#patients-table tbody tr');
            
            rows.forEach(row => {
                const rowName = row.children[0].textContent.toLowerCase();
                const rowCity = row.children[3].textContent.toLowerCase();
                const rowTurma = row.children[6] ? row.children[6].textContent.toLowerCase() : '';
                const matches = 
                    (!name || rowName.includes(name)) &&
                    (!city || rowCity.includes(city)) &&
                    (!turma || rowTurma.includes(turma));
                row.style.display = matches ? '' : 'none';
            });
        }
    </script>
</body>
</html>
