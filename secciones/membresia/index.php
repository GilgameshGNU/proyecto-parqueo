<?php
include_once '../../db.php';
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
            <span style="font-size: 1.2rem; font-weight: 600; color: #333;">Cliente</span>
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

        <!-- Formulario de membresía - IDÉNTICO a la imagen -->
        <form id="membershipForm" method="POST" action="procesar_membresia.php">
            <div class="bank-list">
                <!-- Banco Unión -->
                <div class="bank-item">
                    <div class="bank-name">Banco Unión</div>
                    <input type="number" 
                           class="percentage-input" 
                           name="banco_union" 
                           value="50" 
                           min="0" 
                           max="100" 
                           step="1">
                    <div class="action-buttons">
                        <button type="button" class="btn-edit" onclick="editBank('banco_union')" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn-view" onclick="viewBank('banco_union')" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn-delete" onclick="deleteBank('banco_union')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Banco Ganadero -->
                <div class="bank-item">
                    <div class="bank-name">Banco Ganadero</div>
                    <input type="number" 
                           class="percentage-input" 
                           name="banco_ganadero" 
                           value="27" 
                           min="0" 
                           max="100" 
                           step="1">
                    <div class="action-buttons">
                        <button type="button" class="btn-edit" onclick="editBank('banco_ganadero')" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn-view" onclick="viewBank('banco_ganadero')" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn-delete" onclick="deleteBank('banco_ganadero')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Banco Mercantil -->
                <div class="bank-item">
                    <div class="bank-name">Banco Mercantil</div>
                    <input type="number" 
                           class="percentage-input" 
                           name="banco_mercantil" 
                           value="16" 
                           min="0" 
                           max="100" 
                           step="1">
                    <div class="action-buttons">
                        <button type="button" class="btn-edit" onclick="editBank('banco_mercantil')" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn-view" onclick="viewBank('banco_mercantil')" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn-delete" onclick="deleteBank('banco_mercantil')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Indicador de total -->
            <div id="total-display">
                Total: 93%
            </div>

            <!-- Botón guardar - IDÉNTICO a la imagen -->
            <button type="submit" class="main-button">
                <i class="fas fa-save"></i>
                Guardar
            </button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
