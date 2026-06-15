<?php
/**
 * auth.php — Funções de autenticação simples via sessão PHP.
 *
 * Como funciona:
 *  1) session_start() inicia a sessão (cookie no navegador).
 *  2) loginAdmin($senha) compara com ADMIN_PASSWORD e grava na sessão.
 *  3) isAdmin() devolve true/false para qualquer página decidir o que mostrar.
 *  4) logoutAdmin() limpa a sessão.
 */

require_once __DIR__ . '/config.php';

// Inicia a sessão (se ainda não foi iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Tenta logar o admin.
 * @return bool  true se a senha conferiu, false caso contrário.
 */
function loginAdmin(string $senha): bool {
    if (hash_equals(ADMIN_PASSWORD, $senha)) {
        // Regenera o ID da sessão para evitar session fixation
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        return true;
    }
    return false;
}

/**
 * Retorna true se o usuário atual está logado como admin.
 */
function isAdmin(): bool {
    return !empty($_SESSION['admin']);
}

/**
 * Encerra a sessão de admin.
 */
function logoutAdmin(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

/**
 * Se o usuário não for admin, redireciona para login.
 * Use no topo de páginas/handlers protegidos.
 */
function exigirAdmin(): void {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}
