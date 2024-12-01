<?php
include 'includes/db.php';

if (!isset($_GET['id_turma'])) {
    echo json_encode([]);
    exit;
}

$id_turma = $_GET['id_turma'];

// Buscar o professor associado à turma
$query = "
    SELECT u.id_usuario, u.nome
    FROM usuarios u
    JOIN turmas t ON t.id_professor = u.id_usuario
    WHERE t.id_turma = '$id_turma'
";
$result = $conn->query($query);

$professor = [];
if ($result && $result->num_rows > 0) {
    $professor = $result->fetch_assoc();
}

echo json_encode([$professor]);
?>
