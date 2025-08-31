<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Parqueo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .status {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            text-align: center;
        }
        .sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .section-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .section-card h3 {
            color: #007bff;
            margin-top: 0;
        }
        .section-card a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .section-card a:hover {
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚗 Sistema de Parqueo</h1>
        
        <div class="status">
            ✅ Servidor web funcionando correctamente en puerto 8000<br>
            ✅ Base de datos MySQL conectada exitosamente<br>
            ✅ PHP 8.3.6 ejecutándose
        </div>

        <div class="sections">
            <div class="section-card">
                <h3>👥 Gestión de Clientes</h3>
                <p>Administra la información de los clientes del parqueo</p>
                <a href="secciones/cliente/">Ver Clientes →</a>
            </div>

            <div class="section-card">
                <h3>🚙 Gestión de Vehículos</h3>
                <p>Gestiona los vehículos registrados en el sistema</p>
                <a href="secciones/vehiculo/">Ver Vehículos →</a>
            </div>

            <div class="section-card">
                <h3>🅿️ Espacios de Parqueo</h3>
                <p>Administra los espacios disponibles para estacionar</p>
                <a href="secciones/espacio_parqueo/">Ver Espacios →</a>
            </div>

            <div class="section-card">
                <h3>💰 Gestión de Pagos</h3>
                <p>Controla los pagos y tarifas del parqueo</p>
                <a href="secciones/pago/">Ver Pagos →</a>
            </div>

            <div class="section-card">
                <h3>🎫 Registro de Entrada</h3>
                <p>Registra las entradas y salidas de vehículos</p>
                <a href="secciones/registro_entrada/">Ver Registros →</a>
            </div>

            <div class="section-card">
                <h3>💳 Membresías</h3>
                <p>Gestiona las membresías y sus beneficios</p>
                <a href="secciones/membresia/">Ver Membresías →</a>
                <br><br>
                <small style="color: #6c757d;">
                    ✏️ <a href="secciones/membresia/" style="color: #007bff;">Editar</a> | 
                    👁️ <a href="secciones/membresia/ver.php" style="color: #28a745;">Ver</a>
                </small>
            </div>

            <div class="section-card">
                <h3>⚙️ Configuración</h3>
                <p>Administra tarifas, tipos de espacios y vehículos</p>
                <a href="secciones/tarifa/">Configurar →</a>
            </div>

            <div class="section-card">
                <h3>🔐 Acceso al Sistema</h3>
                <p>Inicia sesión en el sistema de parqueo</p>
                <a href="login.php">Iniciar Sesión →</a>
            </div>
        </div>

        <div class="footer">
            <p>Sistema desarrollado por el equipo de desarrollo</p>
            <p>PHP 8.3.6 | MySQL 8.0.43 | Ubuntu 24.04</p>
        </div>
    </div>
</body>
</html>
