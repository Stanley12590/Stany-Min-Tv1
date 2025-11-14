<?php
include '../auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Stany Min TV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        body {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center" oncontextmenu="return false;">
    <div class="max-w-md w-full bg-gray-800 rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tv text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Stany Min TV</h1>
            <p class="text-gray-400">Admin Panel Login</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-300 mb-2">Username</label>
                <input type="text" name="username" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
        </form>
        
        <div class="mt-6 text-center text-gray-400 text-sm">
            <p>Default Credentials:</p>
            <p>Username: <strong>admin</strong></p>
            <p>Password: <strong>Stanytz076#</strong></p>
        </div>
    </div>

    <script>
        // Disable right-click, text selection, and zoom
        document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        document.addEventListener('selectstart', function(e) { e.preventDefault(); });
        document.addEventListener('dragstart', function(e) { e.preventDefault(); });
        
        // Disable zoom
        document.addEventListener('touchstart', function(e) { if (e.touches.length > 1) e.preventDefault(); });
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(e) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) e.preventDefault();
            lastTouchEnd = now;
        }, false);
    </script>
</body>
</html>
