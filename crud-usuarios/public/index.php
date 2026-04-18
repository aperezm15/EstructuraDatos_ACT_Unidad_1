<?php

declare(strict_types=1);


(function (): void {
    $requestPath = rtrim(
        (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH),
        '/'
    );
    $publicBase = rtrim(dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
    if ($requestPath !== $publicBase && !str_starts_with($requestPath, $publicBase . '/')) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $dest = isset($_SESSION['auth']['id']) ? 'home' : 'auth.login';
        header('Location: ' . $publicBase . '/index.php?route=' . $dest);
        exit;
    }
})();


require_once __DIR__ . '/../Common/ClassLoader.php';
require_once __DIR__ . '/../Common/DependencyInjection.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/View.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/Flash.php';

DependencyInjection::boot();
Flash::start();

// ── Auth helpers ──────────────────────────────────────────────────────────────
function isLoggedIn(): bool {
    return isset($_SESSION['auth']['id']);
}

function getLoggedUser(): array {
    return is_array($_SESSION['auth'] ?? null) ? $_SESSION['auth'] : array();
}

// ── Routing ───────────────────────────────────────────────────────────────────
$route = isset($_GET['route']) ? trim((string) $_GET['route']) : 'home';
$routes = WebRoutes::routes();

if (!isset($routes[$route])) {
    http_response_code(404);
    View::render('home', buildHomeViewData('Ruta no encontrada.'));
    exit;
}

$definition = $routes[$route];
$httpMethod = strtoupper((string) $_SERVER['REQUEST_METHOD']);

if ($httpMethod !== $definition['method']) {
    http_response_code(405);
    View::render('home', buildHomeViewData('Método HTTP no permitido.'));
    exit;
}

$publicActions = array('home', 'login', 'authenticate', 'logout', 'forgot', 'forgot.send', 'create', 'store', 'activate');

if (!in_array($definition['action'], $publicActions, true) && !isLoggedIn()) {
    Flash::setMessage('Debes iniciar sesión para acceder a esta sección.');
    View::redirect('auth.login');
}

