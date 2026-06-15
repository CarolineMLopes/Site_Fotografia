<?php
/**
 * header.php — Cabeçalho principal do site (com logo + assinatura).
 *
 * Variáveis esperadas (opcionais):
 *   $activePage -> 'inicio' | 'galeria'   (para destacar item ativo)
 *   $admin      -> bool                   (mostrar badge "Admin ativo")
 */
$activePage = $activePage ?? '';
$admin      = $admin      ?? false;
?>
<header>
  <div class="brand-wrapper">
    <div class="brand">
      <div class="brand-mark"></div>
      <span class="brand-name">NK Edição e Vídeo – Fotógrafo Mauricio Nadim</span>
    </div>
    <img
      src="../imagens/assinatura_png2.png"
      alt="Assinatura Mauricio Nadim"
      class="brand-signature"
      draggable="false"
      oncontextmenu="return false;"
      ondragstart="return false;"
    />
  </div>

  <nav>
    <ul class="navegacao-lista">
      <li><a href="inicio.php#hero" class="navegacao1 <?php echo $activePage === 'inicio' ? 'ativo' : ''; ?>">Início</a></li>
      <li><a href="inicio.php#servicos">Serviços</a></li>
      <li><a href="inicio.php#portfolio">Portfólio</a></li>
      <li><a href="galeria.php" class="<?php echo $activePage === 'galeria' ? 'ativo' : ''; ?>">Galeria</a></li>
      <li><a href="inicio.php#sobre">Sobre</a></li>
      <li><a href="inicio.php#contato">Contato</a></li>

      <?php if ($admin): ?>
        <!-- Logado: badge dourado clicável (vai pra galeria gerenciar) + link Sair discreto -->
        <li>
          <a href="galeria.php" class="admin-header-badge admin-header-badge-link" title="Gerenciar galeria">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Administrador
          </a>
        </li>
        <li>
          <a href="logout.php" class="btn-sair-nav" title="Encerrar sessão de admin">Sair</a>
        </li>
      <?php else: ?>
        <!-- Não logado: link de acesso ao painel admin -->
        <li>
          <a href="login.php" class="btn-admin" title="Área do administrador">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Admin
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
