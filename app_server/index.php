<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blublu</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <header>
        <h1>Bienvenido a Blublu</h1>
        <nav>
            <ul>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="register.php">Registrarse</a></li>
                <li><a href="products.php">Ver Productos</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="add_product.php">Agregar Producto</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Productos destacados</h2>
                <!--Espacio para incluir consultas a futuro-->
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Blublu</p>
    </footer>

</body>
</html>
