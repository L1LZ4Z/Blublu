<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Asegúrate de incluir OPTIONS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
    $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
    $in_stock = isset($_GET['in_stock']) ? (int)$_GET['in_stock'] : null;

    try {
        $query = "SELECT * FROM Products WHERE 1=1";
        $params = [];

        if ($name) {
            $query .= " AND name LIKE :name";
            $params[':name'] = "%" . $name . "%";
        }

        if ($min_price !== null) {
            $query .= " AND price >= :min_price";
            $params[':min_price'] = $min_price;
        }

        if ($max_price !== null) {
            $query .= " AND price <= :max_price";
            $params[':max_price'] = $max_price;
        }

        if ($in_stock !== null) {
            $query .= " AND stock >= :in_stock";
            $params[':in_stock'] = $in_stock;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            http_response_code(404); // Not Found
            echo json_encode(["error" => "No se encontraron productos que coincidan con los criterios de búsqueda."]);
            exit();
        }

        echo json_encode($products);

    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Error en la búsqueda de productos: ' . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido."]);
}
?>