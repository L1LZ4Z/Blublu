<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Asegúrate de incluir OPTIONS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
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
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];

    if (empty($user_id) || empty($product_id) || empty($quantity)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Por favor, rellena todos los campos."]);
        exit();
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO Orders (user_id, status_id, order_date, amount) VALUES (:user_id, 1, NOW(), 0)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $order_id = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare('SELECT price FROM Products WHERE id = :product_id');
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            throw new Exception('Producto no encontrado');
        }
        $price = $product['price'];

        $stmt = $pdo->prepare('INSERT INTO Details (product_id, order_id, quantity, price) VALUES (:product_id, :order_id, :quantity, :price)');
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->execute();

        $stmt = $pdo->prepare('SELECT country_id FROM Users WHERE id = :user_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception('Usuario no encontrado');
        }
        $country_id = $user['country_id'];

        $stmt = $pdo->prepare('SELECT tax_rate FROM Taxes WHERE country_id = :country_id');
        $stmt->bindParam(':country_id', $country_id);
        $stmt->execute();
        $tax = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$tax) {
            throw new Exception('No se encontró la tasa de impuestos para el país.');
        }
        $tax_rate = $tax['tax_rate'];

        $base_amount = ($price * $quantity);
        $tax_amount = ($base_amount * $tax_rate) / 100;
        $total_amount = $base_amount + $tax_amount;

        $stmt = $pdo->prepare('UPDATE Orders SET amount = :total_amount WHERE id = :order_id');
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $pdo->commit();

        echo json_encode([
            'success' => 'Pedido creado con éxito',
            'order_id' => $order_id,
            'base_amount' => $base_amount,
            'tax_amount' => $tax_amount,
            'total_amount' => $total_amount
        ]);

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['error' => 'Error al crear el pedido: ' . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido."]);
}
?>
