<?php
include 'config.php';

if (isset($_GET['vote']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['vote'];
    $cookie_name = "voted_" . $id;
    if (!isset($_COOKIE[$cookie_name])) {
        if ($type === 'up') { $db->query("UPDATE otzyvy SET Likes = Likes + 1 WHERE id = $id"); }
        elseif ($type === 'down') { $db->query("UPDATE otzyvy SET Likes = Likes - 1 WHERE id = $id"); }
        setcookie($cookie_name, "1", time() + 86400, "/");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$add_error = '';
$add_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);
    $text = trim($_POST['text'] ?? '');
    
    if (empty($customer_name)) { $add_error = 'Введите ваше имя'; }
    elseif ($rating < 1 || $rating > 5) { $add_error = 'Выберите оценку'; }
    elseif (empty($text)) { $add_error = 'Введите текст отзыва'; }
    elseif (strlen($text) < 10) { $add_error = 'Минимум 10 символов'; }
    else {
        $stmt = $db->prepare("INSERT INTO otzyvy (customer_name, rating, text, is_active, Likes) VALUES (?, ?, ?, 1, 0)");
        $stmt->bind_param("sis", $customer_name, $rating, $text);
        if ($stmt->execute()) { $add_success = 'Спасибо за отзыв!'; }
        else { $add_error = 'Ошибка сохранения'; }
        $stmt->close();
    }
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
    <style>
        .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
        .rating-input input { display: none; }
        .rating-input label { font-size: 28px; color: #ddd; cursor: pointer; transition: color 0.2s; }
        .rating-input input:checked ~ label, .rating-input label:hover, .rating-input label:hover ~ label { color: #ffc107; }
        .review-form-container { display: none; margin-top: 30px; }
        .review-form-container.show { display: block; }
        .btn-outline-review { background: transparent; border: 2px solid var(--primary); color: var(--primary); border-radius: 50px; padding: 12px 32px; font-weight: 600; transition: all 0.3s; }
        .btn-outline-review:hover { background: var(--primary); color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="Image/CR.png" alt="SR-Studio" style="height: 40px;">
            <span class="fw-bold fs-4 ms-2">SR</span><span class="fs-5">Studio</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php">Услуги</a></li>
                <li class="nav-item"><a class="nav-link" href="portfolio.php">Портфолио</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">О нас</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Отзывы</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Заявка</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="page-hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Отзывы</h1>
        <p class="lead mb-0">Что говорят о нас клиенты</p>
    </div>
</section>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3><i class="bi bi-chat-dots-fill text-primary me-2"></i>Все отзывы</h3>
        <span class="badge bg-primary fs-6 px-3 py-2"><?php echo $reviews_count; ?> отзывов</span>
    </div>

    <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
        <?php while ($row = $reviews_result->fetch_assoc()): ?>
            <div class="review-card mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="Image/user-avatar.png" class="user-avatar" alt="Аватар" onerror="this.src='Image/CR.png'">
                        <div>
                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($row['customer_name']); ?></h5>
                            <small class="text-muted"><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></small>
                        </div>
                    </div>
                    <div>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star<?php echo $i <= $row['rating'] ? '-fill' : ''; ?> stars"></i>
                        <?php endfor; ?>
                        <span class="text-muted ms-1">(<?php echo $row['rating']; ?>/5)</span>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded-3">
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['text'])); ?></p>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-3 mt-3">
                    <span class="fw-bold <?php echo ($row['Likes'] > 0) ? 'text-success' : (($row['Likes'] < 0) ? 'text-danger' : 'text-muted'); ?>"><?php echo ($row['Likes'] > 0 ? '+' : '') . $row['Likes']; ?></span>
                    <a href="?vote=down&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" style="width:38px;height:38px;"><i class="bi bi-hand-thumbs-down"></i></a>
                    <a href="?vote=up&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success rounded-circle" style="width:38px;height:38px;"><i class="bi bi-hand-thumbs-up"></i></a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-chat-square-quote fs-1 text-muted mb-3 d-block"></i>
            <h5>Отзывов пока нет</h5>
            <p class="text-muted">Будьте первым!</p>
        </div>
    <?php endif; ?>

    <!-- Кнопка "Оставить отзыв" -->
    <div class="text-center mt-4">
        <button class="btn btn-outline-review" onclick="toggleReviewForm()">
            <i class="bi bi-pencil-square me-2"></i>Оставить отзыв
        </button>
    </div>

    <!-- Скрытая форма отзыва -->
    <div id="reviewForm" class="review-form-container">
        <?php if ($add_success): ?>
            <div class="alert alert-success"><?php echo $add_success; ?></div>
        <?php endif; ?>
        <?php if ($add_error): ?>
            <div class="alert alert-danger"><?php echo $add_error; ?></div>
        <?php endif; ?>
        
        <div class="add-review-form">
            <h4 class="mb-4"><i class="bi bi-pencil-square text-primary me-2"></i>Оставить отзыв</h4>
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ваше имя <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Оценка <span class="text-danger">*</span></label>
                        <div class="rating-input">
                            <input type="radio" name="rating" value="5" id="star5" required><label for="star5"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" value="4" id="star4"><label for="star4"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" value="3" id="star3"><label for="star3"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" value="2" id="star2"><label for="star2"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" value="1" id="star1"><label for="star1"><i class="bi bi-star-fill"></i></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Отзыв <span class="text-danger">*</span></label>
                    <textarea name="text" class="form-control" rows="4" required placeholder="Расскажите о вашем опыте..."></textarea>
                    <div class="form-text">Минимум 10 символов</div>
                </div>
                <button type="submit" name="add_review" class="btn btn-modern">Отправить отзыв</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h4 class="fw-bold mb-4">SR-<span class="text-warning">Studio</span></h4>
                <p class="mb-4 opacity-75">Веб-студия в Ярославле. Создаем современные сайты, которые приносят клиентов 24/7.</p>
                <div class="social-icons d-flex gap-3">
                    <a href="https://t.me/sr_studio" target="_blank" class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-telegram text-white fs-5"></i></a>
                    <a href="https://vk.com/sr_studio" target="_blank" class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-vk text-white fs-5"></i></a>
                    <a href="https://instagram.com/sr_studio" target="_blank" class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-instagram text-white fs-5"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Услуги</h6>
                <ul class="list-unstyled">
                    <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">Дизайн сайтов</a></li>
                    <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">Разработка</a></li>
                    <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">SEO-продвижение</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Контакты</h6>
                <div class="mb-3">
                    <a href="tel:+79611626650" class="text-white-50 d-flex align-items-center mb-2 text-decoration-none"><i class="bi bi-telephone-fill me-2"></i>+7 961 162 66 50</a>
                    <a href="mailto:maxkryukovsky@gmail.com" class="text-white-50 d-flex align-items-center text-decoration-none"><i class="bi bi-envelope-fill me-2"></i>maxkryukovsky@gmail.com</a>
                </div>
                <p class="text-white-50 small mb-0"><i class="bi bi-geo-alt-fill me-1"></i>г. Ярославль</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Цены</h6>
                <div class="small text-white-50">
                    <div class="mb-2">🌐 Лендинг: от 45 000 ₽</div>
                    <div class="mb-2">🏢 Корпоративный: от 95 000 ₽</div>
                    <div>📈 SEO: от 25 000 ₽/мес</div>
                </div>
            </div>
        </div>
        <hr class="my-4 opacity-25">
        <div class="text-center text-white-50 small">© 2026 SR-Studio. Все права защищены.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleReviewForm() {
        var form = document.getElementById('reviewForm');
        form.classList.toggle('show');
        if (form.classList.contains('show')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    <?php if ($add_error): ?>
    document.getElementById('reviewForm').classList.add('show');
    <?php endif; ?>
</script>
</body>
</html>