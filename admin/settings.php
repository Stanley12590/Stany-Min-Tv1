<?php include 'common/header.php'; 

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = "Settings updated successfully!";
}
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">App Settings</h2>
    <p class="text-gray-400">Configure your Stany Min TV application</p>
</div>

<?php if(isset($success)): ?>
<div class="bg-green-500 text-white p-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
</div>
<?php endif; ?>

<div class="space-y-6">
    <!-- App Information -->
    <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-bold mb-4">App Information</h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-300 mb-2">App Name</label>
                <input type="text" name="app_name" value="Stany Min TV" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Support Email</label>
                <input type="email" name="support_email" value="support@stanymintv.com" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">WhatsApp Contact</label>
                <input type="text" name="whatsapp_contact" value="+255123456789" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
            </div>
        </form>
    </div>

    <!-- User Management -->
    <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-bold mb-4">User Management</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                <div>
                    <h4 class="font-semibold">User Session Duration</h4>
                    <p class="text-gray-400 text-sm">How long users stay logged in</p>
                </div>
                <select class="bg-gray-600 border border-gray-500 rounded-lg px-3 py-2 text-white">
                    <option>24 Hours</option>
                    <option>7 Days</option>
                    <option selected>30 Days</option>
                </select>
            </div>
            
            <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                <div>
                    <h4 class="font-semibold">Auto-logout Timer</h4>
                    <p class="text-gray-400 text-sm">Automatic logout after inactivity</p>
                </div>
                <select class="bg-gray-600 border border-gray-500 rounded-lg px-3 py-2 text-white">
                    <option>15 minutes</option>
                    <option selected>30 minutes</option>
                    <option>1 hour</option>
                    <option>Never</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-900 border border-red-700 rounded-lg p-4">
        <h3 class="text-lg font-bold mb-4 text-red-200">Danger Zone</h3>
        <div class="space-y-3">
            <button type="button" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-left">
                <i class="fas fa-trash mr-2"></i>Clear All Data
            </button>
            
            <button type="button" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-left">
                <i class="fas fa-user-slash mr-2"></i>Block All Users
            </button>
            
            <button type="button" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-left">
                <i class="fas fa-skull-crossbones mr-2"></i>Delete Entire App
            </button>
        </div>
    </div>

    <!-- Save Settings -->
    <div class="bg-blue-900 border border-blue-700 rounded-lg p-4">
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-lg transition duration-200 text-lg">
            <i class="fas fa-save mr-2"></i>Save All Settings
        </button>
    </div>
</div>

<?php include 'common/footer.php'; ?>
