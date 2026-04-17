<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<h1>Lista de usuarios</h1>
<?php if (empty($users)): ?>
    <p>No hay usuarios registrados todavía.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->getRole(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require __DIR__ . '/../layouts/footer.php'; ?>