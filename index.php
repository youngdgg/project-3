<?php
include 'includes/config.php';

$categories = $conn->query("SELECT * FROM categories");

$filter_category = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT articles.*, categories.name AS category_name FROM articles LEFT JOIN categories ON articles.category_id = categories.category_id WHERE 1=1";

if ($filter_category) {
    $sql .= " AND articles.category_id='$filter_category'";
}

if ($search_query) {
    $sql .= " AND articles.title LIKE '%$search_query%'";
}

$articles = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News Website</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <form method="GET" action="">
        <select name="category_id">
            <option value="">All Categories</option>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <option value="<?= $category['category_id'] ?>" <?= $filter_category == $category['category_id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="search" placeholder="Search..." value="<?= $search_query ?>">
        <button type="submit">Filter</button>
    </form>

    <div class="articles">
        <?php while ($article = $articles->fetch_assoc()): ?>
            <div class="article">
                <h2><a href="articles/view.php?id=<?= $article['article_id'] ?>"><?= $article['title'] ?></a></h2>
                <p><?= substr($article['content'], 0, 100) ?>...</p>
                <p><strong>Category:</strong> <?= $article['category_name'] ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
