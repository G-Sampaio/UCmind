<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_paciente = $_GET['id_paciente'] ?? null;
if (!$id_paciente) {
    header('Location: visualizar_pacientes.php');
    exit;
}

$nivel_acesso = $_SESSION['nivel_acesso'];
$id_usuario = $_SESSION['id_usuario'];

if ($nivel_acesso == 'admin') {
    $query_paciente = "SELECT * FROM pacientes WHERE id_paciente = '$id_paciente'";
} elseif ($nivel_acesso == 'professor') {
    $query_paciente = "
        SELECT p.*
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        JOIN turmas t ON u.id_turma = t.id_turma
        WHERE t.id_professor = '$id_usuario' AND p.id_paciente = '$id_paciente'
    ";
} elseif ($nivel_acesso == 'aluno') {
    $query_paciente = "SELECT * FROM pacientes WHERE id_aluno = '$id_usuario' AND id_paciente = '$id_paciente'";
} else {
    header('Location: dashboard.php');
    exit;
}

$result_paciente = $conn->query($query_paciente);
if ($result_paciente->num_rows == 0) {
    header('Location: visualizar_pacientes.php');
    exit;
}

$paciente = $result_paciente->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $data_nascimento = htmlspecialchars($_POST['data_nascimento'], ENT_QUOTES, 'UTF-8');
    $genero = htmlspecialchars($_POST['genero'], ENT_QUOTES, 'UTF-8');
    $endereco = htmlspecialchars($_POST['endereco'], ENT_QUOTES, 'UTF-8');
    $telefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $cidade = htmlspecialchars($_POST['cidade'], ENT_QUOTES, 'UTF-8');
    $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
    $data_inicio = htmlspecialchars($_POST['data_inicio'], ENT_QUOTES, 'UTF-8');
    $contato_emergencia = htmlspecialchars($_POST['contato_emergencia'], ENT_QUOTES, 'UTF-8');
    $escolaridade = htmlspecialchars($_POST['escolaridade'], ENT_QUOTES, 'UTF-8');
    $ocupacao = htmlspecialchars($_POST['ocupacao'], ENT_QUOTES, 'UTF-8');
    $necessidade_especial = htmlspecialchars($_POST['necessidade_especial'], ENT_QUOTES, 'UTF-8');
    $hist_familiar = htmlspecialchars($_POST['hist_familiar'], ENT_QUOTES, 'UTF-8');
    $hist_social = htmlspecialchars($_POST['hist_social'], ENT_QUOTES, 'UTF-8');
    $finais = htmlspecialchars($_POST['finais'], ENT_QUOTES, 'UTF-8');

    $update_query = "
        UPDATE pacientes 
        SET 
            nome = '$nome', data_nascimento = '$data_nascimento', genero = '$genero', 
            endereco = '$endereco', telefone = '$telefone', email = '$email',
            cidade = '$cidade', estado = '$estado', data_inicio = '$data_inicio',
            contato_emergencia = '$contato_emergencia', escolaridade = '$escolaridade', 
            ocupacao = '$ocupacao', necessidade_especial = '$necessidade_especial',
            hist_familiar = '$hist_familiar', hist_social = '$hist_social', finais = '$finais'
        WHERE id_paciente = '$id_paciente'
    ";

    if ($conn->query($update_query)) {
        $_SESSION['mensagem'] = 'Paciente atualizado com sucesso!';
        header('Location: visualizar_pacientes.php');
        exit;
    } else {
        $erro = 'Erro ao atualizar paciente: ' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            max-width: 800px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0056b3;
            border: none;
        }
        .btn-primary:hover {
            background-color: #003f88;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1>Editar Paciente</h1>

        <?php if (isset($erro)) { ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $paciente['nome']; ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?php echo $paciente['data_nascimento']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">Gênero</label>
                    <select id="genero" name="genero" class="form-select" required>
                        <option value="Masculino" <?php echo $paciente['genero'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Feminino" <?php echo $paciente['genero'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                        <option value="Outro" <?php echo $paciente['genero'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="<?php echo $paciente['endereco']; ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo $paciente['telefone']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $paciente['email']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" id="cidade" name="cidade" class="form-control" value="<?php echo $paciente['cidade']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" id="estado" name="estado" class="form-control" value="<?php echo $paciente['estado']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo $paciente['data_inicio']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="contato_emergencia" class="form-label">Contato de Emergência</label>
                <input type="text" id="contato_emergencia" name="contato_emergencia" class="form-control" value="<?php echo $paciente['contato_emergencia']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="escolaridade" class="form-label">Escolaridade</label>
                <input type="text" id="escolaridade" name="escolaridade" class="form-control" value="<?php echo $paciente['escolaridade']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="ocupacao" class="form-label">Ocupação</label>
                <input type="text" id="ocupacao" name="ocupacao" class="form-control" value="<?php echo $paciente['ocupacao']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="necessidade_especial" class="form-label">Necessidade Especial</label>
                <input type="text" id="necessidade_especial" name="necessidade_especial" class="form-control" value="<?php echo $paciente['necessidade_especial']; ?>">
            </div>
            <div class="mb-3">
                <label for="hist_familiar" class="form-label">Histórico Familiar</label>
                <textarea id="hist_familiar" name="hist_familiar" class="form-control" rows="3" required><?php echo $paciente['hist_familiar']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="hist_social" class="form-label">Histórico Social</label>
                <textarea id="hist_social" name="hist_social" class="form-control" rows="3" required><?php echo $paciente['hist_social']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="finais" class="form-label">Finais</label>
                <textarea id="finais" name="finais" class="form-control" rows="3" required><?php echo $paciente['finais']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="visualizar_pacientes.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
