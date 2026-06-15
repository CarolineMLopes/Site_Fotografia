<?php
/**
 * contato.php — Recebe o form de contato e envia e-mail via Gmail SMTP (PHPMailer).
 *
 * Fluxo:
 *  1) Valida campos obrigatórios e o e-mail.
 *  2) Verifica o honeypot (campo invisível pra bloquear bot).
 *  3) Carrega configurações SMTP de mail-config.php.
 *  4) Monta e envia o e-mail com PHPMailer.
 *  5) Grava mensagem flash em $_SESSION e volta pro #contato.
 */

require_once __DIR__ . '/includes/auth.php';   // já chama session_start()

// Carrega PHPMailer (instalado via Composer na raiz do projeto)
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inicio.php');
    exit;
}

// ── 1) Coleta e sanitiza os dados ───────────────────────────────
$nome      = trim(strip_tags($_POST['nome']         ?? ''));
$sobrenome = trim(strip_tags($_POST['sobrenome']    ?? ''));
$email     = trim(filter_var($_POST['email']        ?? '', FILTER_SANITIZE_EMAIL));
$tipo      = trim(strip_tags($_POST['tipo-projeto'] ?? ''));
$mensagem  = trim(strip_tags($_POST['mensagem']     ?? ''));
$honeypot  = trim($_POST['website']                 ?? ''); // honeypot

// ── 2) Honeypot — se preenchido, é bot. Finge sucesso e ignora. ──
if ($honeypot !== '') {
    $_SESSION['contato_sucesso'] = 'Mensagem enviada com sucesso!';
    header('Location: inicio.php#contato');
    exit;
}

// ── 3) Validações ───────────────────────────────────────────────
$erros = [];
if ($nome === '')                                       $erros[] = 'O nome é obrigatório.';
if ($sobrenome === '')                                  $erros[] = 'O sobrenome é obrigatório.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))         $erros[] = 'E-mail inválido.';
if ($mensagem === '' || mb_strlen($mensagem) < 5)       $erros[] = 'A mensagem está muito curta.';
if (mb_strlen($mensagem) > 3000)                        $erros[] = 'Mensagem excede 3000 caracteres.';

if (!empty($erros)) {
    $_SESSION['contato_erro']  = implode(' ', $erros);
    $_SESSION['contato_dados'] = compact('nome', 'sobrenome', 'email', 'tipo', 'mensagem');
    header('Location: inicio.php#contato');
    exit;
}

// ── 4) Carrega config SMTP ──────────────────────────────────────
$config = require __DIR__ . '/includes/mail-config.php';

// ── 5) Monta e envia o e-mail ───────────────────────────────────
$mail = new PHPMailer(true);

try {
    // Servidor SMTP
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp_user'];
    $mail->Password   = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_secure'] === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int) $config['smtp_port'];
    $mail->CharSet    = 'UTF-8';

    // Remetente e destinatário
    $mail->setFrom($config['smtp_user'], $config['remetente_nome']);
    $mail->addAddress($config['destino'], $config['destino_nome']);
    $mail->addReplyTo($email, "$nome $sobrenome"); // ao "Responder", vai direto pro visitante

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = "Novo contato pelo site - $nome $sobrenome";

    $corpoHtml = "
        <h2>Nova mensagem do site NK Edição e Vídeo</h2>
        <p><strong>Nome:</strong> " . htmlspecialchars("$nome $sobrenome") . "</p>
        <p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Tipo de projeto:</strong> " . htmlspecialchars($tipo ?: 'não informado') . "</p>
        <p><strong>Mensagem:</strong></p>
        <p style='white-space: pre-wrap;'>" . nl2br(htmlspecialchars($mensagem)) . "</p>
        <hr>
        <p style='color:#888;font-size:.85em'>
            Enviado em " . date('d/m/Y H:i:s') . "
        </p>
    ";

    $mail->Body    = $corpoHtml;
    // Versão texto puro (caso o cliente de e-mail não suporte HTML)
    $mail->AltBody = "Nome: $nome $sobrenome\nE-mail: $email\nTipo: $tipo\n\nMensagem:\n$mensagem";

    $mail->send();

    $_SESSION['contato_sucesso'] = 'Mensagem enviada com sucesso! Em breve entraremos em contato.';
} catch (Exception $e) {
    // Para debug, salve a mensagem real em log; para o usuário, mostre algo amigável.
    error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
    $_SESSION['contato_erro']  = 'Não foi possível enviar a mensagem agora. Tente novamente em instantes.';
    $_SESSION['contato_dados'] = compact('nome', 'sobrenome', 'email', 'tipo', 'mensagem');
}

header('Location: inicio.php#contato');
exit;
