<?php
include 'config.php';
$result = $db->query("SELECT * FROM portfolio ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Портфолио | SR-Studio Ярославль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <br>

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
                    <li class="nav-item"><a class="nav-link px-3 py-2 active" href="#">Портфолио</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="about.html">О нас</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="reviews.php">Отзывы</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="register.php">Заявка</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-hero pt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Портфолио</h1>
            <p class="lead mb-0">Реальные проекты для реальных клиентов</p>
        </div>
    </section>

    <div class="container mt-5 pt-5">
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-item h-100">
                        <div class="position-relative overflow-hidden rounded-top shadow-sm">
                            <img src="Image/<?php echo htmlspecialchars($row['image']); ?>" class="img-fluid w-100" style="height: 250px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['customer']); ?>"onerror="this.src='Image/Corp_primer.png'">
                            <div class="position-absolute top-0 start-0 bg-primary text-white p-2 rounded-end m-2">
                                <small><?php echo htmlspecialchars($row['site_type']); ?></small>
                            </div>
                        </div>            

                        <div class="p-4 bg-white h-100 d-flex flex-column">
                            <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($row['customer']); ?></h5>
                            <?php if (!empty($row['description'])): ?>
                                <p class="text-muted small flex-grow-1 mb-3"><?php echo htmlspecialchars(mb_substr($row['description'], 0, 120)); ?></p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-end flex-grow-1">
                                <span class="badge bg-success fs-6"><?php echo htmlspecialchars($row['deadline']); ?> дн.</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-images fs-1 text-muted mb-4 d-block"></i>
                    <h3 class="mb-3">Портфолио скоро появится</h3>
                    <p class="lead text-muted">Готовим для вас лучшие проекты!</p>
                    <a href="register.php" class="btn btn-modern btn-lg mt-3">Заказать проект</a>
                </div>
            <?php endif; ?>
        </div>


    </div>

    <section class="cta py-5 bg-light">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Готовы к вашему проекту?</h2>
            <p class="lead mb-5">Создаем сайты которые приносят клиентов</p>
            <a href="register.php" class="btn btn-modern btn-lg">Оставить заявку</a>
        </div>
    </section>

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