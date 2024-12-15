<?php
session_start(); // Start the session to track login state

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ar_vr_site');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for login error message
$error_message = '';

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the credentials
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password (assumes password is hashed)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session
            header('Location: admin.php');  // Redirect to the admin page
            exit();
        } else {
            $error_message = 'Invalid email or password.';
        }
    } else {
        $error_message = 'No user found with that email address.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR/VR Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@google/model-viewer@latest/dist/model-viewer.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <!-- Navbar -->
      
    <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">AR/VR Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#products">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://linkedin.com/in/gaurav-bharane">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to AR/VR Store</h1>
        <p>Explore the latest AR/VR products to enhance your experiences.</p>
        <a href="#products" class="btn btn-primary">Explore Products</a>
    </div>

    <!-- Products Section -->
    <div class="container my-5" id="products">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Our Products</h2>
            <select id="categoryFilter" class="form-select" style="width: 200px;">
                <option value="all">All Categories</option>
                <option value="vr">VR</option>
                <option value="ar">AR</option>
            </select>
        </div>

        <div class="product-gallery" id="productGallery">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            
            // Check for query error
            if ($result === false) {
                die("Error executing query: " . $conn->error);
            }
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card" data-category="' . htmlspecialchars($row['category']) . '">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                            <p class="card-text">' . htmlspecialchars($row['description']) . '</p>
                            <div class="model-viewer-container mb-3">
                                <model-viewer src="' . htmlspecialchars($row['model_link']) . '" alt="3D model" auto-rotate camera-controls ar></model-viewer>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No products available</p>';
            }
            ?>
        </div>
    </div>

    <!-- Login Modal -->

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- This class centers the modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center text-muted py-3 border-top">
    <p class="mb-0">
        This site has been created by 
        <a href="https://github.com/gauravbharane" target="_blank" class="text-primary text-decoration-none">
            @gauravbharane
        </a>
    </p>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter products by category
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const category = this.value;
            const products = document.querySelectorAll('.product-gallery .card');

            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
