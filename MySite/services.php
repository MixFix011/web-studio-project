<?php
include 'config.php';
$result = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order, id");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Услуги | SR-Studio Ярославль</title>
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
                    <li class="nav-item"><a class="nav-link px-3 py-2 active" href="#">Услуги</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="portfolio.php">Портфолио</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="about.html">О нас</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="reviews.php">Отзывы</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="register.php">Заявка</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-hero pt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Услуги</h1>
            <p class="lead mb-0">Что мы можем сделать для вашего бизнеса</p>
        </div>
    </section>

    <div class="container mt-5 pt-5">
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="service-card-header d-flex align-items-center mb-3">
                                <i class="bi <?php echo htmlspecialchars($row['icon']); ?> fs-2 text-warning me-3"></i>
                                <h5 class="mb-0"><?php echo htmlspecialchars($row['title']); ?></h5>
                            </div>
                            <?php if (!empty($row['description'])): ?>
                                <p class="text-muted small mb-3">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </p>
                            <?php endif; ?>
                            <div class="mt-auto d-flex justify-content-between align-items-end">
                                <div>
                                    <div class="fw-bold fs-5">
                                        <?php echo number_format($row['price'], 0, ',', ' '); ?> ₽
                                    </div>
                                    <small class="text-muted"><?php echo (int)$row['duration']; ?> дней</small>
                                </div>
                                <a href="register.php?service=<?php echo urlencode($row['title']); ?>" class="btn btn-modern">
                                    Заказать
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-grid fs-1 text-muted mb-4 d-block"></i>
                    <h3 class="mb-3">Услуги скоро появятся</h3>
                    <p class="lead text-muted">Мы обновляем прайс‑лист. Напишите нам, если нужен расчёт под ваш проект.</p>
                    <a href="register.php" class="btn btn-modern btn-lg mt-3">Оставить заявку</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <section class="cta py-5 bg-light">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Не нашли нужную услугу?</h2>
            <p class="lead mb-5">Опишите задачу, мы подберём решение под ваш бюджет.</p>
            <a href="register.php" class="btn btn-modern btn-lg">Оставить заявку</a>
        </div>
    </section>

    <footer id="contacts">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="fw-bold mb-4">SR-<span class="text-warning">Studio</span></h4>
                    <p class="mb-4 opacity-90">Веб‑студия в Ярославле. Создаем современные сайты которые приносят клиентов 24/7.</p>
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
                        <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">Дизайн сайтов</a></li>
                        <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">Разработка</a></li>
                        <li><a href="services.php" class="text-white-50 text-decoration-none py-1 d-block small">SEO‑продвижение</a></li>
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
                © 2026 SR‑Studio. Все права защищены.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>