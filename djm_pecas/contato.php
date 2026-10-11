<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'contato';
$pageTitle = 'Contato';
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <p class="eyebrow">Estamos à disposição</p>
    <h1>Fale com a DJM</h1>
    <p>Entre em contato para consultar peças, kits e disponibilidade.</p>
</section>
<section class="contact-section section-wrap">
    <a class="contact-card" href="https://wa.me/5511940013289" target="_blank" rel="noopener noreferrer">
        <span class="contact-icon" aria-hidden="true">☎</span>
        <h2>Fone / WhatsApp</h2>
        <p>+55 11 94001-3289</p>
        <span class="contact-action">Conversar pelo WhatsApp ↗</span>
    </a>
    <a class="contact-card" href="https://instagram.com/djm_distribuidora" target="_blank" rel="noopener noreferrer">
        <span class="contact-icon contact-icon-instagram" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                <circle cx="12" cy="12" r="4"></circle>
                <circle class="instagram-flash" cx="17.5" cy="6.5" r="1"></circle>
            </svg>
        </span>
        <h2>Instagram</h2>
        <p>@djm_distribuidora</p>
        <span class="contact-action">Acompanhar novidades ↗</span>
    </a>
    <a class="contact-card" href="mailto:djm_distribuidora@hotmail.com">
        <span class="contact-icon" aria-hidden="true">✉</span>
        <h2>E-mail</h2>
        <p class="email-address">djm_distribuidora@hotmail.com</p>
        <span class="contact-action">Enviar um e-mail ↗</span>
    </a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