try {
    switch ($definition['action']) {
        case 'home':
            View::render('home', buildHomeViewData());
            break;

        case 'create':
            View::render('users/create', buildCreateUserViewData());
            break;

        case 'store':
            $controller = DependencyInjection::getUserController();
            $form = getCreateUserFormData();
            $form['id'] = generateUuid4();
            $errors = validateCreateUserForm($form);
            if (!empty($errors)) {
                Flash::setOld($form);
                Flash::setErrors($errors);
                Flash::setMessage('Corrige los errores del formulario.');
                View::redirect('users.create');
            }
            $request = new CreateUserWebRequest(
                $form['id'], $form['name'], $form['email'], $form['password'], $form['role']
            );
            $controller->store($request);
            Flash::setSuccess('Usuario registrado. Revisa tu correo para activar la cuenta.');
            View::redirect('auth.login');
            break;

        case 'activate':
            $token = isset($_GET['token']) ? trim((string) $_GET['token']) : '';
            $repository = DependencyInjection::getUserRepository();
            if ($token !== '' && $repository->activateByToken($token)) {
                Flash::setSuccess('¡Cuenta activada con éxito!');
                View::redirect('auth.login');
            } else {
                Flash::setMessage('Token inválido o expirado.');
                View::redirect('home');
            }
            break;

        case 'index':
            $controller = DependencyInjection::getUserController();
            $users = $controller->index();
            View::render('users/list', buildListUsersViewData($users));
            break;

        case 'show':
            $controller = DependencyInjection::getUserController();
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
        throw new Exception("El ID del usuario es necesario para ver el detalle.");
    }
            $user = $controller->show($id);
            View::render('users/show', ['pageTitle' => 'Detalle', 'user' => $user, 'message' => Flash::message()]);
            break;

        case 'edit':
            $controller = DependencyInjection::getUserController();
            $id = $_GET['id'] ?? '';
            $user = $controller->show($id);
            View::render('users/edit', buildEditUserViewData($user));
            break;

        case 'update':
            $controller = DependencyInjection::getUserController();
            $form = getUpdateUserFormData();
            $errors = validateUpdateUserForm($form);
            if (!empty($errors)) {
                Flash::setOld($form); Flash::setErrors($errors);
                header('Location: ?route=users.edit&id=' . urlencode($form['id']));
                exit;
            }
            $request = new UpdateUserWebRequest(
                $form['id'], $form['name'], $form['email'], $form['password'], $form['role'], $form['status']
            );
            $controller->update($request);
            Flash::setSuccess('Usuario actualizado.');
            View::redirect('users.index');
            break;

        case 'delete':
            $controller = DependencyInjection::getUserController();
            $controller->delete($_POST['id'] ?? '');
            Flash::setSuccess('Usuario eliminado.');
            View::redirect('users.index');
            break;

        case 'login':
            if (isLoggedIn()) View::redirect('home');
            View::render('auth/login', [
                'pageTitle' => 'Login', 'message' => Flash::message(), 
                'errors' => Flash::errors(), 'old' => Flash::old(), 'success' => Flash::success()
            ]);
            break;

        case 'authenticate':
            $email = trim(strtolower((string) ($_POST['email'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');
            $loginUseCase = DependencyInjection::getLoginUseCase();
            $user = $loginUseCase->execute(new LoginCommand($email, $password));
            $_SESSION['auth'] = [
                'id' => $user->id()->value(), 'name' => $user->name()->value(),
                'email' => $user->email()->value(), 'role' => $user->role()
            ];
            Flash::setSuccess('Bienvenido/a.');
            View::redirect('home');
            break;

        case 'logout':
            session_destroy();
            header('Location: ?route=auth.login');
            exit;

        case 'forgot':
            View::render('auth/forgot-password', [
                'pageTitle' => 'Recuperar', 'message' => Flash::message(),
                'success' => Flash::success(), 'errors' => Flash::errors(), 'old' => Flash::old()
            ]);
            break;

        case 'forgot.send':
            $forgotEmail = trim(strtolower((string) ($_POST['email'] ?? '')));
            if ($forgotEmail === '' || !filter_var($forgotEmail, FILTER_VALIDATE_EMAIL)) {
                Flash::setErrors(['email' => 'Email no válido.']);
                View::redirect('auth.forgot');
            }
            // Delegamos todo al Controller -> Service -> PhpMailAdapter
            $controller = DependencyInjection::getUserController();
            $controller->sendResetLink($forgotEmail);
            Flash::setSuccess('Si el correo existe, recibirás instrucciones.');
            View::redirect('auth.forgot');
            break;

        default:
            throw new RuntimeException('Acción no soportada.');
    }
} catch (Throwable $exception) {
    //Flash::setMessage($exception->getMessage());
    //View::redirect('home'); // Redirección genérica en caso de error
    die("<h3>Error Detectado:</h3>" . 
        "<strong>Mensaje:</strong> " . $exception->getMessage() . "<br>" .
        "<strong>Archivo:</strong> " . $exception->getFile() . "<br>" .
        "<strong>Línea:</strong> " . $exception->getLine());
}

// ── View Builders & Helpers ───────────────────────────────────────────────────
function buildListUsersViewData(array $users): array {
    return ['pageTitle' => 'Lista de usuarios', 'users' => $users, 'message' => Flash::message(), 'success' => Flash::success()];
}
function buildHomeViewData(string $message = ''): array {
    return ['pageTitle' => 'Menú principal', 'message' => $message, 'success' => Flash::success()];
}
function buildCreateUserViewData(): array {
    return ['pageTitle' => 'Registrar usuario', 'roleOptions' => UserRoleEnum::values(), 'message' => Flash::message(), 'errors' => Flash::errors(), 'old' => Flash::old()];
}
function buildEditUserViewData(UserResponse $user): array {
    return ['pageTitle' => 'Editar usuario', 'user' => $user, 'roleOptions' => UserRoleEnum::values(), 'statusOptions' => UserStatusEnum::values(), 'message' => Flash::message(), 'errors' => Flash::errors(), 'old' => Flash::old()];
}
function generateUuid4(): string {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
function getCreateUserFormData(): array {
    return ['name' => trim($_POST['name'] ?? ''), 'email' => trim($_POST['email'] ?? ''), 'password' => trim($_POST['password'] ?? ''), 'role' => trim($_POST['role'] ?? '')];
}
function getUpdateUserFormData(): array {
    return ['id' => trim($_POST['id'] ?? ''), 'name' => trim($_POST['name'] ?? ''), 'email' => trim($_POST['email'] ?? ''), 'password' => $_POST['password'] ?? '', 'role' => trim($_POST['role'] ?? ''), 'status' => trim($_POST['status'] ?? '')];
}
function validateCreateUserForm(array $form): array {
    $errors = [];
    if ($form['name'] === '') $errors['name'] = 'Nombre obligatorio.';
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email inválido.';
    if (strlen($form['password']) < 8) $errors['password'] = 'Mínimo 8 caracteres.';
    return $errors;
}
function validateUpdateUserForm(array $form): array {
    $errors = [];
    if ($form['name'] === '') $errors['name'] = 'Nombre obligatorio.';
    return $errors;
}