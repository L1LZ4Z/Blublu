<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, OPTIONS');
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null; // Parámetro opcional user_id

    try {
        $query = "SELECT * FROM Products WHERE 1=1";

        if ($name) {
            $query .= " AND name LIKE :name";
            $params[':name'] = "%" . $name . "%";
        }

        if ($user_id !== null) {
            $query .= " AND user_id = :user_id";
            $params[':user_id'] = $user_id;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            http_response_code(404); // No se encontraron productos
            echo json_encode(["error" => "No se encontraron productos que coincidan con los criterios de búsqueda."]);
            exit();
        }

        echo json_encode($products);

    } catch (PDOException $e) {
        http_response_code(500); // Error interno del servidor
        echo json_encode(['error' => 'Error en la búsqueda de productos: ' . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no permitido."]);
}
?>