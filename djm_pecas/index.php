<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'home';
$pageTitle = 'Home';
$products = get_products();
$featuredProducts = array_reverse(array_slice($products, -10));
$featuredProduct = $featuredProducts[0] ?? null;
$otherProducts = array_slice($products, 1, 3);
require __DIR__ . '/includes/header.php';
?>
<section class="home-hero">
    <div class="hero-copy">
        <p class="eyebrow">DJM / SISTEMAS AUTOMOTIVOS</p>
        <h1>Precisão em cada peça. <span>Performance em movimento.</span></h1>
        <p class="hero-description">Kits e componentes para manutenção automotiva, com informações de aplicação para ajudar você a encontrar a peça compatível.</p>
        <div class="hero-actions">
            <a class="button button-primary" href="catalogo.php">Explorar catálogo <span aria-hidden="true">↗</span></a>
            <a class="button button-outline" href="contato.php">Consultar aplicação</a>
        </div>
        <p class="hero-note"><span class="status-dot"></span> Suporte direto para oficinas e lojistas</p>
    </div>
    <?php if ($featuredProduct !== null): ?>
        <article class="hero-product" data-featured-carousel>
            <img
                class="hero-product-image"
                data-featured-image
                src="<?= escape_html((string) $featuredProduct['image']) ?>"
                alt="<?= escape_html((string) $featuredProduct['name']) ?>"
                fetchpriority="high"
            >
            <div class="hero-product-overlay">
                <p class="hero-product-index"><span data-featured-index>DJM / 01</span><span>DESTAQUE DO CATÁLOGO</span></p>
                <h2 data-featured-name><?= escape_html((string) $featuredProduct['name']) ?></h2>
                <p data-featured-details><?= escape_html((string) $featuredProduct['details']) ?></p>
                <button
                    class="product-open-link"
                    type="button"
                    data-open-details
                    data-featured-modal
                    data-product-name="<?= escape_html((string) $featuredProduct['name']) ?>"
                    data-product-image="<?= escape_html((string) $featuredProduct['image']) ?>"
                    data-product-details="<?= escape_html((string) $featuredProduct['details']) ?>"
                >Ver descrição técnica <span aria-hidden="true">↗</span></button>
                <?php if (count($featuredProducts) > 1): ?>
                    <div class="hero-product-indicators" role="group" aria-label="Escolher destaque do catálogo">
                        <?php foreach ($featuredProducts as $index => $product): ?>
                            <button
                                class="hero-product-indicator<?= $index === 0 ? ' is-active' : '' ?>"
                                type="button"
                                data-featured-select
                                data-product-name="<?= escape_html((string) $product['name']) ?>"
                                data-product-image="<?= escape_html((string) $product['image']) ?>"
                                data-product-details="<?= escape_html((string) $product['details']) ?>"
                                aria-label="Mostrar destaque <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?>: <?= escape_html((string) $product['name']) ?>"
                                aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"
                            ><span aria-hidden="true"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <span class="hero-product-mark" aria-hidden="true">DJM</span>
        </article>
    <?php else: ?>
        <div class="hero-product hero-product-empty">
            <span class="eyebrow">CATÁLOGO DJM</span>
            <p>Nenhum produto cadastrado no momento.</p>
            <a class="product-open-link" href="contato.php">Fale com a equipe <span aria-hidden="true">↗</span></a>
        </div>
    <?php endif; ?>
</section>
<?php if ($otherProducts !== []): ?>
<section class="home-catalog section-wrap">
    <div class="section-heading section-heading-row">
        <div>
            <p class="eyebrow">Seleção DJM / <?= str_pad((string) count($otherProducts), 2, '0', STR_PAD_LEFT) ?></p>
            <h2>Componentes em foco</h2>
            <p>Consulte os detalhes e confira a aplicação de cada conjunto.</p>
        </div>
        <a class="text-link" href="catalogo.php">Ver catálogo completo <span aria-hidden="true">↗</span></a>
    </div>
    <div class="product-grid home-product-grid">
        <?php foreach ($otherProducts as $index => $product): ?>
            <article class="product-card">
                <div class="product-image-wrap">
                    <img class="product-image" src="<?= escape_html((string) $product['image']) ?>" alt="<?= escape_html((string) $product['name']) ?>" loading="lazy">
                    <span class="product-label">DJM / <?= str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT) ?></span>
                </div>
                <div class="product-copy">
                    <p class="product-kicker">Descrição técnica</p>
                    <h3><?= escape_html((string) $product['name']) ?></h3>
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
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
