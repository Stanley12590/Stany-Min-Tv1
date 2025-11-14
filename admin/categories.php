<?php
// Start output buffering at the VERY beginning
ob_start();

// Include config first to get all functions
require_once '../config.php';

// Check authentication
requireAuth();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (!empty($name)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if (supabaseInsert('categories', $data)) {
            $_SESSION['message'] = 'Category added successfully!';
            header('Location: categories.php');
            exit;
        } else {
            $error = 'Failed to add category!';
        }
    } else {
        $error = 'Category name is required!';
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (supabaseDelete('categories', $id)) {
        $_SESSION['message'] = 'Category deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete category!';
    }
    header('Location: categories.php');
    exit;
}

// Get all categories
$categories = supabaseFetch('categories', ['order' => 'name.asc']);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stany Min TV - Categories</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
        }
        .user-info {
            color: #666;
        }
        .logout {
            color: #c33;
            text-decoration: none;
            margin-left: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-danger {
            background: #dc3545;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stany Min TV - Categories</h1>
        <div class="user-info">
            <a href="index.php">Dashboard</a> | 
            Welcome, Admin
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Add Category Form -->
        <div class="form-container">
            <h2>Add New Category</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Add Category</button>
            </form>
        </div>
        
        <!-- Categories List -->
        <div class="action-bar">
            <h2>All Categories</h2>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No categories found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($category['created_at'] ?? '')); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $category['id']; ?>" class="btn">Edit</a>
                            <a href="?delete=<?php echo $category['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
