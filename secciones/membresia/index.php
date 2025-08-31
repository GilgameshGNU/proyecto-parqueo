<?php
include_once '../../db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membresía - Sistema de Parqueo</title>
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
        
        <!-- Formulario de membresía -->
        <div class="content-card">
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
                            <button type="button" class="btn btn-edit" onclick="editBank('banco_union')" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-view" onclick="viewBank('banco_union')" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-delete" onclick="deleteBank('banco_union')" title="Eliminar">
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
                            <button type="button" class="btn btn-edit" onclick="editBank('banco_ganadero')" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-view" onclick="viewBank('banco_ganadero')" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-delete" onclick="deleteBank('banco_ganadero')" title="Eliminar">
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
                            <button type="button" class="btn btn-edit" onclick="editBank('banco_mercantil')" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-view" onclick="viewBank('banco_mercantil')" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-delete" onclick="deleteBank('banco_mercantil')" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Botón guardar -->
                <button type="submit" class="main-button">
                    <i class="fas fa-save"></i>
                    Guardar
                </button>
            </form>
        </div>
    </div>

    <script>
        // Funciones para los botones de acción
        function editBank(bankId) {
            const input = document.querySelector(`input[name="${bankId}"]`);
            input.focus();
            input.select();
        }

        function viewBank(bankId) {
            const input = document.querySelector(`input[name="${bankId}"]`);
            alert(`Valor actual de ${bankId}: ${input.value}%`);
        }

        function deleteBank(bankId) {
            if (confirm('¿Estás seguro de que quieres eliminar este banco?')) {
                const input = document.querySelector(`input[name="${bankId}"]`);
                input.value = '0';
            }
        }

        // Validación del formulario
        document.getElementById('membershipForm').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.percentage-input');
            let total = 0;
            
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            if (total > 100) {
                e.preventDefault();
                alert('El total de porcentajes no puede exceder el 100%');
                return false;
            }
            
            if (total < 0) {
                e.preventDefault();
                alert('Los porcentajes no pueden ser negativos');
                return false;
            }
        });

        // Actualizar total en tiempo real
        document.querySelectorAll('.percentage-input').forEach(input => {
            input.addEventListener('input', updateTotal);
        });

        function updateTotal() {
            const inputs = document.querySelectorAll('.percentage-input');
            let total = 0;
            
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            // Mostrar el total en algún lugar si es necesario
            console.log('Total de porcentajes:', total + '%');
        }
    </script>
</body>
</html>
