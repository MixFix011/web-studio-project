<?php
include 'config.php';

$col = 1;

if (isset($_GET['vote']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['vote'];
    $cookie_name = "voted_" . $id;

    if (!isset($_COOKIE[$cookie_name])) {
        if ($type === 'up') {
            $db->query("UPDATE otzyvy SET Likes = Likes + 1 WHERE id = $id");
        } elseif ($type === 'down') {
            $db->query("UPDATE otzyvy SET Likes = Likes - 1 WHERE id = $id");
        }
    }
    setcookie($cookie_name, "1", time() + 10, "/"); 
    $col++;
    if ($col >= 6) {
        echo "Хватит";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$reviews_result = $db->query("SELECT * FROM otzyvy WHERE is_active = 1 ORDER BY id DESC");
$reviews_count = $reviews_result ? $reviews_result->num_rows : 0;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Отзывы | SR-Studio Ярославль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="Image/CR.png" alt="SR-Studio" class="me-2">
                <span class="fw-bold fs-4">SR</span><span class="fs-5">Studio</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="index.php">Главная</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="services.php">Услуги</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="portfolio.php">Портфолио</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="about.html">О нас</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2 active" href="#">Отзывы</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="register.php">Заявка</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-hero pt-2">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Отзывы</h1>
            <p class="lead mb-0">Реальные отзывы</p>
        </div>
    </section>

    <div class="container mt-5 pt-5">
        <?php 
        $reviews_result = $db->query("SELECT * FROM otzyvy WHERE is_active = 1 ORDER BY id DESC");
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
                            <th class="text-center" style="width: 120px;">оценка отзыва</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                            <?php while ($row = $reviews_result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="Image/user-avatar.png" class="user-avatar me-3 shadow-sm" alt="Клиент">
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
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= $row['rating'] ? '-fill' : ''; ?> text-warning fs-6"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted d-block"><?php echo $row['rating']; ?>/5</small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <div class="text-center">
                                                <?php 
                                                    $likes = $row['Likes'];
                                                    $color = ($likes > 0) ? 'text-success' : (($likes < 0) ? 'text-danger' : 'text-muted');
                                                ?>
                                                <span class="fw-bold <?php echo $color; ?> fs-5">
                                                    <?php echo ($likes > 0 ? '+' : '') . $likes; ?>
                                                </span>
                                            </div>
                                            <a href="?vote=down&id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                                <i class="bi bi-hand-thumbs-down fs-5 text-danger"></i>
                                            </a>
                                            <a href="?vote=up&id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                                <i class="bi bi-hand-thumbs-up fs-5 text-success"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 bg-light rounded-3">
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

    <footer id="contacts">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="fw-bold mb-4">SR-<span class="text-warning">Studio</span></h4>
                    <p class="mb-4 opacity-90">Веб-студия в Ярославле. Создаем современные сайты которые приносят клиентов 24/7.</p>
                    <div class="social-icons">
                        <a href="https://t.me/sr_studio" title="Telegram" class="social-icon">
                            <img src="Image/telegram.png" alt="Telegram" width="24" height="24">
                        </a>
                        <a href="https://vk.com/sr_studio" title="ВКонтакте" class="social-icon">
                            <img src="Image/vk.png" alt="ВКонтакте" width="24" height="24">
                        </a>
                        <a href="https://instagram.com/sr_studio" title="Instagram" class="social-icon">
                            <img src="Image/Instagram.png" alt="Instagram" width="24" height="24">
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Услуги</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php#services" class="text-white-50 text-decoration-none py-1 d-block small">Дизайн сайтов</a></li>
                        <li><a href="index.php#services" class="text-white-50 text-decoration-none py-1 d-block small">Разработка</a></li>
                        <li><a href="index.php#services" class="text-white-50 text-decoration-none py-1 d-block small">SEO-продвижение</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Контакты</h6>
                    <div class="mb-3">
                        <a href="tel:+79611626650" class="text-white d-flex align-items-center mb-2 text-decoration-none">
                            <i class="bi bi-telephone-fill me-2"></i>+7 961 162 66 50
                        </a>
                        <a href="mailto:maxkryukovsky@gmail.com" class="text-white d-flex align-items-center text-decoration-none">
                            <i class="bi bi-envelope-fill me-2"></i>maxkryukovsky@gmail.com
                        </a>
                    </div>
                    <p class="text-white-50 small mb-0"><i class="bi bi-geo-alt-fill me-1"></i>г. Ярославль</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Цены</h6>
                    <div class="small text-white-50">
                        <div class="mb-2">Лендинг: от 45 000 ₽</div>
                        <div class="mb-2">Корпоративный: от 95 000 ₽</div>
                        <div>SEO: от 25 000 ₽/мес</div>
                    </div>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center text-white-50 small">
                © 2026 SR-Studio. Все права защищены.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>