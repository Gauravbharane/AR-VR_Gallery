<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ar_vr_site');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Operation for Products and Users
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $type = $_GET['type'];
    $table = $type === 'user' ? 'users' : 'products';
    $sql = "DELETE FROM $table WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error deleting $type: " . $conn->error;
    }
}

// Handle Add/Edit Operations for Products and Users
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['type'] === 'product') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $model_link = $_POST['model_link'];

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $id = intval($_POST['id']);
            $sql = "UPDATE products SET name='$name', description='$description', model_link='$model_link' WHERE id=$id";
        } else {
            $sql = "INSERT INTO products (name, description, model_link) VALUES ('$name', '$description', '$model_link')";
        }
    } else if ($_POST['type'] === 'user') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $id = intval($_POST['id']);
            $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        }
    }

    if ($conn->query($sql)) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch Products and Users
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .modal-dialog {
            max-width: 500px;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border: none;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Products</a>
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

    <div class="container">
        <h1 class="text-center mb-4">Admin Dashboard</h1>
        
        <div class="row">
            <!-- Product Section -->
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h3>Products</h3>
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#productModal">Add Product</button>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Model Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['description']) ?></td>
                                    <td><a href="<?= htmlspecialchars($product['model_link']) ?>" target="_blank">View</a></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-description="<?= htmlspecialchars($product['description']) ?>" data-model="<?= htmlspecialchars($product['model_link']) ?>" data-bs-toggle="modal" data-bs-target="#productModal">Edit</button>
                                        <a href="?delete=<?= $product['id'] ?>&type=product" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- User Section -->
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h3>Users</h3>
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#userModal">Add User</button>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn-user" data-id="<?= $user['id'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>" data-email="<?= htmlspecialchars($user['email']) ?>" data-bs-toggle="modal" data-bs-target="#userModal">Edit</button>
                                        <a href="?delete=<?= $user['id'] ?>&type=user" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <input type="hidden" name="type" value="product">
                    <input type="hidden" id="productId" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Add/Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" id="productName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea id="productDescription" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Model Link</label>
                            <input type="url" id="productModel" name="model_link" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <input type="hidden" name="type" value="user">
                    <input type="hidden" id="userId" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Add/Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" id="userName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" id="userEmail" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" id="userPassword" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
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
        // Pre-fill Product Form for Editing
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('productId').value = button.dataset.id;
                document.getElementById('productName').value = button.dataset.name;
                document.getElementById('productDescription').value = button.dataset.description;
                document.getElementById('productModel').value = button.dataset.model;
            });
        });

        // Pre-fill User Form for Editing
        document.querySelectorAll('.edit-btn-user').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('userId').value = button.dataset.id;
                document.getElementById('userName').value = button.dataset.name;
                document.getElementById('userEmail').value = button.dataset.email;
            });
        });
    </script>

</body>
</html>
