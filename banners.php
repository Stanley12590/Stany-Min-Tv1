<?php include 'common/header.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $banner_data = [
        'banner_image_url' => $_POST['banner_image_url'],
        'target_movie_id' => $_POST['target_movie_id'] ?: null,
        'target_channel_id' => $_POST['target_channel_id'] ?: null
    ];
    
    $result = supabaseInsert('banners', $banner_data);
    if ($result) {
        $success = "Banner added successfully!";
    } else {
        $error = "Failed to add banner!";
    }
}

// Handle banner deletion
if (isset($_GET['delete_banner'])) {
    $delete_id = $_GET['delete_banner'];
    $result = supabaseDelete('banners', $delete_id);
    if ($result) {
        $success = "Banner deleted successfully!";
    } else {
        $error = "Failed to delete banner!";
    }
}

// Get data for dropdowns
$movies = supabaseFetch('movies');
$channels = supabaseFetch('live_channels');
$banners = supabaseFetch('banners', ['order' => 'created_at.desc']);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Banners Management</h2>
    <p class="text-gray-400">Manage homepage banner slides</p>
</div>

<!-- Add Banner Form -->
<div class="bg-gray-800 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-bold mb-4">Add New Banner</h3>
    
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
        <div>
            <label class="block text-gray-300 mb-2">Banner Image URL *</label>
            <input type="url" name="banner_image_url" required 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                   placeholder="https://example.com/banner.jpg">
        </div>
        
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-gray-300 mb-2">Target Movie (Optional)</label>
                <select name="target_movie_id" 
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    <option value="">Select Movie</option>
                    <?php foreach($movies as $movie): ?>
                    <option value="<?php echo $movie['id']; ?>"><?php echo $movie['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Target Channel (Optional)</label>
                <select name="target_channel_id" 
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    <option value="">Select Channel</option>
                    <?php foreach($channels as $channel): ?>
                    <option value="<?php echo $channel['id']; ?>"><?php echo $channel['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="bg-yellow-800 border border-yellow-600 rounded-lg p-3">
            <p class="text-yellow-200 text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                Note: At least one target (movie or channel) is recommended for the banner to be clickable.
            </p>
        </div>
        
        <button type="submit" 
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus-circle mr-2"></i>Add Banner
        </button>
    </form>
</div>

<!-- Banners List -->
<div class="bg-gray-800 rounded-lg p-4">
    <h3 class="text-lg font-bold mb-4">All Banners (<?php echo count($banners); ?>)</h3>
    
    <div class="space-y-4">
        <?php foreach($banners as $banner): 
            $target_name = 'No Target';
            $target_type = '';
            
            if ($banner['target_movie_id']) {
                foreach($movies as $movie) {
                    if($movie['id'] == $banner['target_movie_id']) {
                        $target_name = $movie['title'];
                        $target_type = 'Movie';
                        break;
                    }
                }
            } elseif ($banner['target_channel_id']) {
                foreach($channels as $channel) {
                    if($channel['id'] == $banner['target_channel_id']) {
                        $target_name = $channel['name'];
                        $target_type = 'Channel';
                        break;
                    }
                }
            }
        ?>
        <div class="bg-gray-700 rounded-lg overflow-hidden">
            <div class="h-32 bg-gray-600 flex items-center justify-center">
                <?php if(!empty($banner['banner_image_url'])): ?>
                <img src="<?php echo $banner['banner_image_url']; ?>" alt="Banner" class="w-full h-full object-cover">
                <?php else: ?>
                <i class="fas fa-image text-gray-400 text-4xl"></i>
                <?php endif; ?>
            </div>
            <div class="p-3">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-400">Target: <?php echo $target_type; ?></p>
                        <p class="font-semibold"><?php echo $target_name; ?></p>
                    </div>
                    <a href="?delete_banner=<?php echo $banner['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this banner?')"
                       class="bg-red-600 hover:bg-red-700 p-2 rounded transition duration-200">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($banners)): ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-images text-4xl mb-2"></i>
            <p>No banners added yet</p>
            <p class="text-sm mt-2">Add your first banner using the form above</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
