<?php

/* Conexión a bd empleados - mysql PDO */
function conectarBD()
{
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "empleados";

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

    if ($resultado['max_cod']) {
        $num = intval(substr($resultado['max_cod'], 1)) + 1;
    } else {
        $num = 1;
    }

    return "D" . str_pad($num, 3, "0", STR_PAD_LEFT);
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

function empleadoExiste($conexion, $dni)
{
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM empleado WHERE dni = :dni");
    $stmt->bindParam(":dni", $dni);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

function insertarEmpleado($conexion, $dni, $nombre, $apellidos, $fecha_nac, $salario)
{
    $sql = "INSERT INTO empleado (dni, nombre, apellidos, fecha_nac, salario) 
            VALUES (:dni, :nombre, :apellidos, :fecha_nac, :salario)";
    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(":dni", $dni);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":apellidos", $apellidos);
    $fecha_nac = date('Y-m-d', strtotime($fecha_nac));
    $stmt->bindParam(":fecha_nac", $fecha_nac);
    $stmt->bindParam(":salario", $salario);
    $stmt->execute();
}

function insertarEmpleadoDepartamento($conexion, $dni, $cod_dpto)
{
    $sql = "INSERT INTO emple_depart (dni, cod_dpto, fecha_ini, fecha_fin) 
                VALUES (:dni, :cod_dpto, :fecha_ini, NULL)";
    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(":dni", $dni);
    $stmt->bindParam(":cod_dpto", $cod_dpto);
    $fecha_ini = date('Y-m-d');
    $stmt->bindParam(":fecha_ini", $fecha_ini);
    $stmt->execute();
}

/*********************************************************************************************************************************************/
