<?php
// Mock data for Category design
$mainCategories = [
    ['id' => 1, 'name' => 'Storage'],
    ['id' => 2, 'name' => 'Monitor']
];

$allCategories = [
    ['id' => 1, 'name' => 'Storage', 'parent_id' => 0, 'parent_name' => null],
    ['id' => 3, 'name' => 'SSD', 'parent_id' => 1, 'parent_name' => 'Storage'] // Example of sub-category 
];
?>

<h1>Category Management</h1>
<form action="category_action.php" method="POST">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <select name="parent_id">
        <option value="0">None (Main Category)</option>
        <?php foreach($mainCategories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Create Category</button>
</form>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
    <?php foreach($allCategories as $cat): ?>
    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= $cat['name'] ?></td>
        <td><?= ($cat['parent_id'] == 0) ? "Main" : "Sub-category" ?></td>
        <td>
            <a href="?edit=<?= $cat['id'] ?>">Edit</a> | 
            <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Note: Cannot delete if child categories or products exist.')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>