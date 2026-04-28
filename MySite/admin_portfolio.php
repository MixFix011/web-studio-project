<?php include 'config.php'; checkAdmin(); ?>

<?php 
$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_POST['add'])) {
    $customer = $db->real_escape_string($_POST['customer']);
    $description = $db->real_escape_string($_POST['description']);
    $site_type = $db->real_escape_string($_POST['site_type']);
    $deadline = (int)$_POST['deadline'];
    
    $image_name = 'default.jpg'; 
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "Image/";
        
        // Создаем папку если нет
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $image_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_extension, $allowed_types) && $_FILES['image']['error'] === 0) {
            do {
                $image_name = time() . '_' . uniqid() . '.' . $image_extension;
                $target_file = $target_dir . $image_name;
            } while (file_exists($target_file));
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                chmod($target_file, 0644);
            } else {
                $image_name = 'default.jpg';
            }
        }
    }

    $db->query("INSERT INTO portfolio (customer, description, site_type, deadline, image) VALUES ('$customer', '$description', '$site_type', $deadline, '$image_name')");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $check = $db->query("SELECT image FROM portfolio WHERE id=$id");
    if ($check && $row = $check->fetch_assoc()) {
        if ($row['image'] && $row['image'] != 'default.jpg') {
            $file_path = "Image/" . $row['image'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $db->query("DELETE FROM portfolio WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Портфолио - Админка | SR-Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .img-thumb { width: 80px; height: 60px; object-fit: cover; border-radius: 8px; }
        .deadline-tag { background: #0d6efd; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.85em; }
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
            <h1 class="admin-title mb-3">Портфолио</h1>
            <p class="admin-subtitle lead">Управление портфолио SR-Studio</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-form-card h-100 service-card">
                <h3 class="mb-4">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Добавить проект
                </h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label">Заказчик <span class="text-danger">*</span></label>
                        <input type="text" name="customer" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Тип сайта <span class="text-danger">*</span></label>
                        <input type="text" name="site_type" class="form-control" placeholder="Лендинг, Корпоративный сайт..." required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Срок (дней) <span class="text-danger">*</span></label>
                        <input type="number" name="deadline" class="form-control" value="7" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Краткое описание проекта..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Скриншот</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text small">JPG, PNG, GIF. Рекомендуемый размер: 1200x800px</div>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i>Опубликовать проект
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-8">
            <?php 
            $portfolio_result = $db->query("SELECT * FROM portfolio ORDER BY id DESC");
            $portfolio_count = $portfolio_result ? $portfolio_result->num_rows : 0;
            ?>
            <div class="service-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Портфолио проектов</h4>
                    <span class="badge bg-primary"><?php echo $portfolio_count; ?> проектов</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 100px;">Фото</th>
                                <th>Проект / Тип</th>
                                <th class="text-center" style="width: 100px;">Срок</th>
                                <th class="text-end" style="width: 120px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($portfolio_result && $portfolio_result->num_rows > 0): ?>
                                <?php while($row = $portfolio_result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="Image/<?php echo htmlspecialchars($row['image']); ?>" 
                                             class="img-thumbnail rounded"
                                             style="width: 80px; height: 60px; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($row['customer']); ?>"
                                             onerror="this.src='Image/default.jpg'">
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['customer']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($row['site_type']); ?></div>
                                        <?php if (!empty($row['description'])): ?>
                                            <small class="text-muted d-block mt-1"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?php echo $row['deadline']; ?> дн.</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Удалить проект «<?php echo htmlspecialchars($row['customer']); ?>»?\n\nФайл будет удален навсегда!')">
                                                <i class="bi bi-trash3"></i> Удалить
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 bg-light">
                                        <i class="bi bi-folder-plus fs-1 text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">Портфолио пусто</h5>
                                        <p class="text-muted mb-0">Добавьте первый проект!</p>
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