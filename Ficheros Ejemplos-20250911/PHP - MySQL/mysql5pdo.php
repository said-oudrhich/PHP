<?php
/*SELECTs - mysql PDO*/

$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "empleados1n";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT cod_dpto, nombre_dpto FROM departamento WHERE cod_dpto = :cod");
    $stmt->bindParam(':cod', $cod);
    $cod = 'D002';
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $resultado = $stmt->fetchAll();
    foreach ($resultado as $row) {
        echo "El departamento buscado es Codigo dpto: " . $row["cod_dpto"] . " - Nombre: " . $row["nombre_dpto"] . "<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
