<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

define('DJM_ROOT', dirname(__DIR__));
define('DJM_PRODUCTS_FILE', DJM_ROOT . '/storage/products.json');
define('DJM_ADMIN_FILE', DJM_ROOT . '/storage/admin.json');
define('DJM_UPLOADS_DIR', DJM_ROOT . '/assets/uploads');

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function get_products(): array
{
    $contents = file_get_contents(DJM_PRODUCTS_FILE);
    if ($contents === false) {
        throw new RuntimeException('Não foi possível ler o catálogo.');
    }

    $products = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($products)) {
        throw new RuntimeException('O catálogo possui um formato inválido.');
    }

    return $products;
}

function save_products(array $products): void
{
    $file = fopen(DJM_PRODUCTS_FILE, 'c+');
    if ($file === false) {
        throw new RuntimeException('Não foi possível abrir o arquivo do catálogo para gravação.');
    }

    try {
        if (!flock($file, LOCK_EX)) {
            throw new RuntimeException('Não foi possível bloquear o catálogo para gravação.');
        }

        $contents = json_encode(
            array_values($products),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
        if (!ftruncate($file, 0) || !rewind($file) || fwrite($file, $contents) === false || !fflush($file)) {
            throw new RuntimeException('Não foi possível salvar o catálogo.');
        }

        flock($file, LOCK_UN);
    } finally {
        fclose($file);
    }
}

function get_csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(): bool
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    return is_string($submittedToken)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submittedToken);
}

function is_admin_authenticated(): bool
{
    return ($_SESSION['admin_authenticated'] ?? false) === true;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($flash) ? $flash : null;
}

function redirect_to(string $location): never
{
    header('Location: ' . $location, true, 303);
    exit;
}

function count_characters(string $value): int
{
    $count = preg_match_all('/./us', $value, $matches);
    return $count === false ? strlen($value) : $count;
}

function process_admin_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    if (!verify_csrf_token()) {
        set_flash('error', 'Sua sessão expirou. Atualize a página e tente novamente.');
        redirect_to('cadastrar.php');
    }

    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'login') {
            $adminConfigContents = file_get_contents(DJM_ADMIN_FILE);
            if ($adminConfigContents === false) {
                set_flash('error', 'Painel indisponível: não foi possível ler storage/admin.json.');
                redirect_to('cadastrar.php');
            }

            try {
                $adminConfig = json_decode($adminConfigContents, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $error) {
                set_flash('error', 'Painel indisponível: storage/admin.json contém JSON inválido.');
                redirect_to('cadastrar.php');
            }

            $adminPasswordHash = is_array($adminConfig) ? ($adminConfig['password_hash'] ?? null) : null;
            if (!is_string($adminPasswordHash) || $adminPasswordHash === '') {
                set_flash('error', 'Painel indisponível: configure password_hash em storage/admin.json.');
                redirect_to('cadastrar.php');
            }

            $submittedPassword = $_POST['password'] ?? '';
            if (!is_string($submittedPassword) || !password_verify($submittedPassword, $adminPasswordHash)) {
                set_flash('error', 'Senha inválida.');
                redirect_to('cadastrar.php');
            }

            session_regenerate_id(true);
            $_SESSION['admin_authenticated'] = true;
            set_flash('success', 'Acesso autorizado.');
            redirect_to('cadastrar.php');
        }

        if ($action === 'logout') {
            unset($_SESSION['admin_authenticated']);
            session_regenerate_id(true);
            set_flash('success', 'Sessão encerrada.');
            redirect_to('cadastrar.php');
        }

        if (!is_admin_authenticated()) {
            set_flash('error', 'Faça login para acessar o painel.');
            redirect_to('cadastrar.php');
        }

        $products = get_products();

        if ($action === 'add') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $details = trim((string) ($_POST['details'] ?? ''));

            if ($name === '' || count_characters($name) > 100) {
                set_flash('error', 'Informe um nome de até 100 caracteres.');
                redirect_to('cadastrar.php');
            }
            if ($details === '' || count_characters($details) > 400) {
                set_flash('error', 'Informe detalhes com até 400 caracteres.');
                redirect_to('cadastrar.php');
            }

            $upload = $_FILES['image'] ?? null;
            if (!is_array($upload) || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                set_flash('error', 'Selecione uma imagem válida para a peça.');
                redirect_to('cadastrar.php');
            }
            if (($upload['size'] ?? 0) > 5 * 1024 * 1024 || !is_uploaded_file($upload['tmp_name'] ?? '')) {
                set_flash('error', 'A imagem deve ter no máximo 5 MB.');
                redirect_to('cadastrar.php');
            }

            $imageInfo = getimagesize($upload['tmp_name']);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($upload['tmp_name']);
            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];

            if ($imageInfo === false || !isset($allowedTypes[$mimeType])) {
                set_flash('error', 'Envie uma imagem JPG, PNG, WEBP ou GIF.');
                redirect_to('cadastrar.php');
            }

            if (!is_dir(DJM_UPLOADS_DIR) && !mkdir(DJM_UPLOADS_DIR, 0755, true) && !is_dir(DJM_UPLOADS_DIR)) {
                throw new RuntimeException('Não foi possível preparar a pasta de imagens.');
            }

            $filename = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
            $destination = DJM_UPLOADS_DIR . '/' . $filename;
            if (!move_uploaded_file($upload['tmp_name'], $destination)) {
                throw new RuntimeException('Não foi possível armazenar a imagem enviada.');
            }

            $products[] = [
                'id' => bin2hex(random_bytes(8)),
                'name' => $name,
                'image' => 'assets/uploads/' . $filename,
                'details' => $details,
            ];

            try {
                save_products($products);
            } catch (RuntimeException | JsonException $error) {
                unlink($destination);
                throw $error;
            }

            set_flash('success', 'Peça cadastrada com sucesso.');
            redirect_to('cadastrar.php');
        }

        if ($action === 'delete') {
            $id = (string) ($_POST['id'] ?? '');
            $remainingProducts = array_values(array_filter(
                $products,
                static fn (array $product): bool => (string) ($product['id'] ?? '') !== $id
            ));

            if (count($remainingProducts) === count($products)) {
                set_flash('error', 'A peça selecionada não foi encontrada.');
                redirect_to('cadastrar.php');
            }

            save_products($remainingProducts);
            foreach ($products as $product) {
                if ((string) ($product['id'] ?? '') === $id) {
                    $image = (string) ($product['image'] ?? '');
                    if (str_starts_with($image, 'assets/uploads/')) {
                        $imagePath = DJM_ROOT . '/' . $image;
                        if (is_file($imagePath) && !unlink($imagePath)) {
                            error_log('Não foi possível remover a imagem de produto: ' . $imagePath);
                        }
                    }
                    break;
                }
            }

            set_flash('success', 'Peça excluída do catálogo.');
            redirect_to('cadastrar.php');
        }

        set_flash('error', 'Ação não reconhecida.');
        redirect_to('cadastrar.php');
    } catch (RuntimeException $error) {
        error_log('Erro no painel de cadastro DJM: ' . $error->getMessage());
        set_flash('error', 'Não foi possível concluir a operação. Verifique as permissões de armazenamento do servidor.');
        redirect_to('cadastrar.php');
    } catch (JsonException $error) {
        error_log('Erro ao processar o catálogo DJM: ' . $error->getMessage());
        set_flash('error', 'O catálogo não pôde ser processado. Verifique o arquivo de dados.');
        redirect_to('cadastrar.php');
    }
}
