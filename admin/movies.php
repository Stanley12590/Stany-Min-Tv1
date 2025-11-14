<?php
ob_start();
require_once '../config.php';
requireAuth();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $poster_url = $_POST['poster_url'] ?? '';
    $description = $_POST['description'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $realesa_year = $_POST['realesa_year'] ?? '';
    $watch_link = $_POST['watch_link'] ?? '';
    
    if (!empty($title)) {
        $data = [
            'title' => $title,
            'poster_url' => $poster_url,
            'description' => $description,
            'rating' => $rating,
            'realesa_year' => $realesa_year,
            'watch_link' => $watch_link,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if (supabaseInsert('Movie', $data)) {
            $_SESSION['message'] = 'Movie added successfully!';
            header('Location: movies.php');
            exit;
        } else {
            $error = 'Failed to add movie!';
        }
    } else {
        $error = 'Movie title is required!';
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (supabaseDelete('Movie', $id)) {
        $_SESSION['message'] = 'Movie deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete movie!';
    }
    header('Location: movies.php');
    exit;
}

// Get all movies
$movies = supabaseFetch('Movie', ['order' => 'created_at.desc']);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stany Min TV - Movies</title>
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
        <h1>Stany Min TV - Manage Movies</h1>
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
        
        <!-- Add Movie Form -->
        <div class="form-container">
            <h2>Add New Movie</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Movie Title:</label>
                    <input type="text" id="title" name="title" required>
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
                    <label for="rating">Rating:</label>
                    <input type="text" id="rating" name="rating" placeholder="e.g., 8.5/10">
                </div>
                <div class="form-group">
                    <label for="realesa_year">Release Year:</label>
                    <input type="number" id="realesa_year" name="realesa_year" min="1900" max="2030">
                </div>
                <div class="form-group">
                    <label for="watch_link">Watch Link:</label>
                    <input type="text" id="watch_link" name="watch_link">
                </div>
                <button type="submit" class="btn">Add Movie</button>
            </form>
        </div>
        
        <!-- Movies List -->
        <h2>All Movies</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Rating</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movies)): ?>
                    <tr><td colspan="4" style="text-align: center;">No movies found</td></tr>
                <?php else: ?>
                    <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($movie['title'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['rating'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['realesa_year'] ?? ''); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $movie['id']; ?>" class="btn">Edit</a>
                            <a href="?delete=<?php echo $movie['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Delete this movie?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>                
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
