<?php
ob_start();
require_once '../config.php';
requireAuth();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $poster_url = $_POST['poster_url'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $watch_link = $_POST['watch_link'] ?? '';
    
    if (!empty($name)) {
        $data = [
            'name' => $name,
            'poster_url' => $poster_url,
            'description' => $description,
            'category_id' => $category_id,
            'watch_link' => $watch_link,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if (supabaseInsert('Live_Channels', $data)) {
            $_SESSION['message'] = 'Channel added successfully!';
            header('Location: live_channels.php');
            exit;
        } else {
            $error = 'Failed to add channel!';
        }
    } else {
        $error = 'Channel name is required!';
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (supabaseDelete('Live_Channels', $id)) {
        $_SESSION['message'] = 'Channel deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete channel!';
    }
    header('Location: live_channels.php');
    exit;
}

// Get all channels and categories
$channels = supabaseFetch('Live_Channels', ['order' => 'created_at.desc']);
$categories = supabaseFetch('Categories', ['order' => 'category_name.asc']);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stany Min TV - Live Channels</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: white; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .header h1 { color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .form-container { background: white; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stany Min TV - Live Channels</h1>
        <div>
            <a href="index.php">Dashboard</a> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <!-- Add Channel Form -->
        <div class="form-container">
            <h2>Add New Channel</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Channel Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="poster_url">Poster URL:</label>
                    <input type="text" id="poster_url" name="poster_url">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Category:</label>
                    <select id="category_id" name="category_id">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="watch_link">Watch Link:</label>
                    <input type="text" id="watch_link" name="watch_link">
                </div>
                <button type="submit" class="btn">Add Channel</button>
            </form>
        </div>
        
        <!-- Channels List -->
        <h2>All Live Channels</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($channels)): ?>
                    <tr><td colspan="3" style="text-align: center;">No channels found</td></tr>
                <?php else: ?>
                    <?php foreach ($channels as $channel): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($channel['name'] ?? ''); ?></td>
                        <td>
                            <?php 
                            $cat_name = 'Unknown';
                            foreach ($categories as $cat) {
                                if ($cat['id'] == $channel['category_id']) {
                                    $cat_name = $cat['category_name'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($cat_name);
                            ?>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?php echo $channel['id']; ?>" class="btn">Edit</a>
                            <a href="?delete=<?php echo $channel['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Delete this channel?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($channels)): ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-tower-broadcast text-4xl mb-2"></i>
            <p>No live channels added yet</p>
            <p class="text-sm mt-2">Add your first channel using the form above</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
