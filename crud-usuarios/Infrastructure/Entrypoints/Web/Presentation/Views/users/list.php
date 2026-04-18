<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<h1>Lista de usuarios</h1>

<?php if (!empty($success)): ?>
    <div style="color: green; background: #e6fffa; padding: 10px; border: 1px solid #38a169; margin-bottom: 15px;">
        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<?php if (empty($users)): ?>
    <p>No hay usuarios registrados todavía.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th style="width: 250px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="badge"><?= htmlspecialchars($user->getRole(), ENT_QUOTES, 'UTF-8') ?></span>
                    </td>
                    <td><?= htmlspecialchars($user->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td style="text-align: center;">
                        
                        <a href="?route=show&id=<?= urlencode($user->getId()) ?>" 
                           style="text-decoration: none; color: green; margin-right: 10px;" title="Ver detalle">
                           👁️ Ver
                        </a>

                        <a href="?route=users.edit&id=<?= urlencode($user->getId()) ?>" 
                           style="text-decoration: none; color: blue; margin-right: 10px;" title="Editar usuario">
                           ✏️ Editar
                        </a>

                        <form action="?route=users.delete" method="POST" style="display:inline;" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar a <?= htmlspecialchars($user->getName()) ?>?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user->getId()) ?>">
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer; padding:0; font-family:inherit; font-size:inherit;" title="Eliminar usuario">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>