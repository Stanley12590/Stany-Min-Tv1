<?php include 'common/header.php'; 

// Fetch data for stats
$movies = supabaseFetch('movies');
$channels = supabaseFetch('live_channels');
$categories = supabaseFetch('categories');
$banners = supabaseFetch('banners');
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Dashboard</h2>
    <p class="text-gray-400">Welcome to Stany Min TV Admin Panel</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Movies</p>
                <h3 class="text-2xl font-bold"><?php echo count($movies); ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-film text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Live Channels</p>
                <h3 class="text-2xl font-bold"><?php echo count($channels); ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-tower-broadcast text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Categories</p>
                <h3 class="text-2xl font-bold"><?php echo count($categories); ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-layer-group text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Banners</p>
                <h3 class="text-2xl font-bold"><?php echo count($banners); ?></h3>
            </div>
            <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-images text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-gray-800 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 gap-3">
        <a href="movies.php" class="bg-blue-600 hover:bg-blue-700 p-3 rounded-lg text-center transition duration-200">
            <i class="fas fa-plus-circle mb-1"></i>
            <p class="text-sm">Add Movie</p>
        </a>
        <a href="live_channels.php" class="bg-green-600 hover:bg-green-700 p-3 rounded-lg text-center transition duration-200">
            <i class="fas fa-broadcast-tower mb-1"></i>
            <p class="text-sm">Add Channel</p>
        </a>
        <a href="categories.php" class="bg-purple-600 hover:bg-purple-700 p-3 rounded-lg text-center transition duration-200">
            <i class="fas fa-tags mb-1"></i>
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
