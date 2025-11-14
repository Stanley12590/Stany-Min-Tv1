<?php include 'common/header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (!$action || !$id) {
    header('Location: live_channels.php');
    exit;
}

// Get channel data
$channel = supabaseFetch('live_channels', ['id' => 'eq.' . $id])[0] ?? null;
if (!$channel) {
    header('Location: live_channels.php');
    exit;
}

$categories = supabaseFetch('categories');

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'edit') {
        $update_data = [
            'name' => $_POST['name'],
            'poster_url' => $_POST['poster_url'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'watch_link' => $_POST['watch_link']
        ];
        
        $result = supabaseUpdate('live_channels', $id, $update_data);
        if ($result) {
            $success = "Channel updated successfully!";
            $channel = array_merge($channel, $update_data);
        } else {
            $error = "Failed to update channel!";
        }
    }
} elseif ($action === 'delete' && isset($_GET['confirm'])) {
    $result = supabaseDelete('live_channels', $id);
    if ($result) {
        header('Location: live_channels.php?deleted=true');
        exit;
    } else {
        $error = "Failed to delete channel!";
    }
}
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">
        <?php echo $action === 'edit' ? 'Edit Live Channel' : 'Delete Live Channel'; ?>
    </h2>
    <p class="text-gray-400">
        <?php echo $action === 'edit' ? 'Update channel information' : 'Permanently delete this channel'; ?>
    </p>
</div>

<?php if(isset($success)): ?>
<div class="bg-green-500 text-white p-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
</div>
<?php endif; ?>

<?php if(isset($error)): ?>
<div class="bg-red-500 text-white p-3 rounded mb-6">
    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
</div>
<?php endif; ?>

<?php if ($action === 'edit'): ?>
<div class="bg-gray-800 rounded-lg p-4">
    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-gray-300 mb-2">Channel Name *</label>
                <input type="text" name="name" value="<?php echo $channel['name']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Poster URL *</label>
                <input type="url" name="poster_url" value="<?php echo $channel['poster_url']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Description *</label>
                <textarea name="description" rows="3" required 
                          class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"><?php echo $channel['description']; ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Category *</label>
                <select name="category_id" required 
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $channel['category_id'] ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Stream URL *</label>
                <input type="url" name="watch_link" value="<?php echo $channel['watch_link']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Update Channel
            </button>
            <a href="live_channels.php" 
               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
        </div>
    </form>
</div>

<?php elseif ($action === 'delete'): ?>
<div class="bg-red-900 border border-red-700 rounded-lg p-6 text-center">
    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
    <h3 class="text-xl font-bold mb-2">Confirm Deletion</h3>
    <p class="text-red-200 mb-6">Are you sure you want to delete "<strong><?php echo $channel['name']; ?></strong>"? This action cannot be undone.</p>
    
    <div class="flex space-x-3">
        <a href="?action=delete&id=<?php echo $id; ?>&confirm=true" 
           class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-trash mr-2"></i>Yes, Delete
        </a>
        <a href="live_channels.php" 
           class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-times mr-2"></i>Cancel
        </a>
    </div>
</div>
<?php endif; ?>

<?php include 'common/footer.php'; ?>
