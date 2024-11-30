<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor' && $_SESSION['nivel_acesso'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Adicionar consulta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $data_hora = $_POST['data_hora'];
    $descricao = $_POST['descricao'];
    $observacoes = $_POST['observacoes'];

    $query = "
        INSERT INTO consultas (id_paciente, data_consulta, descricao, observacoes) 
        VALUES ('$id_paciente', '$data_hora', '$descricao', '$observacoes')
    ";

    if ($conn->query($query)) {
        echo "<p>Consulta cadastrada com sucesso!</p>";
    } else {
        echo "<p>Erro ao cadastrar consulta: " . $conn->error . "</p>";
    }
}

// Carregar pacientes para o formulário
if ($_SESSION['nivel_acesso'] == 'admin') {
    $pacientes = $conn->query("
        SELECT p.id_paciente, p.nome 
        FROM pacientes p
    ");
} elseif ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $pacientes = $conn->query("
        SELECT p.id_paciente, p.nome 
        FROM pacientes p
        WHERE p.id_professor = '$id_professor'
    ");
} else {
    $id_aluno = $_SESSION['id_usuario'];
    $pacientes = $conn->query("
        SELECT p.id_paciente, p.nome 
        FROM pacientes p
        WHERE p.id_aluno = '$id_aluno'
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Consulta</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Cadastrar Consulta</h1>
        </header>
        <main>
            <form method="POST" class="form-consulta">
                <div class="form-group">
                    <label for="id_paciente">Selecione o Paciente:</label>
                    <select name="id_paciente" id="id_paciente" required>
                        <option value="">-- Escolha um Paciente --</option>
                        <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                            <option value="<?php echo $paciente['id_paciente']; ?>"><?php echo $paciente['nome']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="data_hora">Data e Hora:</label>
                    <input type="datetime-local" name="data_hora" id="data_hora" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" name="descricao" id="descricao" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" id="observacoes"></textarea>
                </div>

                <button type="submit" class="btn-submit">Cadastrar Consulta</button>
            </form>
        </main>
    </div>
</body>
</html>

