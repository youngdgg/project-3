<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $image_url = $_POST['image_url'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO articles (user_id, category_id, title, content, image_url) VALUES ('$user_id', '$category_id', '$title', '$content', '$image_url')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Article</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" required></textarea>
        <select name="category_id" required>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="image_url" placeholder="Image URL">
        <button type="submit">Create</button>
    </form>
</body>
</html>

<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $article = $conn->query("SELECT * FROM articles WHERE article_id='$article_id'")->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $image_url = $_POST['image_url'];

    $sql = "UPDATE articles SET title='$title', content='$content', category_id='$category_id', image_url='$image_url' WHERE article_id='$article_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Article</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Title" value="<?= $article['title'] ?>" required>
        <textarea name="content" placeholder="Content" required><?= $article['content'] ?></textarea>
        <select name="category_id" required>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $article['category_id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="image_url" placeholder="Image URL" value="<?= $article['image_url'] ?>">
        <button type="submit">Update</button>
    </form>
</body>
</html>

<?php
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $sql = "DELETE FROM articles WHERE article_id='$article_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<?php
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$articles = $conn->query("SELECT articles.*, categories.name AS category_name FROM articles LEFT JOIN categories ON articles.category_id = categories.category_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Articles</h1>
    <a href="create_article.php">Create New Article</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($article = $articles->fetch_assoc()): ?>
                <tr>
                    <td><?= $article['title'] ?></td>
                    <td><?= $article['category_name'] ?></td>
                    <td><?= $article['created_at'] ?></td>
                    <td>
                        <a href="edit_article.php?id=<?= $article['article_id'] ?>">Edit</a>
                        <a href="delete_article.php?id=<?= $article['article_id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
