<?php
ob_start();
require_once '../config.php';
requireAuth();

// Get all users
$users = supabaseFetch('users', ['order' => 'created_at.desc']);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nipe Admin - Users</title>
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
            justify-content: between;
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
        .btn {
            background: #667eea;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-danger {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Users</h1>
        <div class="user-info">
            <a href="index.php">Dashboard</a> | 
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No users found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'] ?? '')); ?></td>
                        <td>
                            <a href="#" class="btn">Edit</a>
                            <a href="#" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
