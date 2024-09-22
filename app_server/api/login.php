<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Asegúrate de incluir OPTIONS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data === null) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Error en los datos de entrada."]);
        exit();
    }

    $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Por favor, rellena todos los campos."]);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "El correo electrónico no es válido."]);
        exit();
    }

    try {
        $sql = "SELECT id, name, password, role_id FROM Users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['loggedin'] = true;

                http_response_code(200); // OK
                echo json_encode([
                    "success" => "Inicio de sesión exitoso.",
                    "user" => [
                        "name" => $user['name'],
                        "role_id" => $user['role_id'],
                        "id" => $user['id']
                    ]
                ]);
            } else {
                echo json_encode(["error" => "La contraseña es incorrecta."]);
            } 
} else {
            http_response_code(401); // Unauthorized
            echo json_encode(["error" => "No se ha encontrado una cuenta con ese correo electrónico."]);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error en la conexión a la base de datos."]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido."]);
}
?>