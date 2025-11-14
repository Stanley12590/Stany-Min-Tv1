<?php include 'common/header.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_data = [
        'title' => $_POST['title'],
        'poster_url' => $_POST['poster_url'],
        'description' => $_POST['description'],
        'rating' => $_POST['rating'],
        'release_year' => $_POST['release_year'],
        'category_id' => $_POST['category_id'],
        'watch_link' => $_POST['watch_link']
    ];
    
    $result = supabaseInsert('movies', $movie_data);
    if ($result) {
        $success = "Movie added successfully!";
    } else {
        $error = "Failed to add movie!";
    }
}

// Get categories for dropdown
$categories = supabaseFetch('categories');
$movies = supabaseFetch('movies', ['order' => 'created_at.desc']);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Movies Management</h2>
    <p class="text-gray-400">Add and manage movies</p>
</div>

<!-- Add Movie Form -->
<div class="bg-gray-800 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-bold mb-4">Add New Movie</h3>
    
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
                <label class="block text-gray-300 mb-2">Movie Title *</label>
                <input type="text" name="title" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                       placeholder="Enter movie title">
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
                          placeholder="Enter movie description"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-2">Rating</label>
                    <input type="number" name="rating" step="0.1" min="0" max="10" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                           placeholder="7.5">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Release Year</label>
                    <input type="number" name="release_year" min="1900" max="2030" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                           placeholder="2024">
                </div>
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
                <label class="block text-gray-300 mb-2">Watch Link (URL) *</label>
                <input type="url" name="watch_link" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                       placeholder="https://example.com/watch">
            </div>
        </div>
        
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus-circle mr-2"></i>Add Movie
        </button>
    </form>
</div>

<!-- Movies List -->
<div class="bg-gray-800 rounded-lg p-4">
    <h3 class="text-lg font-bold mb-4">All Movies (<?php echo count($movies); ?>)</h3>
    
    <div class="space-y-3">
        <?php foreach($movies as $movie): 
            $category_name = 'Uncategorized';
            foreach($categories as $cat) {
                if($cat['id'] == $movie['category_id']) {
                    $category_name = $cat['category_name'];
                    break;
                }
            }
        ?>
        <div class="flex items-center justify-between bg-gray-700 p-3 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-16 bg-gray-600 rounded flex items-center justify-center">
                    <?php if(!empty($movie['poster_url'])): ?>
                    <img src="<?php echo $movie['poster_url']; ?>" alt="Poster" class="w-full h-full object-cover rounded">
                    <?php else: ?>
                    <i class="fas fa-film text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold"><?php echo $movie['title']; ?></h4>
                    <p class="text-gray-400 text-sm"><?php echo $category_name; ?> • <?php echo $movie['release_year']; ?></p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="manage_movie.php?action=edit&id=<?php echo $movie['id']; ?>" 
                   class="bg-yellow-600 hover:bg-yellow-700 p-2 rounded transition duration-200">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="manage_movie.php?action=delete&id=<?php echo $movie['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this movie?')"
                   class="bg-red-600 hover:bg-red-700 p-2 rounded transition duration-200">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($movies)): ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-film text-4xl mb-2"></i>
            <p>No movies added yet</p>
            <p class="text-sm mt-2">Add your first movie using the form above</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
