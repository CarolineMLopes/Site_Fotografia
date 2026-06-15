<?php
/**
 * head.php — Tags <head> reutilizáveis.
 *
 * Variáveis esperadas (defina ANTES do include):
 *   $pageTitle  -> título da aba
 *   $extraCss   -> array de caminhos CSS adicionais (opcional)
 */
$pageTitle = $pageTitle ?? 'NK Edição e Vídeo – Fotógrafo Mauricio Nadim';
$extraCss  = $extraCss  ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

    <link rel="stylesheet" href="../css/vars.css" />
    <?php foreach ($extraCss as $css): ?>
      <link rel="stylesheet" href="<?php echo htmlspecialchars($css, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endforeach; ?>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
