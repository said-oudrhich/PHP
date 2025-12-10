<?php
function mostrarError($error, $tipo)
{
    $codigo = $error->getCode();

    // Mensajes base según código
    $mensajes = [
        1048 => "Falta información necesaria",
        1062 => "Este dato ya existe",
        22001 => "El valor ingresado es demasiado largo",
        2002 => "No se puede conectar al servidor",
        1049 => "Base de datos no encontrada",
        1146 => "Tabla no encontrada",
        1054 => "Campo desconocido",
        1366 => "Valor ingresado no es válido",
        1452 => "No se puede guardar: dato relacionado no existe",
        1451 => "No se puede eliminar o modificar: dato relacionado existe",
        1292 => "Fecha u hora inválida",
        23000 => "Error de integridad de datos"
    ];

    $msg = $mensajes[$codigo] ?? "Error desconocido (Código: $codigo) en '$tipo'.";

    return $msg;
}
