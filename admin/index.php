<?php
ob_start();
require_once '../config.php';
requireAuth();

// Get stats for dashboard
$movies = supabaseFetch('Movie');
$channels = supabaseFetch('Live_Channels');
$categories = supabaseFetch('Categories');
$banners = supabaseFetch('Banners');

$total_movies = count($movies);
$total_channels = count($channels);
$total_categories = count($categories);
$total_banners = count($banners);
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stany Min TV - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: white; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header h1 { color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2.5em; font-weight: bold; color: #667eea; margin-bottom: 10px; }
        .stat-label { color: #666; font-size: 1.1em; }
        .nav-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .nav-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: inherit; transition: transform 0.3s; }
        .nav-card:hover { transform: translateY(-5px); }
        .nav-card h3 { color: #333; margin-bottom: 10px; }
        .nav-card p { color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stany Min TV - Admin Dashboard</h1>
        <div>
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_movies; ?></div>
                <div class="stat-label">Total Movies</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_channels; ?></div>
                <div class="stat-label">Live Channels</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_categories; ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_banners; ?></div>
                <div class="stat-label">Banners</div>
            </div>
        </div>
        
        <div class="nav-cards">
            <a href="movies.php" class="nav-card">
                <h3>Manage Movies</h3>
                <p>Add, edit, and delete movies</p>
            </a>
            <a href="live_channels.php" class="nav-card">
                <h3>Manage Live Channels</h3>
                <p>Manage TV channels and streams</p>
            </a>
            <a href="categories.php" class="nav-card">
                <h3>Manage Categories</h3>
                <p>Organize content by categories</p>
            </a>
            <a href="banners.php" class="nav-card">
                <h3>Manage Banners</h3>
                <p>Promotional banners and ads</p>
            </a>
        </div>
    </div>
</body>
</html>            <i class="fas fa-tags mb-1"></i>
            <p class="text-sm">Add Category</p>
        </a>
        <a href="banners.php" class="bg-yellow-600 hover:bg-yellow-700 p-3 rounded-lg text-center transition duration-200">
            <i class="fas fa-image mb-1"></i>
            <p class="text-sm">Add Banner</p>
        </a>
    </div>
</div>

<!-- Recent Movies -->
<div class="bg-gray-800 rounded-lg p-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Recent Movies</h3>
        <a href="movies.php" class="text-blue-400 text-sm">View All</a>
    </div>
    <div class="space-y-3">
        <?php 
        $recent_movies = array_slice($movies, -3, 3);
        $recent_movies = array_reverse($recent_movies);
        
        foreach($recent_movies as $movie): 
        ?>
        <div class="flex items-center space-x-3 bg-gray-700 p-3 rounded-lg">
            <div class="w-12 h-16 bg-gray-600 rounded flex items-center justify-center">
                <?php if(!empty($movie['poster_url'])): ?>
                <img src="<?php echo $movie['poster_url']; ?>" alt="Poster" class="w-full h-full object-cover rounded">
                <?php else: ?>
                <i class="fas fa-film text-gray-400"></i>
                <?php endif; ?>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold"><?php echo $movie['title']; ?></h4>
                <p class="text-gray-400 text-sm">Rating: <?php echo $movie['rating'] ?? 'N/A'; ?></p>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($recent_movies)): ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-film text-4xl mb-2"></i>
            <p>No movies added yet</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
