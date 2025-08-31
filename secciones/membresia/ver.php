<?php
include_once '../../db.php';

// Obtener datos de membresía desde la base de datos
$query = "SELECT * FROM membresia_bancos ORDER BY id";
$result = mysqli_query($conectador, $query);

$bancos = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bancos[] = $row;
    }
}

// Si no hay datos, usar valores por defecto
if (empty($bancos)) {
    $bancos = [
        ['nombre' => 'Banco Unión', 'porcentaje' => 50],
        ['nombre' => 'Banco Ganadero', 'porcentaje' => 27],
        ['nombre' => 'Banco Mercantil', 'porcentaje' => 16]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Membresía - Sistema de Parqueo</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div>
                <span style="font-size: 1.2rem; font-weight: 600; color: #495057;">Cliente</span>
            </div>
            <a href="../../index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="main-container">
        <!-- Logo y título -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-car"></i>
            </div>
            <h1 class="app-title">CAR PARKING</h1>
            <h2 class="section-title">Membresía</h2>
        </div>

        <!-- Navegación -->
        <?php include 'nav.php'; ?>
        
        <!-- Vista de membresía -->
        <div class="content-card">
            <div class="bank-list">
                <?php foreach ($bancos as $banco): ?>
                <div class="bank-item">
                    <div class="bank-name"><?php echo htmlspecialchars($banco['nombre']); ?></div>
                    <div class="percentage-display"><?php echo htmlspecialchars($banco['porcentaje']); ?>%</div>
                    <?php if ($banco['nombre'] === 'Banco Unión'): ?>
                    <div class="action-buttons">
                        <a href="index.php" class="btn btn-edit-large">
                            <i class=""></i>
                            Editar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Botón volver al menú -->
            <a href="../../index.php" class="main-button" style="text-decoration: none; display: inline-block;">
                <i class="fas fa-home"></i>
                Volver al menú
            </a>
        </div>
    </div>

    <script>
        // Función para mostrar información del banco al hacer clic
        document.querySelectorAll('.bank-item').forEach(item => {
            item.addEventListener('click', function() {
                const bankName = this.querySelector('.bank-name').textContent;
                const percentage = this.querySelector('.percentage-display').textContent;
                
                // Mostrar información en una alerta o modal
                console.log(`${bankName}: ${percentage}`);
            });
        });

        // Agregar efecto hover a los elementos del banco
        document.querySelectorAll('.bank-item').forEach(item => {
            item.style.cursor = 'pointer';
        });
    </script>
</body>
</html>
