<?php
ob_start();
require_once '../config.php';
requireAuth();

// Handle actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// Delete post
if ($action === 'delete' && $id) {
    if (supabaseDelete('posts', $id)) {
        $_SESSION['message'] = 'Post deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete post!';
    }
    header('Location: posts.php');
    exit;
}

// Get all posts
$posts = supabaseFetch('posts', ['order' => 'created_at.desc']);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nipe Admin - Posts</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Posts</h1>
        <div class="user-info">
            <a href="index.php">Dashboard</a> | 
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
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
        
        <div class="action-bar">
            <h2>All Posts</h2>
            <a href="?action=create" class="btn">Create New Post</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No posts found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title'] ?? ''); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'] ?? '')); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                            <a href="?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
