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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Turmas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            padding: 20px;
            color: #444;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #5cb85c;
            color: white;
            font-weight: 700;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        table tr:last-child td {
            border-bottom: none;
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
</body>
</html>
