<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle  = 'NK Edição e Vídeo – Fotógrafo Mauricio Nadim';
$activePage = 'inicio';
$extraCss   = ['../css/inicio.css'];
$admin      = isAdmin();

// Mensagens flash do form de contato (vem de contato.php)
$contatoSucesso = $_SESSION['contato_sucesso'] ?? '';
$contatoErro    = $_SESSION['contato_erro']    ?? '';
$contatoDados   = $_SESSION['contato_dados']   ?? [];
unset($_SESSION['contato_sucesso'], $_SESSION['contato_erro'], $_SESSION['contato_dados']);

include __DIR__ . '/includes/head.php';
?>
  <body class="inicio-body">
    <div class="page-wrapper">
      <?php include __DIR__ . '/includes/header.php'; ?>

      <main>
        <!-- HERO -->
        <section class="hero" id="hero">
          <div class="hero-inner">
            <div class="hero-copy">
              <p class="hero-eyebrow">Fotografia &amp; Produção Audiovisual</p>
              <h1>NK Edição e Vídeo – Fotógrafo Mauricio Nadim</h1>
              <p>
                Produzimos fotografia profissional e vídeos autorais para marcas,
                eventos e projetos que precisam comunicar com impacto e sofisticação.
              </p>
              <div class="hero-actions">
                <a href="#portfolio" class="hero-button">Ver portfólio</a>
                <span class="hero-secondary">
                  Disponível para eventos, campanhas e ensaios personalizados.
                </span>
              </div>
            </div>
            <div class="hero-visual">
              <img
                src="../imagens/ensaio1.jpeg"
                alt="Ensaio fotográfico Mauricio Nadim"
                draggable="false"
                oncontextmenu="return false;"
                ondragstart="return false;"
              />
            </div>
          </div>
        </section>

        <!-- SERVIÇOS -->
        <section class="services" id="servicos">
          <div class="section-header">
            <p class="section-eyebrow">Serviços</p>
            <h2>Do primeiro clique ao corte final.</h2>
            <p class="section-description">
              Cuidamos de todo o processo criativo para que você tenha imagens consistentes
              com o posicionamento da sua marca.
            </p>
          </div>
          <div class="services-grid">
            <?php
              // Lista de serviços — fácil de editar/adicionar
              $servicos = [
                [
                  'titulo'    => 'Fotografia de Eventos',
                  'descricao' => 'Cobertura completa de eventos corporativos, sociais e culturais, com foco em registros espontâneos e memoráveis.',
                  'itens'     => [
                    'Eventos corporativos e lançamentos',
                    'Congressos, palestras e workshops',
                    'Casamentos e eventos sociais',
                  ],
                ],
                [
                  'titulo'    => 'Edição de Vídeo',
                  'descricao' => 'Montagem, correção de cor e finalização de vídeos para campanhas, reels, institucionais e conteúdo digital.',
                  'itens'     => [
                    'Vídeos institucionais e comerciais',
                    'Conteúdo para redes sociais',
                    'Motion básico e sound design',
                  ],
                ],
                [
                  'titulo'    => 'Ensaios Externos',
                  'descricao' => 'Ensaios autorais em locações externas para marcas, profissionais e projetos pessoais que exigem autenticidade.',
                  'itens'     => [
                    'Retratos profissionais e pessoais',
                    'Campanhas de moda e lifestyle',
                    'Produção e direção de cena',
                  ],
                ],
              ];
              foreach ($servicos as $s):
            ?>
              <article class="service-card">
                <h3><?php echo htmlspecialchars($s['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($s['descricao']); ?></p>
                <ul>
                  <?php foreach ($s['itens'] as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                  <?php endforeach; ?>
                </ul>
              </article>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- PORTFÓLIO PRÉVIA -->
        <section class="portfolio" id="portfolio">
          <div class="section-header">
            <p class="section-eyebrow">Portfólio</p>
            <h2>Momentos que contam histórias.</h2>
            <p class="section-description">
              Uma prévia de projetos recentes em eventos, campanhas e ensaios.
              Para ver tudo, abra a galeria completa.
            </p>
          </div>

          <div class="masonry-grid">
            <?php
              // Prévia de fotos no inicio (a galeria completa fica em galeria.php)
              $portfolio = [
                ['src' => '../imagens/casamento1.jpeg', 'alt' => 'Casamento',         'classe' => 'masonry-item-lg'],
                ['src' => '../imagens/ensaio1.jpeg',    'alt' => 'Ensaio',            'classe' => ''],
                ['src' => '../imagens/gestante1.jpeg',  'alt' => 'Ensaio gestante',   'classe' => 'masonry-item-tall'],
                ['src' => '../imagens/15anos1.jpeg',    'alt' => '15 anos',           'classe' => ''],
                ['src' => '../imagens/batizado1.jpeg',  'alt' => 'Batizado',          'classe' => ''],
                ['src' => '../imagens/produtos1.jpeg',  'alt' => 'Produtos',          'classe' => 'masonry-item-wide'],
              ];
              foreach ($portfolio as $img):
            ?>
              <figure class="masonry-item <?php echo $img['classe']; ?>">
                <img
                  src="<?php echo htmlspecialchars($img['src']); ?>"
                  alt="<?php echo htmlspecialchars($img['alt']); ?>"
                  draggable="false"
                  oncontextmenu="return false;"
                  ondragstart="return false;"
                />
              </figure>
            <?php endforeach; ?>
          </div>

          <div class="portfolio-cta">
            <a href="galeria.php" class="hero-button">Ver mais fotos</a>
          </div>
        </section>

        <!-- SOBRE -->
        <section class="about" id="sobre">
          <div class="section-header">
            <p class="section-eyebrow">Sobre</p>
            <h2>Olhar autoral aliado à estratégia.</h2>
          </div>

          <div class="about-inner about-with-photo">
            <div class="about-photo">
              <?php
                // Foto do Mauricio: tenta mauricio.jpg primeiro;
                // se ainda não existir, cai pra foto provisória.
                // Quando salvar a foto definitiva em imagens/mauricio.jpg,
                // ela aparece automaticamente — sem mexer no código.
                $fotoMauricio = file_exists(__DIR__ . '/../imagens/mauricio.jpg')
                    ? '../imagens/mauricio.jpg'
                    : '../imagens/ensaio2.jpeg';
              ?>
              <img
                src="<?php echo htmlspecialchars($fotoMauricio); ?>"
                alt="Mauricio Nadim - Fotógrafo"
                draggable="false"
                oncontextmenu="return false;"
                ondragstart="return false;"
              />
            </div>
            <div class="about-text">
              <p>
                NK Edição e Digitalização de Imagem LTDA é um estúdio especializado em fotografia e
                produção audiovisual para marcas que entendem o poder das boas imagens. Atuamos em
                projetos de diferentes portes, sempre com foco em planejamento visual,
                narrativa e consistência estética.
              </p>
              <p>
                Com experiência em campanhas digitais, eventos e projetos autorais,
                construímos cada trabalho em parceria com o cliente — da concepção ao
                material final — para que cada entrega seja única, relevante e memorável.
              </p>
            </div>
          </div>
        </section>

        <!-- CONTATO -->
        <section class="contact" id="contato">
          <div class="contact-inner">
            <div class="contact-text">
              <p class="section-eyebrow">Contato</p>
              <h2>Vamos tirar seu próximo projeto do papel.</h2>
              <p>
                Envie uma mensagem contando sobre o que você precisa. Retornaremos com uma
                proposta personalizada para o seu evento, campanha ou ensaio.
              </p>

              <div class="contact-social">
                <p class="contact-social-label">Redes sociais &amp; contato</p>
                <div class="social-icons">
                  <a href="https://www.instagram.com/mauricionadim"
                     target="_blank" rel="noopener"
                     aria-label="Instagram @fotografomauricionadim"
                     title="Instagram — @fotografomauricionadim">
                    <i class="fa-brands fa-instagram"></i>
                  </a>
                  <a href="https://wa.me/5519996308930?text=Ol%C3%A1%2C%20vim%20pelo%20site%20e%20queria%20saber%20mais%20sobre%20os%20servi%C3%A7os."
                     target="_blank" rel="noopener"
                     aria-label="WhatsApp (19) 99630-8930"
                     title="WhatsApp — (19) 99630-8930">
                    <i class="fa-brands fa-whatsapp"></i>
                  </a>
                  <a href="https://web.facebook.com/mauricio.nadim/"
                     target="_blank" rel="noopener"
                     aria-label="Facebook Mauricio Nadim"
                     title="Facebook — Mauricio Nadim">
                    <i class="fa-brands fa-facebook"></i>
                  </a>
                </div>

                <ul class="contact-list">
                  <li>
                    <i class="fa-brands fa-instagram"></i>
                    <a href="https://www.instagram.com/mauricionadim" target="_blank" rel="noopener">
                      @fotografomauricionadim
                    </a>
                  </li>
                  <li>
                    <i class="fa-brands fa-whatsapp"></i>
                    <a href="https://wa.me/5519996308930?text=Ol%C3%A1%2C%20vim%20pelo%20site%20e%20queria%20saber%20mais%20sobre%20os%20servi%C3%A7os." target="_blank" rel="noopener">
                      (19) 99630-8930
                    </a>
                  </li>
                  <li>
                    <i class="fa-brands fa-facebook"></i>
                    <a href="https://web.facebook.com/mauricio.nadim/" target="_blank" rel="noopener">
                      Mauricio Nadim
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="contact-form">
              <!-- Mensagens de sucesso/erro do form de contato -->
              <?php if ($contatoSucesso): ?>
                <div class="form-flash form-flash-sucesso">
                  <?php echo htmlspecialchars($contatoSucesso); ?>
                </div>
              <?php endif; ?>
              <?php if ($contatoErro): ?>
                <div class="form-flash form-flash-erro">
                  <?php echo htmlspecialchars($contatoErro); ?>
                </div>
              <?php endif; ?>

              <form method="post" action="contato.php" novalidate>
                <div class="form-row">
                  <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome"
                           value="<?php echo htmlspecialchars($contatoDados['nome'] ?? ''); ?>" required />
                  </div>
                  <div class="form-field">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" id="sobrenome" name="sobrenome" placeholder="Seu sobrenome"
                           value="<?php echo htmlspecialchars($contatoDados['sobrenome'] ?? ''); ?>" required />
                  </div>
                </div>

                <div class="form-field">
                  <label for="email">E-mail</label>
                  <input type="email" id="email" name="email" placeholder="voce@exemplo.com"
                         value="<?php echo htmlspecialchars($contatoDados['email'] ?? ''); ?>" required />
                </div>

                <div class="form-field">
                  <label for="tipo-projeto">Tipo de projeto</label>
                  <input type="text" id="tipo-projeto" name="tipo-projeto"
                         placeholder="Evento, campanha, ensaio externo..."
                         value="<?php echo htmlspecialchars($contatoDados['tipo'] ?? ''); ?>" />
                </div>

                <div class="form-field">
                  <label for="mensagem">Mensagem</label>
                  <textarea id="mensagem" name="mensagem" rows="4"
                            placeholder="Conte um pouco sobre o que você está buscando."><?php echo htmlspecialchars($contatoDados['mensagem'] ?? ''); ?></textarea>
                </div>

                <!-- Honeypot: bots preenchem este campo, humanos não veem (ele está escondido) -->
                <div class="form-honeypot" aria-hidden="true">
                  <label for="website">Não preencha este campo:</label>
                  <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
                </div>

                <button type="submit">Enviar mensagem</button>
              </form>
            </div>
          </div>
        </section>
      </main>

      <?php include __DIR__ . '/includes/footer.php'; ?>
    </div>

    <!-- Bloqueio global de menu de contexto em <img> -->
    <script>
      document.addEventListener('contextmenu', function(e){
        if (e.target.tagName === 'IMG') e.preventDefault();
      });
    </script>
  </body>
</html>
