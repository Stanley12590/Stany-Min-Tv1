<?php include 'common/header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (!$action || !$id) {
    header('Location: movies.php');
    exit;
}

// Get movie data
$movie = supabaseFetch('movies', ['id' => 'eq.' . $id])[0] ?? null;
if (!$movie) {
    header('Location: movies.php');
    exit;
}

$categories = supabaseFetch('categories');

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'edit') {
        $update_data = [
            'title' => $_POST['title'],
            'poster_url' => $_POST['poster_url'],
            'description' => $_POST['description'],
            'rating' => $_POST['rating'],
            'release_year' => $_POST['release_year'],
            'category_id' => $_POST['category_id'],
            'watch_link' => $_POST['watch_link']
        ];
        
        $result = supabaseUpdate('movies', $id, $update_data);
        if ($result) {
            $success = "Movie updated successfully!";
            $movie = array_merge($movie, $update_data);
        } else {
            $error = "Failed to update movie!";
        }
    }
} elseif ($action === 'delete' && isset($_GET['confirm'])) {
    $result = supabaseDelete('movies', $id);
    if ($result) {
        header('Location: movies.php?deleted=true');
        exit;
    } else {
        $error = "Failed to delete movie!";
    }
}
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">
        <?php echo $action === 'edit' ? 'Edit Movie' : 'Delete Movie'; ?>
    </h2>
    <p class="text-gray-400">
        <?php echo $action === 'edit' ? 'Update movie information' : 'Permanently delete this movie'; ?>
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
                <label class="block text-gray-300 mb-2">Movie Title *</label>
                <input type="text" name="title" value="<?php echo $movie['title']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Poster URL *</label>
                <input type="url" name="poster_url" value="<?php echo $movie['poster_url']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Description *</label>
                <textarea name="description" rows="3" required 
                          class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"><?php echo $movie['description']; ?></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-2">Rating</label>
                    <input type="number" name="rating" value="<?php echo $movie['rating']; ?>" step="0.1" min="0" max="10" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Release Year</label>
                    <input type="number" name="release_year" value="<?php echo $movie['release_year']; ?>" min="1900" max="2030" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Category *</label>
                <select name="category_id" required 
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $movie['category_id'] ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Watch Link (URL) *</label>
                <input type="url" name="watch_link" value="<?php echo $movie['watch_link']; ?>" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Update Movie
            </button>
            <a href="movies.php" 
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
    <p class="text-red-200 mb-6">Are you sure you want to delete "<strong><?php echo $movie['title']; ?></strong>"? This action cannot be undone.</p>
    
    <div class="flex space-x-3">
        <a href="?action=delete&id=<?php echo $id; ?>&confirm=true" 
           class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-trash mr-2"></i>Yes, Delete
        </a>
        <a href="movies.php" 
           class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-times mr-2"></i>Cancel
        </a>
    </div>
</div>
<?php endif; ?>

<?php include 'common/footer.php'; ?>
