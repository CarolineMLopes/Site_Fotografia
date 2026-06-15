<?php
/**
 * excluir.php — Remove uma imagem da pasta da galeria.
 *
 * Segurança:
 *  - Só admin chega aqui.
 *  - Sanitiza o nome (basename) para evitar path traversal (../../).
 *  - Verifica se o arquivo realmente está dentro de UPLOAD_DIR antes de remover.
 */

require_once __DIR__ . '/includes/auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: galeria.php');
    exit;
}

$arquivo = $_POST['arquivo'] ?? '';
$arquivo = basename($arquivo); // sanitiza: corta qualquer caminho

if ($arquivo === '' || $arquivo === '.' || $arquivo === '..') {
    $_SESSION['msg_erro'] = 'Arquivo inválido.';
    header('Location: galeria.php');
    exit;
}

// Só permite remover se for uma extensão de imagem conhecida
$ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
if (!in_array($ext, ALLOWED_EXT, true)) {
    $_SESSION['msg_erro'] = 'Tipo de arquivo não permitido.';
    header('Location: galeria.php');
    exit;
}

$caminho = UPLOAD_DIR . DIRECTORY_SEPARATOR . $arquivo;

// Garante que o realpath ainda aponta para dentro da pasta da galeria
$real = realpath($caminho);
$base = realpath(UPLOAD_DIR);
if ($real === false || $base === false || strpos($real, $base) !== 0) {
    $_SESSION['msg_erro'] = 'Arquivo não encontrado.';
    header('Location: galeria.php');
    exit;
}

if (unlink($real)) {
    $_SESSION['msg_sucesso'] = 'Imagem removida com sucesso.';
} else {
    $_SESSION['msg_erro'] = 'Não foi possível remover o arquivo.';
}

header('Location: galeria.php');
exit;
