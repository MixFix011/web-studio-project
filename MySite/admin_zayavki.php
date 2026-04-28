<?php
include 'config.php';
checkAdmin();

$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM zayavki WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $db->real_escape_string($_POST['status']);
    $db->query("UPDATE zayavki SET status='$status', is_read=1 WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'markRead' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->query("UPDATE zayavki SET is_read=1 WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}

$zayavki_result = $db->query("SELECT * FROM zayavki ORDER BY id DESC");
$zayavki_count = $zayavki_result ? $zayavki_result->num_rows : 0;
$unread_count = $db->query("SELECT COUNT(*) as count FROM zayavki WHERE is_read=0")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="Image/CR.png">

    <title>Заявки - Админка | SR‑Studio</title>

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
            <h1 class="admin-title mb-3">Заявки</h1>
            <p class="admin-subtitle lead">Управление заявками клиентов SR‑Studio</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-inbox2 me-2"></i>Все заявки</h4>
                <div>
                    <span class="badge bg-primary fs-6 me-2"><?php echo $zayavki_count; ?> всего</span>
                    <?php if ($unread_count > 0): ?>
                        <span class="badge bg-warning text-dark fs-6"><?php echo $unread_count; ?> новых</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="table-responsive">
            <table class="table table-hover admin-table mb-0">
                <thead class="table-dark">
                <tr>
                    <th style="width: 120px;">Дата</th>
                    <th style="width: 240px;">Клиент / Услуга</th>
                    <th>Сообщение</th>
                    <th class="text-center" style="width: 130px;">Статус</th>
                    <th class="text-center" style="width: 130px;">Статус просмотра</th>
                    <th class="text-end" style="width: 160px;">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($zayavki_result && $zayavki_result->num_rows > 0): ?>
                    <?php while ($row = $zayavki_result->fetch_assoc()): ?>
                        <tr class="<?php echo $row['is_read'] == 0 ? 'unread' : ''; ?>">
                            <td>
                                <div class="fw-bold"><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></div>
                                <small class="text-muted"><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="Image/user-avatar.png" class="user-avatar me-3 shadow-sm" alt="Клиент">
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($row['phone']); ?>
                                            <?php if ($row['email']): ?> | <?php echo htmlspecialchars($row['email']); ?><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="service-badge">
                                    <i class="bi bi-<?php echo htmlspecialchars(strtolower($row['service'])); ?> me-1"></i>
                                    <?php echo htmlspecialchars(ucfirst($row['service'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['message']): ?>
                                    <div class="text-wrap" style="max-height: 80px; overflow: hidden;">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">— Сообщение не указано</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2 fw-normal fs-6 <?php
                                    echo $row['status'] == 'новая' ? 'status-new' :
                                        ($row['status'] == 'в работе' ? 'status-work' : 'status-done');
                                ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($row['is_read']): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Просмотрено
                                    </span>
                                <?php else: ?>
                                    <a href="?action=markRead&id=<?php echo $row['id']; ?>" class="btn-srMarkRead" title="Отметить как просмотренное">
                                        <i class="bi bi-eye"></i> Просмотрено
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <form method="POST" style="display: inline-block; margin-right: 5px;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 110px;">
                                            <option value="новая" <?php echo $row['status'] == 'новая' ? 'selected' : ''; ?>>Новая</option>
                                            <option value="в работе" <?php echo $row['status'] == 'в работе' ? 'selected' : ''; ?>>В работе</option>
                                            <option value="завершена" <?php echo $row['status'] == 'завершена' ? 'selected' : ''; ?>>Завершена</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Удалить заявку №<?php echo $row['id']; ?> от <?php echo htmlspecialchars($row['name']); ?>?')">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="Image/user-avatar.png" class="user-avatar mx-auto mb-3 d-block shadow" alt="Клиент" style="width: 60px; height: 60px;">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Заявок нет</h5>
                            <p class="text-muted mb-0">Ожидаем первые заявки от клиентов!</p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>