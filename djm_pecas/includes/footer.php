</main>
<footer class="site-footer">
    <div class="footer-inner">
        <a href="index.php" class="footer-brand"><img src="assets/djm_logo.png" alt="">DJM Distribuidora</a>
        <p>Kits de peças automotivas com qualidade e confiança.</p>
        <div class="footer-links">
            <a href="catalogo.php">Ver catálogo</a>
            <a href="contato.php">Entre em contato</a>
        </div>
        <small>&copy; <?= date('Y') ?> DJM Distribuidora. Todos os direitos reservados.</small>
    </div>
</footer>
<div class="modal-backdrop" id="product-modal" hidden>
    <section class="product-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button type="button" class="modal-close" aria-label="Fechar detalhes">&times;</button>
        <img class="modal-image" src="" alt="">
        <h2 id="modal-title"></h2>
        <h3>Detalhes do produto</h3>
        <p class="modal-details"></p>
    </section>
</div>
</body>
</html>
