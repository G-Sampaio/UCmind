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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            padding: 20px;
            color: #0056b3;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #0056b3;
            color: white;
            font-weight: 700;
            text-align: center;
        }

        table td {
            text-align: center;
        }

        table tr:hover {
            background-color: #e9f5ff;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        .btn-back {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #0056b3;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            width: fit-content;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #003f88;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            table th,
            table td {
                padding: 10px;
            }
        }
    </style>
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

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</body>
</html>
