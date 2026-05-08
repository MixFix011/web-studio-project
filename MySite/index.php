<?php
include 'config.php';

$result = $db->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

$result_portfolio = $db->query("SELECT * FROM portfolio WHERE is_active = 1 ORDER BY id DESC");
$portfolio_items = [];
while ($row = $result_portfolio->fetch_assoc()) {
    $portfolio_items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>SR-Studio | Веб-студия Ярославль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .carousel-control-prev, .carousel-control-next {
            width: 8%;
            opacity: 0.7;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            padding: 20px;
            background-size: 50%;
        }
        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .hero .lead { font-size: 1.2rem !important; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#about">
            <img src="Image/CR.png" alt="SR-Studio" class="me-2" style="height: 40px;">
            <span class="fw-bold fs-4">SR</span><span class="fs-5">Studio</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link px-3 py-2" href="services.php">Услуги</a></li>
                <li class="nav-item"><a class="nav-link px-3 py-2" href="portfolio.php">Портфолио</a></li>
                <li class="nav-item"><a class="nav-link px-3 py-2" href="about.html">О нас</a></li>
                <li class="nav-item"><a class="nav-link px-3 py-2" href="reviews.php">Отзывы</a></li>
                <li class="nav-item"><a class="nav-link px-3 py-2" href="register.php">Заявка</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero pt-5" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="mb-5">Создаем <span class="text-warning">современные</span><br>сайты для вашего бизнеса</h1>
                <p class="lead fs-3 mb-4">Веб-студия в Ярославле. Дизайн • Разработка • SEO-продвижение • Поддержка</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="#services" class="btn btn-modern btn-lg" style="width: 100%; display: flex; justify-content: center;">Посмотреть услуги</a>
                    <a href="#portfolio" class="btn btn-modern btn-lg" style="width: 100%; display: flex; justify-content: center;">Посмотреть проекты</a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <img src="Image/CR.png" alt="SR-Studio" class="img-fluid shadow-lg rounded-4" style="max-height:250px;">
            </div>
        </div>
    </div>
</section>

<section class="stats py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6"><div class="p-4"><span class="stat-number d-block mb-3">+150</span><div class="h5 fw-bold">сайтов создано</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="p-4"><span class="stat-number d-block mb-3">95%</span><div class="h5 fw-bold">довольных клиентов</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="p-4"><span class="stat-number d-block mb-3">2 мес</span><div class="h5 fw-bold">ТОП-10 Яндекс</div></div></div>
            <div class="col-lg-3 col-md-6"><div class="p-4"><span class="stat-number d-block mb-3">24/7</span><div class="h5 fw-bold">поддержка</div></div></div>
        </div>
    </div>
</section>

<!-- УСЛУГИ - СЛАЙДЕР (на ПК 3 карточки, на телефоне 1) -->
<section class="services py-5" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-4">Наши услуги</h2>
            <p class="lead fs-5">Создаем сайты которые работают на результат</p>
        </div>

        <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <?php
                // Разбиваем услуги на группы по 3 для ПК (телефон через CSS покажет по 1)
                $chunks = array_chunk($services, 3);
                foreach ($chunks as $index => $chunk):
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($chunk as $service): ?>
                        <div class="col-12 col-md-4">
                            <div class="service-card text-center p-4 border rounded shadow-sm h-100">
                                <i class="bi <?php echo htmlspecialchars($service['icon']); ?> fs-1 text-primary mb-3"></i>
                                <h3 class="h5 fw-bold mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="small text-muted" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"><?php echo htmlspecialchars($service['description']); ?></p>
                                <div class="mt-3">
                                    <span class="badge bg-primary"><?php echo number_format($service['price']/1000, 0); ?>K ₽</span>
                                    <span class="badge bg-success"><?php echo $service['duration']; ?>д</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

<!-- ПОРТФОЛИО - СЛАЙДЕР (на ПК 3 карточки, на телефоне 1) -->
<section class="portfolio py-5" id="portfolio">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">Портфолио</h2>
            <p class="lead">Реальные проекты для реальных клиентов</p>
        </div>

        <div id="portfolioCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <?php
                $chunks_p = array_chunk($portfolio_items, 3);
                foreach ($chunks_p as $idx => $chunk):
                ?>
                <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($chunk as $item): ?>
                        <div class="col-12 col-md-4">
                            <div class="portfolio-item border rounded shadow-sm p-3 h-100">
                                <img src="Image/<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid w-100" style="height: 180px; object-fit: cover; border-radius: 12px;" alt="<?php echo htmlspecialchars($item['customer']); ?>" onerror="this.src='Image/CR.png'">
                                <div class="mt-3">
                                    <h3 class="h5 fw-bold mb-1"><?php echo htmlspecialchars($item['customer']); ?></h3>
                                    <p class="small text-muted"><?php echo htmlspecialchars($item['site_type']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="portfolio.php" class="btn btn-modern">Посмотреть больше проектов</a>
        </div>
    </div>
</section>

<!-- ОТЗЫВЫ - СЛАЙДЕР -->
<section class="reviews py-5" id="reviews">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">Что говорят клиенты</h2>
            <p class="lead">Реальные отзывы наших клиентов</p>
        </div>

        <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <?php
                $reviews_main = $db->query("SELECT * FROM otzyvy WHERE is_active = 1 ORDER BY id DESC");
                $reviews_chunks = array_chunk($reviews_main->fetch_all(MYSQLI_ASSOC), 3);
                foreach ($reviews_chunks as $ridx => $rchunk):
                ?>
                <div class="carousel-item <?php echo $ridx === 0 ? 'active' : ''; ?>">
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($rchunk as $rev): ?>
                        <div class="col-12 col-md-4">
                            <div class="review-card text-center shadow-sm p-4 h-100">
                                <div class="mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo $i <= $rev['rating'] ? '-fill' : ''; ?> text-warning fs-5"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-3">"<?php echo htmlspecialchars(mb_substr($rev['text'], 0, 100)); ?>"</p>
                                <hr>
                                <h3 class="h6 fw-bold mb-0"><?php echo htmlspecialchars($rev['customer_name']); ?></h3>
                                <small class="text-muted"><?php echo date('d.m.Y', strtotime($rev['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="reviews.php" class="btn btn-modern">Все отзывы</a>
        </div>
    </div>
</section>

<section class="cta py-5" id="callback">
    <div class="container text-center">
        <h2 class="display-4 fw-bold mb-4">Готовы запустить ваш проект?</h2>
        <p class="lead mb-5">Оставьте заявку и получите консультацию бесплатно</p>
        <a href="register.php" class="btn btn-modern btn-lg">Оставить заявку</a>
    </div>
</section>

<footer id="contacts">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h3 class="h4 fw-bold mb-4">SR-<span class="text-warning">Studio</span></h3>
                <p class="mb-4 opacity-90">Веб-студия в Ярославле. Создаем современные сайты которые приносят клиентов 24/7.</p>
                <div class="social-icons">
                    <a href="https://t.me/sr_studio"><img src="Image/telegram.png" alt="Telegram" width="24"></a>
                    <a href="https://vk.com/sr_studio"><img src="Image/vk.png" alt="ВКонтакте" width="24"></a>
                    <a href="https://instagram.com/sr_studio"><img src="Image/Instagram.png" alt="Instagram" width="24"></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Услуги</h6>
                <ul class="list-unstyled">
                    <li><a href="services.php" class="text-white-50 text-decoration-none">Дизайн сайтов</a></li>
                    <li><a href="services.php" class="text-white-50 text-decoration-none">Разработка</a></li>
                    <li><a href="services.php" class="text-white-50 text-decoration-none">SEO-продвижение</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Контакты</h6>
                <a href="tel:+79611626650" class="text-white-50 d-block"><i class="bi bi-telephone-fill me-2"></i>+7 961 162 66 50</a>
                <a href="mailto:maxkryukovsky@gmail.com" class="text-white-50 d-block"><i class="bi bi-envelope-fill me-2"></i>maxkryukovsky@gmail.com</a>
                <p class="text-white-50 small mt-2"><i class="bi bi-geo-alt-fill me-1"></i>г. Ярославль</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Цены</h6>
                <p class="text-white-50 small">от 25 000 ₽</p>
            </div>
        </div>
        <hr>
        <div class="text-center text-white-50 small">© 2026 SR-Studio</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>