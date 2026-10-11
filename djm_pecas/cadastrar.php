<?php
require_once __DIR__ . '/includes/functions.php';
process_admin_post();
$currentPage = '';
$pageTitle = 'Cadastro de peças';
$authenticated = is_admin_authenticated();
$products = $authenticated ? get_products() : [];
$flash = take_flash();
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner admin-banner">
    <p class="eyebrow">Painel de gerenciamento</p>
    <h1>Cadastrar peças</h1>
    <p>Adicione e gerencie os produtos exibidos no catálogo.</p>
</section>
<section class="admin-section section-wrap">
    <?php if ($flash !== null): ?>
        <p class="flash-message flash-<?= escape_html((string) $flash['type']) ?>" role="status"><?= escape_html((string) $flash['message']) ?></p>
    <?php endif; ?>
    <?php if (!$authenticated): ?>
        <form class="admin-form admin-login content-panel" action="cadastrar.php" method="post">
            <h2>Acesso restrito</h2>
            <p>Entre com a senha administrativa para gerenciar as peças.</p>
            <input type="hidden" name="csrf_token" value="<?= escape_html(get_csrf_token()) ?>">
            <input type="hidden" name="action" value="login">
            <label for="password">Senha administrativa</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button class="button button-primary" type="submit">Entrar</button>
        </form>
    <?php else: ?>
    <div class="admin-toolbar">
        <p>Gerencie as imagens e informações que aparecem no catálogo público.</p>
        <form action="cadastrar.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= escape_html(get_csrf_token()) ?>">
            <input type="hidden" name="action" value="logout">
            <button class="button button-outline" type="submit">Sair do painel</button>
        </form>
    </div>
    <div class="admin-layout">
        <form class="admin-form content-panel" action="cadastrar.php" method="post" enctype="multipart/form-data">
            <h2>Adicionar peça</h2>
            <input type="hidden" name="csrf_token" value="<?= escape_html(get_csrf_token()) ?>">
            <input type="hidden" name="action" value="add">
            <label for="name">Nome da peça</label>
            <input id="name" name="name" type="text" maxlength="100" required>
            <label for="image">Imagem da peça</label>
            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" required>
            <small>JPG, PNG, WEBP ou GIF. Tamanho máximo: 5 MB.</small>
            <label for="details">Detalhes do produto</label>
            <textarea id="details" name="details" rows="6" maxlength="400" required></textarea>
            <small id="character-count">0 / 400 caracteres</small>
            <button class="button button-primary" type="submit">Cadastrar peça</button>
        </form>
        <div class="admin-products">
            <h2>Peças cadastradas <span><?= count($products) ?></span></h2>
            <?php if ($products === []): ?>
                <p class="empty-state">Ainda não há peças cadastradas.</p>
            <?php else: ?>
                <div class="admin-list">
                    <?php foreach ($products as $product): ?>
                        <article class="admin-product">
                            <img src="<?= escape_html((string) $product['image']) ?>" alt="">
                            <div class="admin-product-copy">
                                <h3><?= escape_html((string) $product['name']) ?></h3>
                                <p><?= escape_html((string) $product['details']) ?></p>
                            </div>
                            <form action="cadastrar.php" method="post" data-confirm-delete>
                                <input type="hidden" name="csrf_token" value="<?= escape_html(get_csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= escape_html((string) $product['id']) ?>">
                                <button class="button button-danger" type="submit">Excluir</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
