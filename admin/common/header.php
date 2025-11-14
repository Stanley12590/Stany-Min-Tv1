<?php 
// Use absolute path for auth
require_once __DIR__ . '/../../auth.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Stany Min TV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-white overflow-x-hidden" oncontextmenu="return false;">
    <!-- Mobile Header -->
    <header class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tv text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Stany Min TV</h1>
                    <p class="text-gray-400 text-sm">Admin Panel</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-300"><?php echo $_SESSION['admin_username']; ?></span>
                <a href="?logout=true" class="bg-red-600 hover:bg-red-700 p-2 rounded-lg transition duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation -->
    <nav class="bg-gray-800 border-t border-gray-700 fixed bottom-0 left-0 right-0 z-50">
        <div class="flex justify-around items-center p-3">
            <a href="dashboard.php" class="flex flex-col items-center text-gray-400 hover:text-white transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'text-blue-500' : ''; ?>">
                <i class="fas fa-chart-bar text-lg mb-1"></i>
                <span class="text-xs">Dashboard</span>
            </a>
            <a href="movies.php" class="flex flex-col items-center text-gray-400 hover:text-white transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'movies.php' ? 'text-blue-500' : ''; ?>">
                <i class="fas fa-film text-lg mb-1"></i>
                <span class="text-xs">Movies</span>
            </a>
            <a href="live_channels.php" class="flex flex-col items-center text-gray-400 hover:text-white transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'live_channels.php' ? 'text-blue-500' : ''; ?>">
                <i class="fas fa-tower-broadcast text-lg mb-1"></i>
                <span class="text-xs">Live TV</span>
            </a>
            <a href="categories.php" class="flex flex-col items-center text-gray-400 hover:text-white transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'text-blue-500' : ''; ?>">
                <i class="fas fa-layer-group text-lg mb-1"></i>
                <span class="text-xs">Categories</span>
            </a>
            <a href="settings.php" class="flex flex-col items-center text-gray-400 hover:text-white transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'text-blue-500' : ''; ?>">
                <i class="fas fa-cog text-lg mb-1"></i>
                <span class="text-xs">Settings</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pb-20 pt-4 px-4 min-h-screen">
