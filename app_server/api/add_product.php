<?php

header('Content-Type: application/json');
include '../includes/db.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data === null) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Error en los datos de entrada."]);
        exit();
    }

    $user_id = $data['user_id'];
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $stock = $data['stock'];

    if (empty($user_id) || empty($name) || empty($description) || empty($price) || empty($stock)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Por favor, rellena todos los campos."]);
        exit();
    }

    try {
        $pdo->beginTransaction();
    
        $stmt = $pdo->prepare('INSERT INTO Products (user_id, name, description, price, stock) VALUES (:user_id, :name, :description, :price, :stock)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->execute();
    
        $pdo->commit();
        echo json_encode(['success' => 'Producto agregado con éxito']);
    
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['error' => 'Error al agregar el producto: ' . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido."]);
}

?>