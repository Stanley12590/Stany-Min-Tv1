<?php include 'common/header.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_data = [
        'category_name' => $_POST['category_name']
    ];
    
    $result = supabaseInsert('categories', $category_data);
    if ($result) {
        $success = "Category added successfully!";
        // Refresh categories list
        $categories = supabaseFetch('categories', ['order' => 'created_at.desc']);
    } else {
        $error = "Failed to add category!";
    }
}

$categories = supabaseFetch('categories', ['order' => 'created_at.desc']);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Categories Management</h2>
    <p class="text-gray-400">Add and manage content categories</p>
</div>

<!-- Add Category Form -->
<div class="bg-gray-800 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-bold mb-4">Add New Category</h3>
    
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
            <label class="block text-gray-300 mb-2">Category Name *</label>
            <input type="text" name="category_name" required 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500"
                   placeholder="e.g., Action, Drama, News, Sports">
        </div>
        
        <button type="submit" 
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus-circle mr-2"></i>Add Category
        </button>
    </form>
</div>

<!-- Categories List -->
<div class="bg-gray-800 rounded-lg p-4">
    <h3 class="text-lg font-bold mb-4">All Categories (<?php echo count($categories); ?>)</h3>
    
    <div class="grid grid-cols-2 gap-3">
        <?php foreach($categories as $category): ?>
        <div class="bg-gray-700 p-4 rounded-lg text-center">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-tag text-white"></i>
            </div>
            <h4 class="font-semibold"><?php echo $category['category_name']; ?></h4>
            <p class="text-gray-400 text-sm">ID: <?php echo substr($category['id'], 0, 8); ?>...</p>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($categories)): ?>
        <div class="col-span-2 text-center py-8 text-gray-500">
            <i class="fas fa-tags text-4xl mb-2"></i>
            <p>No categories added yet</p>
            <p class="text-sm mt-2">Add your first category using the form above</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>
