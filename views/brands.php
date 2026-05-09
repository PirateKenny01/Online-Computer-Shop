<?php
// Mock data for Brand design
$brands = [
    ['id' => 1, 'name' => 'ASUS', 'category_name' => 'Monitor'],
    ['id' => 2, 'name' => 'Samsung', 'category_name' => 'Storage']
];

$allCategories = [
    ['id' => 1, 'name' => 'Storage'],
    ['id' => 2, 'name' => 'Monitor']
];
?>

<h1>Brand Management</h1>
<form action="brand_action.php" method="POST">
    <input type="text" name="brand_name" placeholder="Brand Name" required>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach($allCategories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Add Brand</button>
</form>

<table border="1" width="100%">
    <tr>
        <th>Brand Name</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>
    <?php foreach($brands as $brand): ?>
    <tr>
        <td><?= $brand['name'] ?></td>
        <td><?= $brand['category_name'] ?></td>
        <td><a href="?delete=<?= $brand['id'] ?>">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>