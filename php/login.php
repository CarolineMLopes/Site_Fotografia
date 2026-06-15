<?php
require_once __DIR__ . '/includes/auth.php';

// Se já estiver logado, vai direto para a galeria.
if (isAdmin()) {
    header('Location: galeria.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['password'] ?? '';
    if (loginAdmin($senha)) {
        header('Location: galeria.php');
        exit;
    } else {
        $erro = 'Senha incorreta.';
    }
}

$pageTitle = 'Login do Administrador – NK Edição e Vídeo';
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="../css/vars.css" />
    <style>
      * { box-sizing: border-box; margin: 0; padding: 0; }
      body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        min-height: 100vh;
        color: #f5f5f5;
        background:
          radial-gradient(circle at top left, #ffffff15 0, transparent 45%),
          radial-gradient(circle at bottom right, #ffffff10 0, transparent 50%),
          linear-gradient(135deg, #1b1b1f 0%, #101015 40%, #232329 100%);
        display: grid;
        place-items: center;
        padding: 24px;
      }
      .login-card {
        width: 100%;
        max-width: 420px;
        background: #0e0e14;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.6);
      }
      .login-card h1 { font-size: 1.35rem; margin-bottom: 8px; }
      .login-card .sub { color: rgba(245, 245, 245, 0.55); margin-bottom: 20px; font-size: 0.92rem; }
      .field { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
      label { font-size: 0.83rem; letter-spacing: 0.05em; color: rgba(245, 245, 245, 0.65); }
      input[type="password"] {
        padding: 10px 14px;
        border: 1.5px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        color: #f5f5f5;
        font-size: 0.95rem;
        outline: none;
      }
      input[type="password"]:focus { border-color: rgba(245, 245, 245, 0.45); }
      .error { color: #e36d6d; font-size: 0.85rem; margin-bottom: 12px; }
      .actions { display: grid; gap: 10px; }
      button {
        width: 100%;
        padding: 11px;
        border: none;
        border-radius: 8px;
        background: #f5f5f5;
        color: #050509;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
      }
      .back-link {
        text-align: center;
        text-decoration: none;
        color: rgba(245, 245, 245, 0.7);
        font-size: 0.9rem;
      }
      .back-link:hover { color: #f5f5f5; }
    </style>
  </head>
  <body>
    <main class="login-card">
      <h1>Área do Administrador</h1>
      <p class="sub">Digite a senha para gerenciar a galeria.</p>

      <form method="post" autocomplete="off">
        <div class="field">
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" required autofocus />
        </div>

        <?php if ($erro): ?>
          <p class="error"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <div class="actions">
          <button type="submit">Entrar</button>
          <a href="inicio.php" class="back-link">Voltar para o início</a>
        </div>
      </form>
    </main>
  </body>
</html>
