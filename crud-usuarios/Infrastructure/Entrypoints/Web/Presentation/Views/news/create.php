<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<h1>Registrar Nueva Noticia</h1>

<form action="?route=news.store" method="POST" class="form-container">
    <div class="form-group">
        <label>Categoría:</label>
        <input type="text" name="categoria" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Fecha:</label>
            <input type="date" name="fecha" required>
        </div>
        <div class="form-group">
            <label>País:</label>
            <input type="text" name="pais" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Departamento:</label>
            <input type="text" name="departamento">
        </div>
        <div class="form-group">
            <label>Ciudad:</label>
            <input type="text" name="ciudad">
        </div>
    </div>

    <div class="form-group">
        <label>Periodista:</label>
        <input type="text" name="periodista" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Programa que Emite:</label>
            <input type="text" name="programaEmite">
        </div>
        <div class="form-group">
            <label>Fecha/Hora Emisión:</label>
            <input type="datetime-local" name="fechaEmision">
        </div>
    </div>

    <div class="form-group">
        <label>Nivel de Público:</label>
        <select name="nivelPublico">
            <option value="General">General</option>
            <option value="Infantil">Infantil</option>
            <option value="Adultos">Adultos</option>
        </select>
    </div>

    <div class="form-group">
        <label>Descripción:</label>
        <textarea name="descripcion" rows="4"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Noticia</button>
    <a href="?route=news.index" class="btn">Cancelar</a>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>