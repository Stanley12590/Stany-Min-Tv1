<?php include 'common/header.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $channel_data = [
        'name' => $_POST['name'],
        'poster_url' => $_POST['poster_url'],
        'description' => $_POST['description'],
        'category_id' => $_POST['category_id'],
        'watch_link' => $_POST['watch_link']
    ];
    
    $result = supabaseInsert('live_channels', $channel_data);
    if ($result) {
        $success = "Live Channel added successfully!";
    } else {
        $error = "Failed to add channel!";
    }
}

// Get categories for dropdown
$categories = supabaseFetch('categories');
$channels = supabaseFetch('live_channels', ['order' => 'created_at.desc']);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Live Channels Management</h2>
    <p class="text-gray-400">Add and manage live TV channels</p>
</div>

<!-- Add Channel Form -->
<div class="bg-gray-800 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-bold mb-4">Add New Live Channel</h3>
    
    <?php if(isset($success)): ?>
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
    </div>
    <?php endif; ?>
    
    <?php if(isset($error)): ?>
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-gray-300 mb-2">Channel Name *</label>
                <input type="text" name="name" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                       placeholder="Enter channel name">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Poster URL *</label>
                <input type="url" name="poster_url" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                       placeholder="https://example.com/poster.jpg">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Description *</label>
                <textarea name="description" rows="3" required 
                          class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                          placeholder="Enter channel description"></textarea>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Category *</label>
                <select name="category_id" required 
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Stream URL *</label>
                <input type="url" name="watch_link" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                       placeholder="https://example.com/stream.m3u8">
            </div>
        </div>
        
        <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-broadcast-tower mr-2"></i>Add Live Channel
        </button>
    </form>
</div>

<!-- Channels List -->
<div class="bg-gray-800 rounded-lg p-4">
    <h3 class="text-lg font-bold mb-4">All Live Channels (<?php echo count($channels); ?>)</h3>
    
    <div class="space-y-3">
        <?php foreach($channels as $channel): 
            $category_name = 'Uncategorized';
            foreach($categories as $cat) {
                if($cat['id'] == $channel['category_id']) {
                    $category_name = $cat['category_name'];
                    break;
                }
            }
        ?>
        <div class="flex items-center justify-between bg-gray-700 p-3 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gray-600 rounded flex items-center justify-center">
                    <?php if(!empty($channel['poster_url'])): ?>
                    <img src="<?php echo $channel['poster_url']; ?>" alt="Poster" class="w-full h-full object-cover rounded">
                    <?php else: ?>
                    <i class="fas fa-tower-broadcast text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="font-semibold"><?php echo $channel['name']; ?></h4>
                    <p class="text-gray-400 text-sm"><?php echo $category_name; ?></p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="manage_channel.php?action=edit&id=<?php echo $channel['id']; ?>" 
                   class="bg-yellow-600 hover:bg-yellow-700 p-2 rounded transition duration-200">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="manage_channel.php?action=delete&id=<?php echo $channel['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this channel?')"
                   class="bg-red-600 hover:bg-red-700 p-2 rounded transition duration-200">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
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
