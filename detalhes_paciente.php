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
    <title>Detalhes do Paciente</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .details p {
            margin: 8px 0;
        }
        .details strong {
            font-weight: bold;
        }
        .print-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-btn:hover {
            background: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalhes do Paciente</h1>
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
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data e Hora</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($consulta = $result_consultas->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $consulta['id_consulta']; ?></td>
                        <td><?php echo $consulta['data_consulta']; ?></td>
                        <td><?php echo $consulta['descricao']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Nenhuma consulta encontrada para este paciente.</p>
        <?php } ?>

        <button class="print-btn" onclick="window.print()">Imprimir</button>
        <a href="visualizar_pacientes.php" class="print-btn">Voltar</a>
    </div>
</body>
</html>
