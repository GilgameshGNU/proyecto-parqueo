# Sección de Membresía - Sistema de Parqueo

## Descripción
Esta sección permite gestionar las membresías bancarias del sistema de parqueo, configurando los porcentajes de descuento para diferentes bancos.

##Funcionalidades

### ✏️ **Editar Membresía** (`index.php`)
- **Formulario de edición** con campos para cada banco
- **Validación en tiempo real** de porcentajes
- **Botones de acción** para cada banco:
  - 🟢 **Editar**: Enfoca el campo de entrada
  - 🟡 **Ver**: Muestra el valor actual
  - 🔴 **Eliminar**: Establece el valor a 0
- **Botón Guardar** para persistir cambios

### 👁️ **Ver Membresía** (`ver.php`)
- **Vista de solo lectura** de los porcentajes
- **Datos dinámicos** desde la base de datos
- **Botón Editar** para ir al modo de edición
- **Botón Volver al menú** para regresar

## 🗄️ Base de Datos

### Tabla: `membresia_bancos`
```sql
CREATE TABLE membresia_bancos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    porcentaje INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Bancos por defecto:
- **Banco Unión**: 50%
- **Banco Ganadero**: 27%
- **Banco Mercantil**: 16%

## 🎨 Diseño y Estilos

### Características del diseño:
- **Diseño moderno** con gradientes y efectos glassmorphism
- **Responsive** para dispositivos móviles y desktop
- **Animaciones suaves** con CSS transitions
- **Iconos FontAwesome** para mejor UX
- **Colores consistentes** con el sistema

### Archivos CSS:
- `../../assets/css/styles.css` - Estilos principales
- Separación completa de HTML y CSS
- Variables CSS para fácil personalización

## 🔧 Instalación y Uso

### Requisitos:
- PHP 8.0+
- MySQL 8.0+
- Servidor web (Apache/Nginx) o servidor PHP integrado

### Configuración:
1. **Base de datos**: Asegúrate de que `db.php` esté configurado
2. **Permisos**: Verifica permisos de escritura en la carpeta
3. **Servidor**: Ejecuta `php -S localhost:8000`

### Acceso:
- **Editar**: `http://localhost:8000/secciones/membresia/`
- **Ver**: `http://localhost:8000/secciones/membresia/ver.php`

## 📱 Responsive Design

### Breakpoints:
- **Desktop**: > 768px - Layout horizontal
- **Mobile**: ≤ 768px - Layout vertical centrado

### Características móviles:
- Botones adaptados al tamaño de pantalla
- Navegación optimizada para touch
- Texto legible en dispositivos pequeños

## 🚀 Funcionalidades Avanzadas

### Validaciones:
- **Porcentaje máximo**: 100%
- **Porcentaje mínimo**: 0%
- **Validación en tiempo real** del total
- **Mensajes de error** informativos

### Seguridad:
- **Escape de datos** para prevenir XSS
- **Validación del lado del servidor**
- **Sanitización de entradas**

## 🔄 Flujo de Trabajo

1. **Usuario accede** a la sección de membresía
2. **Edita porcentajes** usando el formulario
3. **Valida datos** en tiempo real
4. **Guarda cambios** en la base de datos
5. **Redirige** a la vista de solo lectura
6. **Confirma** que los cambios se guardaron

## 🐛 Solución de Problemas

### Problemas comunes:
- **Error de conexión DB**: Verifica `db.php`
- **CSS no carga**: Verifica ruta `assets/css/styles.css`
- **Formulario no envía**: Verifica permisos de escritura

### Logs:
- Revisa la consola del navegador para errores JavaScript
- Verifica logs del servidor PHP para errores del servidor

## 📈 Mejoras Futuras

### Funcionalidades sugeridas:
- **Historial de cambios** con timestamps
- **Exportar datos** a PDF/Excel
- **Múltiples tipos de membresía**
- **Notificaciones** por email/SMS
- **Dashboard** con estadísticas

### Optimizaciones técnicas:
- **Caché** de consultas frecuentes
- **API REST** para integración externa
- **Tests automatizados** con PHPUnit
- **Docker** para desarrollo

## 👥 Contribución

### Estándares de código:
- **PSR-12** para estilo de código PHP
- **BEM** para metodología CSS
- **Comentarios** en español para consistencia
- **Nombres descriptivos** para variables y funciones

