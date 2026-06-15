<?php
/**
 * upload.php — Recebe imagens enviadas pelo admin e salva em imagens/galeria/.
 *
 * Segurança:
 *  - Só admin (sessão) pode chegar aqui.
 *  - Valida extensão, MIME real (mimeMagic / finfo) e tamanho.
 *  - Gera nome único para evitar sobrescrever.
 */

require_once __DIR__ . '/includes/auth.php';
exigirAdmin(); // se não for admin, manda pra login.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: galeria.php');
    exit;
}

if (empty($_FILES['images']) || !is_array($_FILES['images']['name'])) {
    $_SESSION['msg_erro'] = 'Nenhum arquivo enviado.';
    header('Location: galeria.php');
    exit;
}

$enviados   = 0;
$ignorados  = 0;
$erros      = [];

// finfo detecta o MIME real do arquivo (não confia no que o navegador diz)
$finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;

$qtd = count($_FILES['images']['name']);
for ($i = 0; $i < $qtd; $i++) {
    $nome     = $_FILES['images']['name'][$i];
    $tmpName  = $_FILES['images']['tmp_name'][$i];
    $tamanho  = (int) $_FILES['images']['size'][$i];
    $errCode  = (int) $_FILES['images']['error'][$i];

    // Pula slots vazios (input file que ficou sem nada)
    if ($errCode === UPLOAD_ERR_NO_FILE) continue;

    if ($errCode !== UPLOAD_ERR_OK) {
        $ignorados++;
        $erros[] = "Erro no upload de '$nome' (código $errCode).";
        continue;
    }

    // Tamanho
    if ($tamanho > MAX_FILE_SIZE) {
        $ignorados++;
        $erros[] = "'$nome' excede 10 MB.";
        continue;
    }

    // Extensão
    $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXT, true)) {
        $ignorados++;
        $erros[] = "'$nome' tem extensão inválida.";
        continue;
    }

    // MIME real
    if ($finfo) {
        $mime = finfo_file($finfo, $tmpName);
        if (!in_array($mime, ALLOWED_MIME, true)) {
            $ignorados++;
            $erros[] = "'$nome' não é uma imagem válida.";
            continue;
        }
    }

    // Nome único: timestamp + random + extensão original
    $novoNome = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destino  = UPLOAD_DIR . DIRECTORY_SEPARATOR . $novoNome;

    if (move_uploaded_file($tmpName, $destino)) {
        $enviados++;
    } else {
        $ignorados++;
        $erros[] = "Falha ao salvar '$nome'.";
    }
}

if ($finfo) finfo_close($finfo);

if ($enviados > 0) {
    $_SESSION['msg_sucesso'] = "$enviados imagem(ns) adicionada(s) à galeria.";
}
if (!empty($erros)) {
    $_SESSION['msg_erro'] = implode(' ', $erros);
}

header('Location: galeria.php');
exit;
