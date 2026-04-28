<?php include 'config.php'; checkAdmin(); ?>

<?php 
$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_POST['add'])) {
    $title = $db->real_escape_string($_POST['title']);
    $description = $db->real_escape_string($_POST['description']);
    $price = (int)$_POST['price'];
    $duration = (int)$_POST['duration'];
    $icon = $db->real_escape_string($_POST['icon']);
    $db->query("INSERT INTO services (title, description, price, duration, icon, sort_order, is_active) VALUES ('$title', '$description', $price, $duration, '$icon', 0, 1)");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM services WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $status = $db->query("SELECT is_active FROM services WHERE id=$id")->fetch_assoc()['is_active'];
    $new_status = $status ? 0 : 1;
    $db->query("UPDATE services SET is_active=$new_status WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Админка | SR-Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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
            <h1 class="admin-title mb-3">Услуги</h1>
            <p class="admin-subtitle lead">Управление услугами SR-Studio</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="admin-form-card h-100 service-card">
                <h3 class="mb-4">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Добавить услугу
                </h3>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Название услуги</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Описание услуги..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Цена (₽)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Срок (дней)</label>
                            <input type="number" name="duration" class="form-control" value="7" min="1" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Иконка Bootstrap</label>
                        <input type="text" name="icon" class="form-control" placeholder="bi-brush, bi-code-slash, bi-graph-up" required>
                        <div class="form-text small">Примеры: bi-brush, bi-code-slash, bi-gear</div>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary w-100">
                        Добавить услугу
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="service-card h-100">
                <?php 
                $services_result = $db->query("SELECT * FROM services ORDER BY sort_order ASC, id DESC");
                $services_count = $services_result ? $services_result->num_rows : 0;
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Список услуг</h4>
                    <span class="badge bg-light text-dark"><?php echo $services_count; ?> услуг</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Иконка</th>
                                <th>Услуга</th>
                                <th>Цена</th>
                                <th>Срок</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($services_result && $services_result->num_rows > 0): ?>
                                <?php while($row = $services_result->fetch_assoc()): ?>
                                <tr>
                                    <td><i class="bi <?php echo htmlspecialchars($row['icon']); ?> fs-4"></i></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['description']); ?></small>
                                    </td>
                                    <td><strong><?php echo number_format($row['price'], 0, '.', ' '); ?> ₽</strong></td>
                                    <td><?php echo $row['duration']; ?> дн.</td>
                                    <td>
                                        <?php if($row['is_active']): ?>
                                            <span class="badge bg-success">Активна</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Скрыта</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="?toggle=<?php echo $row['id']; ?>" 
                                               class="btn <?php echo $row['is_active'] ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <i class="bi bi-power"></i>
                                            </a>
                                            <a href="?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-outline-danger"
                                               onclick="return confirm('Удалить услугу «<?php echo htmlspecialchars($row['title']); ?>»?\n\nДействие нельзя отменить!')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-plus-circle fs-1 text-muted mb-3 d-block"></i>
                                        <h5>Услуг нет</h5>
                                        <p class="text-muted mb-0">Добавьте первую услугу!</p>
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