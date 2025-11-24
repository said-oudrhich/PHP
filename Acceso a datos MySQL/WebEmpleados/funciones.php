<?php

/* Conexión a bd empleados1n - mysql PDO */
function conectarBD()
{
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "empleados1n";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

/*********************************************************************************************************************************************/

// Limpiar datos de entrada para evitar inyecciones XSS y otros problemas
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/

// Insertar nuevo departamento en la base de datos
function insertarDepartamento($conexion, $nombre, $cod_dpto)
{
    $sql = "INSERT INTO departamento (cod_dpto, nombre_dpto) 
            VALUES (:cod_dpto, :nombre_dpto)";
    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(":cod_dpto", $cod_dpto);
    $stmt->bindParam(":nombre_dpto", $nombre);

    $stmt->execute();
}

// Generar nuevo código de departamento
function generarNuevoCod_dpto($conexion)
{
    $sql = "SELECT MAX(cod_dpto) AS max_cod FROM departamento";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado && $resultado['max_cod']) {
        $num = intval(substr($resultado['max_cod'], 1)) + 1;
    } else {
        $num = 1;
    }

    return "D" . str_pad($num, 3, "0", STR_PAD_LEFT);
}

// Verificar si el departamento ya existe devueklve true si existe, false si no existe
function departamentoExiste($conexion, $nombre)
{
    $sql = "SELECT COUNT(*) FROM departamento WHERE nombre_dpto = :nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

/*********************************************************************************************************************************************/

function obtenerDepartamentos($conexion)
{
    $sql = "SELECT cod_dpto, nombre_dpto FROM departamento ORDER BY nombre_dpto";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $departamentos;
}

function insertarEmpleado($conexion, $dni, $nombre, $apellidos, $fecha_nac, $salario, $cod_dpto)
{
    $sql = "INSERT INTO empleado (dni, nombre, apellidos, fecha_nac, salario, cod_dpto) 
                VALUES (:dni, :nombre, :apellidos, :fecha_nac, :salario, :cod_dpto)";
    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(":dni", $dni);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":apellidos", $apellidos);
    $stmt->bindParam(":fecha_nac", $fecha_nac);
    $stmt->bindParam(":salario", $salario);
    $stmt->bindParam(":cod_dpto", $cod_dpto);
    $stmt->execute();
}

/*********************************************************************************************************************************************/
