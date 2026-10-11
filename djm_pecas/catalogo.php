<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'catalogo';
$pageTitle = 'Catálogo';
$products = get_products();
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <p class="eyebrow">DJM / ESPECIFICAÇÕES</p>
    <h1>Catálogo de componentes</h1>
    <p>Consulte as descrições técnicas e confirme a aplicação antes da instalação.</p>
</section>
<section class="catalog-section section-wrap">
    <?php if ($products === []): ?>
        <p class="empty-state">Nenhuma peça cadastrada no momento. Entre em contato para consultar nosso estoque.</p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $productIndex => $product): ?>
                <article class="product-card">
                    <div class="product-image-wrap">
                        <img class="product-image" src="<?= escape_html((string) $product['image']) ?>" alt="<?= escape_html((string) $product['name']) ?>" loading="lazy">
                        <span class="product-label">DJM / <?= str_pad((string) ($productIndex + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="product-copy">
                        <p class="product-kicker">Descrição técnica</p>
                        <h2><?= escape_html((string) $product['name']) ?></h2>
                        <p><?= escape_html((string) $product['details']) ?></p>
                        <button
                            class="product-details-button"
                            type="button"
                            data-open-details
                            data-product-name="<?= escape_html((string) $product['name']) ?>"
                            data-product-image="<?= escape_html((string) $product['image']) ?>"
                            data-product-details="<?= escape_html((string) $product['details']) ?>"
                        >Consultar detalhes <span aria-hidden="true">↗</span></button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
