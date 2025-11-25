<?php
function mostrarError($error, $contexto = [])
{
    $codigo = $error->getCode();
    $tipo = $contexto['tipo'] ?? '';
    $valor = $contexto['valor'] ?? '';
    $columna = $contexto['columna'] ?? '';

    switch ($codigo) {
        case 1048:
            return "Error: Faltan datos obligatorios" . ($tipo ? " para '$tipo'." : ".");
        case 1062:
            return "Error: Duplicado de clave primaria"
                . ($tipo && $valor ? " en '$tipo' con valor '$valor'." : ".");
        case 22001:
            return "Error: El valor es demasiado largo para la columna"
                . ($tipo && $columna ? " '$columna' en '$tipo'." : ($tipo ? " en '$tipo'." : "."));
        case 2002:
            return "Error: No se puede conectar al servidor de base de datos.";
        case 1049:
            return "Error: Base de datos desconocida.";
        case 1146:
            return "Error: Tabla desconocida" . ($tipo ? " '$tipo'." : ".");
        case 1054:
            return "Error: Columna desconocida" . ($columna ? " '$columna'." : ".");
        case 1366:
            return "Error: Valor incorrecto para columna"
                . ($columna && $valor ? " '$columna' con valor '$valor'." : ".");
        case 1452:
            return "Error: Violación de clave foránea"
                . ($tipo && $valor ? " en '$tipo' con valor '$valor'." : ".");
        case 1451:
            return "Error: No se puede eliminar o actualizar debido a clave foránea"
                . ($tipo ? " en '$tipo'." : ".");
        case 1216:
            return "Error: Registro padre no encontrado para clave foránea"
                . ($tipo && $valor ? " en '$tipo' con valor '$valor'." : ".");
        case 1217:
            return "Error: No se puede eliminar registro padre debido a clave foránea"
                . ($tipo && $valor ? " en '$tipo' con valor '$valor'." : ".");
        case 1292:
            return "Error: Valor de fecha/hora incorrecto"
                . ($columna && $valor ? " en '$columna' con valor '$valor'." : ".");
        case 23000:
            if ($tipo && $columna && $valor) {
                return "Error: Violación de restricción de integridad en '$tipo', columna '$columna' con valor '$valor'.";
            } elseif ($tipo && $valor) {
                return "Error: Violación de restricción de integridad en '$tipo' con valor '$valor'.";
            } else {
                return "Error: Violación de restricción de integridad.";
            }
        default:
            return "Error desconocido. Código: " . $codigo;
    }
}
