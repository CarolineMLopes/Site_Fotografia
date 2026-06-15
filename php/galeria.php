<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle  = 'Galeria · NK Edição e Vídeo – Fotógrafo Mauricio Nadim';
$activePage = 'galeria';
$extraCss   = ['../css/galeria.css'];
$admin      = isAdmin();

// Mensagens flash (sucesso/erro vindas dos handlers de upload/exclusão)
$msgSucesso = $_SESSION['msg_sucesso'] ?? '';
$msgErro    = $_SESSION['msg_erro']    ?? '';
unset($_SESSION['msg_sucesso'], $_SESSION['msg_erro']);

// ── Lê arquivos da pasta da galeria ────────────────────────────
$imagens = [];
if (is_dir(UPLOAD_DIR)) {
    $files = scandir(UPLOAD_DIR);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ALLOWED_EXT, true)) {
            $imagens[] = $f;
        }
    }
    // Mais recentes primeiro (ordem alfabética reversa funciona porque o nome começa com timestamp)
    rsort($imagens);
}

include __DIR__ . '/includes/head.php';
?>
  <body>
    <header>
      <div class="brand-wrapper">
        <div class="brand">
          <div class="brand-mark"></div>
          <span class="brand-name">NK Edição e Vídeo</span>
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
      <div class="header-actions">
        <a href="inicio.php" class="back-link">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          Voltar ao início
        </a>
        <?php if ($admin): ?>
          <span class="admin-header-badge">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Administrador
          </span>
        <?php else: ?>
          <a href="login.php" class="btn-admin">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Admin
          </a>
        <?php endif; ?>
      </div>
    </header>

    <section class="galeria-hero">
      <h1>Galeria de Imagens</h1>
      <p>Portfólio fotográfico de Mauricio Nadim.</p>
    </section>

    <!-- Painel admin: SÓ aparece se o PHP confirmar que está logado -->
    <?php if ($admin): ?>
      <div class="admin-panel">
        <div class="admin-panel-inner">
          <span class="admin-badge">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path></svg>
            Administrador
          </span>

          <form action="upload.php" method="post" enctype="multipart/form-data" class="admin-form-upload">
            <label class="upload-label" for="file-input">
              <svg viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
              </svg>
              Adicionar imagens
            </label>
            <input type="file" id="file-input" name="images[]" accept=".jpg,.jpeg,.png" multiple onchange="this.form.submit()" />
          </form>

          <a href="logout.php" class="btn-logout">Sair</a>
        </div>

        <?php if ($msgSucesso): ?>
          <p class="upload-status sucesso"><?php echo htmlspecialchars($msgSucesso); ?></p>
        <?php endif; ?>
        <?php if ($msgErro): ?>
          <p class="upload-status erro"><?php echo htmlspecialchars($msgErro); ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <main class="galeria-wrapper">
      <?php if (empty($imagens)): ?>
        <div class="empty-state">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
          <p>Nenhuma imagem na galeria ainda.</p>
        </div>
      <?php else: ?>
        <div class="galeria-grid">
          <?php foreach ($imagens as $img): ?>
            <?php
              $url = UPLOAD_URL_PATH . '/' . rawurlencode($img);
              $alt = pathinfo($img, PATHINFO_FILENAME);
            ?>
            <div class="galeria-item" data-url="<?php echo htmlspecialchars($url); ?>" data-alt="<?php echo htmlspecialchars($alt); ?>">
              <img
                src="<?php echo htmlspecialchars($url); ?>"
                alt="<?php echo htmlspecialchars($alt); ?>"
                loading="lazy"
                draggable="false"
                oncontextmenu="return false;"
                ondragstart="return false;"
              />
              <!-- Marca d'água visível em cima de cada foto -->
              <div class="watermark" aria-hidden="true">
                <img src="../imagens/assinatura1.png" alt="" draggable="false" />
              </div>

              <?php if ($admin): ?>
                <!-- Botão de remover SÓ aparece para admin (renderização server-side) -->
                <form action="excluir.php" method="post" class="form-excluir" onsubmit="return confirm('Remover esta imagem permanentemente?');">
                  <input type="hidden" name="arquivo" value="<?php echo htmlspecialchars($img); ?>" />
                  <button type="submit" class="btn-deletar" aria-label="Remover imagem">
                    <svg viewBox="0 0 24 24">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6l-1 14H6L5 6"></path>
                      <path d="M10 11v6"></path>
                      <path d="M14 11v6"></path>
                      <path d="M9 6V4h6v2"></path>
                    </svg>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>

    <!-- Lightbox simples (clique abre, ESC ou clique fora fecha) -->
    <div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Visualizar imagem">
      <button class="lightbox-close" id="lightbox-close" aria-label="Fechar">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
      <div class="lightbox-content">
        <img class="lightbox-img" id="lightbox-img" src="" alt="" />
        <!-- Marca d'água também no lightbox -->
        <div class="watermark watermark-lightbox" aria-hidden="true">
          <img src="../imagens/assinatura1.png" alt="" draggable="false" />
        </div>
      </div>
    </div>

    <footer>
      &copy; <?php echo date('Y'); ?> NK Edição e Vídeo · Fotógrafo Mauricio Nadim. Todos os direitos reservados.
    </footer>

    <script>
      // ── Lightbox ───────────────────────────────────────────────
      var lightbox      = document.getElementById('lightbox');
      var lightboxImg   = document.getElementById('lightbox-img');
      var lightboxClose = document.getElementById('lightbox-close');

      function abrirLightbox(url, alt) {
        lightboxImg.src = url;
        lightboxImg.alt = alt || '';
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
      function fecharLightbox() {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
        setTimeout(function(){ lightboxImg.src = ''; }, 200);
      }
      lightboxClose.addEventListener('click', fecharLightbox);
      lightbox.addEventListener('click', function(e){
        if (e.target === lightbox) fecharLightbox();
      });
      document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') fecharLightbox();
      });

      // Clique em cada card abre o lightbox (exceto se clicou em botão de excluir)
      document.querySelectorAll('.galeria-item').forEach(function(item){
        item.addEventListener('click', function(e){
          if (e.target.closest('.btn-deletar') || e.target.closest('.form-excluir')) return;
          abrirLightbox(item.dataset.url, item.dataset.alt);
        });
      });

      // ── Bloqueio anti-download (camada 1: JS) ──────────────────
      document.addEventListener('contextmenu', function(e){
        if (e.target.tagName === 'IMG') e.preventDefault();
      });
      document.addEventListener('dragstart', function(e){
        if (e.target.tagName === 'IMG') e.preventDefault();
      });
      // Atalhos comuns de "salvar"
      document.addEventListener('keydown', function(e){
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'u')) {
          e.preventDefault();
        }
      });
    </script>
  </body>
</html>
