<?php include 'config.php'; checkAdmin(); ?>

<?php 
$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_POST['add'])) {
    $customer_name = $db->real_escape_string($_POST['customer_name']);
    $rating = (int)$_POST['rating'];
    $text = $db->real_escape_string($_POST['text']);
    
    $db->query("INSERT INTO otzyvy (customer_name, rating, text, is_active) VALUES ('$customer_name', $rating, '$text', 1)");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM otzyvy WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $status_result = $db->query("SELECT is_active FROM otzyvy WHERE id=$id");
    if ($status_result && $row = $status_result->fetch_assoc()) {
        $new_status = $row['is_active'] ? 0 : 1;
        $db->query("UPDATE otzyvy SET is_active=$new_status WHERE id=$id");
    }
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Отзывы - Админка | SR-Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid px-3">
        <a class="navbar-brand d-flex align-items-center ps-0" href="#" style="height: 40px;">
            <img src="Image/CR.png" alt="SR-Studio" style="height: 40px; width: auto;">
            <span class="fw-bold fs-4">SR</span><span class="fs-5">Studio</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 <?php echo $current_page == 'admin_uslugi.php' ? 'active' : ''; ?>" href="admin_uslugi.php">Услуги</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 <?php echo $current_page == 'admin_portfolio.php' ? 'active' : ''; ?>" href="admin_portfolio.php">Портфолио</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 <?php echo $current_page == 'admin_reviews.php' ? 'active' : ''; ?>" href="admin_reviews.php">Отзывы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 <?php echo $current_page == 'admin_zayavki.php' ? 'active' : ''; ?>" href="admin_zayavki.php">Заявки</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2" href="index.php">На главную</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pt-5">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <h1 class="admin-title mb-3">Отзывы</h1>
            <p class="admin-subtitle lead">Управление отзывами клиентов SR-Studio</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-form-card h-100 service-card">
                <h3 class="mb-4">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Добавить отзыв</h3>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Имя клиента <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Иван Иванов" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Оценка <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select" required>
                            <option value="">Выберите оценку</option>
                            <option value="5">★★★★★ Отлично (5/5)</option>
                            <option value="4">★★★★☆ Хорошо (4/5)</option>
                            <option value="3">★★★☆☆ Нормально (3/5)</option>
                            <option value="2">★★☆☆☆ Плохо (2/5)</option>
                            <option value="1">★☆☆☆☆ Ужасно (1/5)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Текст отзыва <span class="text-danger">*</span></label>
                        <textarea name="text" class="form-control" rows="4" placeholder="Поделитесь своим опытом..." required maxlength="500"></textarea>
                        <div class="form-text">Максимум 500 символов</div>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i>Добавить отзыв
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-8">
            <?php 
            $reviews_result = $db->query("SELECT * FROM otzyvy ORDER BY id DESC");
            $reviews_count = $reviews_result ? $reviews_result->num_rows : 0;
            ?>
            <div class="service-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Все отзывы</h4>
                    <span class="badge bg-primary"><?php echo $reviews_count; ?> отзывов</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 180px;">Клиент</th>
                                <th style="width: auto;">Отзыв</th>
                                <th class="text-center" style="width: 120px;">Оценка</th>
                                <th class="text-center" style="width: 100px;">Статус</th>
                                <th class="text-end" style="width: 140px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                                <?php while($row = $reviews_result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="Image/user-avatar.png" class="user-avatar me-3 shadow-sm" alt="Клиент" >
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                                <small class="text-muted"><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="review-text align-middle">
                                        <?php echo htmlspecialchars($row['text']); ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= $row['rating'] ? '-fill' : '' ?> stars fs-6"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted d-block"><?php echo $row['rating']; ?>/5</small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if($row['is_active']): ?>
                                            <span class="badge bg-success">Опубликован</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Скрыт</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="?toggle=<?php echo $row['id']; ?>" 
                                               class="btn <?php echo $row['is_active'] ? 'btn-outline-danger' : 'btn-outline-success'; ?>"
                                               title="<?php echo $row['is_active'] ? 'Скрыть отзыв' : 'Опубликовать отзыв'; ?>">
                                                <i class="bi bi-power"></i>
                                            </a>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-outline-danger"onclick="return confirm('Удалить отзыв «<?php echo htmlspecialchars($row['customer_name']); ?>»?\n\nЭто действие нельзя отменить!')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 bg-light rounded-3">
                                        <div class="user-avatar mx-auto mb-3 d-block shadow-sm"></div>
                                        <i class="bi bi-chat-square-quote fs-1 text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted mb-2">Отзывов пока нет</h5>
                                        <p class="text-muted mb-0">Добавьте первый отзыв!</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>