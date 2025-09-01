<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Membresía</title>
    <link rel="stylesheet" href="tarifas.css">
</head>
<body>
<div class="container form-container">
    <h1>Nueva Membresía</h1>
    <form action="procesar_membresia.php" method="POST">
        <input type="hidden" name="accion" value="crear">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="precio">Precio (Bs.)</label>
        <input type="number" step="0.01" id="precio" name="precio" required>

        <label for="descuento">Descuento (%)</label>
        <input type="number" step="0.01" id="descuento" name="descuento">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="Activa">Activa</option>
            <option value="Inactiva">Inactiva</option>
        </select>
        

        <div class="actions">
            <button type="submit" class="btn-save">Guardar</button>
            <a href="membresias.php" class="btn-back">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
