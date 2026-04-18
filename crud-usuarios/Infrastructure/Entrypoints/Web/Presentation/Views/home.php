<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/menu.php'; ?>
<h1>Menú principal del CRUDL de usuarios</h1>
<p>Desde aquí podrás acceder a las operaciones del sistema.</p>
<?php if (!empty($message)): ?>
    <div class="alert-error">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert-success">
        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
<ul>
    <li>
        <a href="?route=users.create">C: Registrar usuario</a>
    </li>
    <li>
        <a href="?route=users.index">R: Consultar usuario</a>
    </li>
    <li>
        <a href="?route=users.index">U: Actualizar usuario</a> 
        
    </li>
    <li>
        <a href="?route=users.index">D: Eliminar usuario</a>
        
    </li>
    <li>
        <a href="?route=users.index">L: Listar usuarios</a>
    </li>
</ul>
<?php require __DIR__ . '/layouts/footer.php'; ?>