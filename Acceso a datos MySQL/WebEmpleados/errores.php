<?php

function mostrarError($error)
{
    $error = $error->getCode();
    switch ($error) {
        case 1048:
            return "Error: Faltan datos obligatorios.";
        case 1062:
            return "Error: Duplicado de clave primaria.";
        case 2002:
            return "Error: No se puede conectar al servidor de base de datos.";
        case 1049:
            return "Error: Base de datos desconocida.";
        case 1146:
            return "Error: Tabla desconocida.";
        case 1054:
            return "Error: Columna desconocida.";
        case 1366:
            return "Error: Valor incorrecto para columna.";
        case 1452:
            return "Error: Violación de clave foránea.";
        case 1451:
            return "Error: No se puede eliminar o actualizar debido a clave foránea.";
        case 1216:
            return "Error: Registro padre no encontrado para clave foránea.";
        case 1217:
            return "Error: No se puede eliminar registro padre debido a clave foránea.";
        case 1292:
            return "Error: Valor de fecha/hora incorrecto.";
        case 23000:
            return "Error: Violación de restricción de integridad.";
        default:
            return "Error desconocido. Código: " . $error;
    }
}
