<?php
/**
 * config.php — Configurações centrais do projeto
 *
 * Aqui ficam as constantes que você muda quando quiser:
 *  - Senha do admin
 *  - Pasta onde as imagens da galeria são salvas
 *  - Tipos/limites permitidos no upload
 */

// ── SENHA DO ADMIN ─────────────────────────────────────────────
// IMPORTANTE: troque para uma senha forte antes de hospedar online!
define('ADMIN_PASSWORD', 'nkadmin2024');

// ── Pasta onde as imagens enviadas pelo admin ficam salvas ─────
// Caminho absoluto (no servidor) e caminho relativo (para o navegador)
define('UPLOAD_DIR',      __DIR__ . '/../../imagens/galeria');
define('UPLOAD_URL_PATH', '../imagens/galeria');

// ── Limites do upload ──────────────────────────────────────────
define('MAX_FILE_SIZE',  10 * 1024 * 1024);  // 10 MB por arquivo
define('ALLOWED_EXT',    ['jpg', 'jpeg', 'png']);
define('ALLOWED_MIME',   ['image/jpeg', 'image/png']);

// ── Garante que a pasta de upload existe ───────────────────────
if (!is_dir(UPLOAD_DIR)) {
    @mkdir(UPLOAD_DIR, 0755, true);
}
