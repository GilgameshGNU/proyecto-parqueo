<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarifas</title>
    <!-- Aquí enlazamos el CSS externo -->
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <a href="#" class="back">Back</a>
    <a href="#" class="edit">Editar</a>

    <h1>Tarifas</h1>

    <div class="form-container">
        <form action="procesar.php" method="POST">
            <label for="monto">Monto</label>
            <input type="text" id="monto" name="monto" required>

            <label for="nombre">Nombre de tarifa</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="tipo">Tipo de vehículo</label>
            <select id="tipo" name="tipo">
                <option value="moto">Moto</option>
                <option value="auto">Auto Estandar</option>
                <option value="camion">Auto Grande</option>
            </select>

            <button type="submit" class="button">Volver al menú...</button>
        </form>
    </div>

</body>
</html>
