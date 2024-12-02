<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : null;

if (!$id_paciente) {
    echo "Paciente não encontrado.";
    exit;
}

// Buscar detalhes do paciente
$query_paciente = "
    SELECT * 
    FROM pacientes 
    WHERE id_paciente = '$id_paciente'
";
$result_paciente = $conn->query($query_paciente);

if ($result_paciente->num_rows > 0) {
    $paciente = $result_paciente->fetch_assoc();
} else {
    echo "Paciente não encontrado.";
    exit;
}

// Buscar consultas associadas ao paciente
$query_consultas = "
    SELECT * 
    FROM consultas 
    WHERE id_paciente = '$id_paciente'
";
$result_consultas = $conn->query($query_consultas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Paciente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        h1, h2 {
            color: #0056b3;
        }
        .details p {
            margin: 8px 0;
        }
        .details strong {
            font-weight: bold;
        }
        .btn {
            margin: 10px 5px;
        }
        table {
            margin-top: 20px;
        }
        table th {
            background-color: #0056b3;
            color: white;
        }
        table td, table th {
            border: 1px solid #dee2e6;
        }
        @media print {
            .btn {
                display: none;
            }
            .container {
                box-shadow: none;
                border: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Detalhes do Paciente</h1>
        <div class="details">
            <p><strong>Nome:</strong> <?php echo $paciente['nome']; ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo $paciente['data_nascimento']; ?></p>
            <p><strong>Gênero:</strong> <?php echo $paciente['genero']; ?></p>
            <p><strong>Endereço:</strong> <?php echo $paciente['endereco']; ?></p>
            <p><strong>Telefone:</strong> <?php echo $paciente['telefone']; ?></p>
            <p><strong>E-mail:</strong> <?php echo $paciente['email']; ?></p>
            <p><strong>Cidade:</strong> <?php echo $paciente['cidade']; ?></p>
            <p><strong>Estado:</strong> <?php echo $paciente['estado']; ?></p>
            <p><strong>Data de Início:</strong> <?php echo $paciente['data_inicio']; ?></p>
            <p><strong>Contato de Emergência:</strong> <?php echo $paciente['contato_emergencia']; ?></p>
            <p><strong>Escolaridade:</strong> <?php echo $paciente['escolaridade']; ?></p>
            <p><strong>Ocupação:</strong> <?php echo $paciente['ocupacao']; ?></p>
            <p><strong>Necessidades Especiais:</strong> <?php echo $paciente['necessidade_especial']; ?></p>
            <p><strong>Histórico Familiar:</strong> <?php echo $paciente['hist_familiar']; ?></p>
            <p><strong>Histórico Social:</strong> <?php echo $paciente['hist_social']; ?></p>
            <p><strong>Observações Finais:</strong> <?php echo $paciente['finais']; ?></p>
        </div>

        <h2>Consultas</h2>
        <?php if ($result_consultas->num_rows > 0) { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Data e Hora</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($consulta = $result_consultas->fetch_assoc()) { ?>
                        <tr>
                            <td class="text-center"><?php echo $consulta['id_consulta']; ?></td>
                            <td><?php echo $consulta['data_consulta']; ?></td>
                            <td><?php echo $consulta['descricao']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="text-center">Nenhuma consulta encontrada para este paciente.</p>
        <?php } ?>

        <div class="text-center">
            <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
            <a href="visualizar_consultas.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

