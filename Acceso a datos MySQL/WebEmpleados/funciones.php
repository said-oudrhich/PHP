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
// empaltadpto.php
/*Programar un formulario empaltadpto.html/empaltadpto.php que permita dar de alta 
departamentos. El código del departamento tendrá el formato DxxxN (‘D001’, ‘D002’ …) y se 
obtendrá automáticamente. */

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
// empaltaemp.php
/*Realizar un programa en php empaltaemp.php que permita dar de alta un empleado en la 
empresa. Para seleccionar el departamento, al que se asignará al empleado inicialmente, se 
utilizará una lista de valores con los nombres de los departamentos de la empresa. */

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
// empcambiodpto.php

/*Realizar un programa en php empcambiodpto.php que permita seleccionar el DNI de un 
empleado de una lista desplegable y permita asignarlo a un nuevo departamento. Este nuevo 
departamento se obtendrá también de un desplegable.  */

function obtenerEmpleados($conexion)
{
    $sql = "SELECT dni, nombre, apellidos FROM empleado ORDER BY apellidos, nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $empleados;
}

// Obtener los depertamentos ya esta creado arriba

function cambiarDepartamentoEmpleado($conexion, $dni, $cod_dpto)
{
    // Finalizar el departamento actual del empleado
    $sqlFin = "UPDATE emple_depart 
               SET fecha_fin = :fecha_fin 
               WHERE dni = :dni AND fecha_fin IS NULL";
    $stmtFin = $conexion->prepare($sqlFin);
    $fecha_fin = date('Y-m-d');
    $stmtFin->bindParam(":fecha_fin", $fecha_fin);
    $stmtFin->bindParam(":dni", $dni);
    $stmtFin->execute();

    // Asignar el nuevo departamento al empleado
    $sqlNuevo = "INSERT INTO emple_depart (dni, cod_dpto, fecha_ini, fecha_fin) 
                 VALUES (:dni, :cod_dpto, :fecha_ini, NULL)";
    $stmtNuevo = $conexion->prepare($sqlNuevo);
    $fecha_ini = date('Y-m-d');
    $stmtNuevo->bindParam(":dni", $dni);
    $stmtNuevo->bindParam(":cod_dpto", $cod_dpto);
    $stmtNuevo->bindParam(":fecha_ini", $fecha_ini);
    $stmtNuevo->execute();
}
