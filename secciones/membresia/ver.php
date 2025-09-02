<?php
include_once '../../db.php';
include_once 'banco_operations.php';

// Obtener bancos desde la base de datos
$bancos = obtenerBancos();
$total_porcentajes = obtenerTotalPorcentajes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Membresía - Sistema de Parqueo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div>
            <span style="font-size: 1.2rem; font-weight: 600; color: #333;">Membresía</span>
        </div>
        <a href="../../index.php" class="back">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Logo y título --->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-car"></i>
            </div>
            <h1 class="app-title">CAR PARKING</h1>
            <h2 class="section-title">Ver Membresía</h2>
        </div>
        <!-- Botones de acción -->
        <div class="action-buttons" style="justify-content: center; margin-top: 30px;">
            <a href="index.php" class="main-butto" style="max-width: 200px; text-decoration: none; display: inline-block; margin-left: 490px;">
                <i class="fas fa-pencil-alt"></i>
                Editar
            </a>
        </div>

        <!-- Lista de bancos en modo solo lectura -->
        <div class="bank-list">
            <?php if ($bancos && count($bancos) > 0): ?>
                <?php foreach ($bancos as $banco): ?>
                    <div class="bank-item">
                        <div class="bank-name"><?php echo htmlspecialchars($banco['Nombre']); ?></div>
                        <div class="percentage-display"><?php echo number_format($banco['Porcentaje'], 2); ?>%</div>
                        <div class="action-buttons">
                            <button type="button" class="btn-view" onclick="verDetallesBanco(<?php echo $banco['IdBanco']; ?>)" title="Ver Detalles">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <p>No hay bancos registrados en el sistema.</p>
                </div>
            <?php endif; ?>
        </div>

        

        <!-- Botones de acción -->
        <div class="action-buttons" style="justify-content: center; margin-top: 30px;">
            <a href="../../index.php" class="main-button" style="max-width: 800px; text-decoration: none; display: inline-block; margin-left: 20px;">
                <i class="fas fa-home"></i>
                Menú Principal
            </a>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Función para ver detalles del banco
        function verDetallesBanco(id) {
            fetch('banco_operations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=obtener&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const banco = data.data;
                    const fechaCreacion = new Date(banco.FechaCreacion).toLocaleDateString('es-ES');
                    const fechaActualizacion = new Date(banco.FechaActualizacion).toLocaleDateString('es-ES');
                    
                    alert(`Detalles del Banco:\n\n` +
                          `Nombre: ${banco.Nombre}\n` +
                          `Porcentaje: ${banco.Porcentaje}%\n` +
                          `Estado: ${banco.Estado}\n` +
                          `Fecha de Creación: ${fechaCreacion}\n` +
                          `Última Actualización: ${fechaActualizacion}`);
                } else {
                    alert('Error al obtener detalles del banco');
                }
            })
            .catch(error => {
                alert('Error al obtener detalles del banco');
            });
        }
    </script>
</body>
</html>
