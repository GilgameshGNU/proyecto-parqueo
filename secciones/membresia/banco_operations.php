<?php
include_once '../../db.php';

// Función para obtener todos los bancos
function obtenerBancos() {
    global $conectador;
    $query = "SELECT * FROM BancoMembresia WHERE Estado = 'Activo' ORDER BY Nombre";
    $resultado = mysqli_query($conectador, $query);
    
    if (!$resultado) {
        return false;
    }
    
    $bancos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $bancos[] = $fila;
    }
    
    return $bancos;
}

// Función para obtener un banco por ID
function obtenerBancoPorId($id) {
    global $conectador;
    $id = mysqli_real_escape_string($conectador, $id);
    $query = "SELECT * FROM BancoMembresia WHERE IdBanco = '$id' AND Estado = 'Activo'";
    $resultado = mysqli_query($conectador, $query);
    
    if (!$resultado) {
        return false;
    }
    
    return mysqli_fetch_assoc($resultado);
}

// Función para agregar un nuevo banco
function agregarBanco($nombre, $porcentaje) {
    global $conectador;
    
    $nombre = mysqli_real_escape_string($conectador, $nombre);
    $porcentaje = mysqli_real_escape_string($conectador, $porcentaje);
    
    // Validar que el porcentaje sea válido
    if ($porcentaje < 0 || $porcentaje > 100) {
        return array('success' => false, 'message' => 'El porcentaje debe estar entre 0 y 100');
    }
    
    // Verificar que el total no exceda 100%
    $query_total = "SELECT SUM(Porcentaje) as total FROM BancoMembresia WHERE Estado = 'Activo'";
    $resultado_total = mysqli_query($conectador, $query_total);
    $total_actual = mysqli_fetch_assoc($resultado_total)['total'] ?? 0;
    
    if (($total_actual + $porcentaje) > 100) {
        return array('success' => false, 'message' => 'El total de porcentajes no puede exceder 100%');
    }
    
    $query = "INSERT INTO BancoMembresia (Nombre, Porcentaje) VALUES ('$nombre', '$porcentaje')";
    
    if (mysqli_query($conectador, $query)) {
        return array('success' => true, 'message' => 'Banco agregado exitosamente', 'id' => mysqli_insert_id($conectador));
    } else {
        return array('success' => false, 'message' => 'Error al agregar banco: ' . mysqli_error($conectador));
    }
}

// Función para actualizar un banco
function actualizarBanco($id, $nombre, $porcentaje) {
    global $conectador;
    
    $id = mysqli_real_escape_string($conectador, $id);
    $nombre = mysqli_real_escape_string($conectador, $nombre);
    $porcentaje = mysqli_real_escape_string($conectador, $porcentaje);
    
    // Validar que el porcentaje sea válido
    if ($porcentaje < 0 || $porcentaje > 100) {
        return array('success' => false, 'message' => 'El porcentaje debe estar entre 0 y 100');
    }
    
    // Verificar que el total no exceda 100% (excluyendo el banco actual)
    $query_total = "SELECT SUM(Porcentaje) as total FROM BancoMembresia WHERE Estado = 'Activo' AND IdBanco != '$id'";
    $resultado_total = mysqli_query($conectador, $query_total);
    $total_actual = mysqli_fetch_assoc($resultado_total)['total'] ?? 0;
    
    if (($total_actual + $porcentaje) > 100) {
        return array('success' => false, 'message' => 'El total de porcentajes no puede exceder 100%');
    }
    
    $query = "UPDATE BancoMembresia SET Nombre = '$nombre', Porcentaje = '$porcentaje' WHERE IdBanco = '$id'";
    
    if (mysqli_query($conectador, $query)) {
        return array('success' => true, 'message' => 'Banco actualizado exitosamente');
    } else {
        return array('success' => false, 'message' => 'Error al actualizar banco: ' . mysqli_error($conectador));
    }
}

// Función para eliminar un banco (marcar como inactivo)
function eliminarBanco($id) {
    global $conectador;
    
    $id = mysqli_real_escape_string($conectador, $id);
    $query = "UPDATE BancoMembresia SET Estado = 'Inactivo' WHERE IdBanco = '$id'";
    
    if (mysqli_query($conectador, $query)) {
        return array('success' => true, 'message' => 'Banco eliminado exitosamente');
    } else {
        return array('success' => false, 'message' => 'Error al eliminar banco: ' . mysqli_error($conectador));
    }
}

// Función para obtener el total de porcentajes
function obtenerTotalPorcentajes() {
    global $conectador;
    $query = "SELECT SUM(Porcentaje) as total FROM BancoMembresia WHERE Estado = 'Activo'";
    $resultado = mysqli_query($conectador, $query);
    
    if (!$resultado) {
        return 0;
    }
    
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total'] ?? 0;
}

// Manejar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = array();
    
    switch ($action) {
        case 'agregar':
            $nombre = $_POST['nombre'] ?? '';
            $porcentaje = $_POST['porcentaje'] ?? 0;
            
            if (empty($nombre)) {
                $response = array('success' => false, 'message' => 'El nombre del banco es requerido');
            } else {
                $response = agregarBanco($nombre, $porcentaje);
            }
            break;
            
        case 'actualizar':
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $porcentaje = $_POST['porcentaje'] ?? 0;
            
            if (empty($id) || empty($nombre)) {
                $response = array('success' => false, 'message' => 'ID y nombre del banco son requeridos');
            } else {
                $response = actualizarBanco($id, $nombre, $porcentaje);
            }
            break;
            
        case 'eliminar':
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                $response = array('success' => false, 'message' => 'ID del banco es requerido');
            } else {
                $response = eliminarBanco($id);
            }
            break;
            
        case 'obtener':
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                $response = array('success' => false, 'message' => 'ID del banco es requerido');
            } else {
                $banco = obtenerBancoPorId($id);
                if ($banco) {
                    $response = array('success' => true, 'data' => $banco);
                } else {
                    $response = array('success' => false, 'message' => 'Banco no encontrado');
                }
            }
            break;
            
        case 'listar':
            $bancos = obtenerBancos();
            $total = obtenerTotalPorcentajes();
            $response = array('success' => true, 'data' => $bancos, 'total' => $total);
            break;
            
        default:
            $response = array('success' => false, 'message' => 'Acción no válida');
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
