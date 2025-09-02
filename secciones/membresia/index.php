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
    <title>Membresía - Sistema de Parqueo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header - Posicionado como en la imagen -->
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
        <!-- Logo y título - Centrados como en la imagen -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-car"></i>
            </div>
            <h1 class="app-title">CAR PARKING</h1>
            <h2 class="section-title">Membresía</h2>
        </div>

        <!-- Botón para agregar nuevo banco -->
        <div style="text-align: center; margin-bottom: 20px;">
            <button type="button" class="main-button" onclick="mostrarModalAgregar()" style="max-width: 200px;">
                <i class="fas fa-plus"></i>
                Agregar Banco
            </button>
        </div>

        <!-- Formulario de membresía - IDÉNTICO a la imagen -->
        <form id="membershipForm" method="POST" action="procesar_membresia.php">
            <div class="bank-list" id="bankList">
                <?php if ($bancos && count($bancos) > 0): ?>
                    <?php foreach ($bancos as $banco): ?>
                        <div class="bank-item" data-id="<?php echo $banco['IdBanco']; ?>">
                            <div class="bank-name"><?php echo htmlspecialchars($banco['Nombre']); ?></div>
                            <input type="number" 
                                   class="percentage-input" 
                                   name="banco_<?php echo $banco['IdBanco']; ?>" 
                                   value="<?php echo $banco['Porcentaje']; ?>" 
                                   min="0" 
                                   max="100" 
                                   step="1"
                                   data-id="<?php echo $banco['IdBanco']; ?>">
                            <div class="action-buttons">
                                <button type="button" class="btn-edit" onclick="editarBanco(<?php echo $banco['IdBanco']; ?>)" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn-view" onclick="verBanco(<?php echo $banco['IdBanco']; ?>)" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn-delete" onclick="eliminarBanco(<?php echo $banco['IdBanco']; ?>)" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 20px;"></i>
                        <p>No hay bancos registrados. Agrega el primer banco para comenzar.</p>
                    </div>
                <?php endif; ?>
            </div>



            <!-- Botón guardar - -->
            <button type="submit" class="main-button">
                <i class="fas fa-save"></i>
                Guardar Cambios
            </button>
        </form>
    </div>

    <!-- Modal para agregar/editar banco -->
    <div id="bancoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3 id="modalTitle">Agregar Nuevo Banco</h3>
            <form id="bancoForm">
                <input type="hidden" id="bancoId" name="id">
                <div style="margin-bottom: 15px;">
                    <label for="bancoNombre">Nombre del Banco:</label>
                    <input type="text" id="bancoNombre" name="nombre" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="bancoPorcentaje">Porcentaje (%):</label>
                    <input type="number" id="bancoPorcentaje" name="porcentaje" min="0" max="100" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="main-button" style="max-width: 150px;">Guardar</button>
                    <button type="button" onclick="cerrarModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; margin-left: 10px;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
