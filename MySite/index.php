<?php
include 'config.php';
$result = $db->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order ASC, id ASC LIMIT 9");
$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}
if (empty($services)) {
    $services = [
        ['icon'=>'bi-brush', 'title'=>'Веб-дизайн', 'description'=>'Современный дизайн', 'price'=>25000, 'duration'=>5],
        ['icon'=>'bi-code-slash', 'title'=>'Разработка', 'description'=>'Быстрая верстка', 'price'=>30000, 'duration'=>7],
        ['icon'=>'bi-graph-up-arrow', 'title'=>'SEO', 'description'=>'ТОП поисковиков', 'price'=>15000, 'duration'=>30],
        ['icon'=>'bi-headset', 'title'=>'Поддержка', 'description'=>'24/7 помощь', 'price'=>5000, 'duration'=>30],
        ['icon'=>'bi-image', 'title'=>'Логотипы', 'description'=>'Уникальный стиль', 'price'=>15000, 'duration'=>3]
    ];
}

$result_portfolio = $db->query("SELECT * FROM portfolio WHERE is_active = 1 ORDER BY id DESC LIMIT 9");
$portfolio_items = [];
while ($row = $result_portfolio->fetch_assoc()) {
    $portfolio_items[] = $row;
}
if (empty($portfolio_items)) {
    $portfolio_items = [
        ['image' => 'Image/Corp_primer.png','customer' => 'Корпоративный сайт','description' => 'Ярославль 2025','site_type' => 'Сайт-визитка','deadline' => 14],
        ['image' => 'Image/Mag_primer.png','customer' => 'Интернет-магазин','description' => 'SEO продвижение','site_type' => 'Интернет-магазин','deadline' => 30],
        ['image' => 'Image/Lending_primer.png','customer' => 'Лендинг','description' => 'Высокая конверсия','site_type' => 'Лендинг','deadline' => 7]
    ];
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
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myCarousel = document.querySelector('#servicesCarousel');
            if (myCarousel) {
                var carousel = new bootstrap.Carousel(myCarousel, {interval: 4000, wrap: true, pause: false});
            }

            var portfolioCarousel = document.querySelector('#portfolioCarousel');
            if (portfolioCarousel) {
                var carousel2 = new bootstrap.Carousel(portfolioCarousel, {interval: 4000, wrap: true, pause: false});
            }
        });
    </script>

    <br>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#about">
                <img src="Image/CR.png" alt="SR-Studio" class="me-2">
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
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#services" class="btn btn-modern btn-lg">Посмотреть услуги</a>
                        <a href="#portfolio" class="btn btn-modern btn-lg">Посмотреть проекты</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <img src="Image/CR.png" alt="SR-Studio" class="img-fluid shadow-lg rounded-4" style="max-height:250px;">
                </div>
            </div>
        </div>
    </section>

    <section class="stats py-5">
    <h2 class="visually-hidden">Наши достижения</h2>
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <span class="stat-number d-block mb-3">+150</span>
                    <div class="h5 fw-bold">сайтов создано</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <span class="stat-number d-block mb-3">95%</span>
                    <div class="h5 fw-bold">довольных клиентов</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <span class="stat-number d-block mb-3">2 мес</span>
                    <div class="h5 fw-bold">ТОП-10 Яндекс</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <span class="stat-number d-block mb-3">24/7</span>
                    <div class="h5 fw-bold">поддержка</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services py-5" id="services">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-6 fw-bold mb-4">Наши услуги</h2>
                <p class="lead fs-5">Создаем сайты которые работают на результат</p>
            </div>
        </div>

        <div id="servicesCarousel" class="carousel slide col-lg-9 mx-auto mb-4" data-bs-ride="carousel" data-bs-interval="4000" data-bs-wrap="true">
            <div class="carousel-inner">
                <?php
                $chunks_s = array_chunk($services, 3);
                foreach ($chunks_s as $index => $chunk):
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row g-3 justify-content-center">
                        <?php foreach ($chunk as $service): ?>
                        <div class="col-md-4">
                            <div class="service-card h-100 text-center p-4 border rounded shadow-sm hover-lift">
                                <i class="bi <?php echo htmlspecialchars($service['icon']); ?> fs-1 text-primary mb-3"></i>
                                <h3 class="h5 fw-bold mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="small mb-3 text-muted">
                                    <?php echo htmlspecialchars(substr($service['description'], 0, 60)); ?>...
                                </p>
                                <div class="mt-auto d-flex justify-content-center gap-2">
                                    <small class="badge bg-primary me-1"><?php echo number_format($service['price']/1000, 0); ?>K ₽</small>
                                    <small class="badge bg-success"><?php echo $service['duration']; ?>д</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-indicators">
                <?php foreach ($chunks_s as $index => $chunk): ?>
                <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-label="Слайд <?php echo $index+1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<section class="portfolio py-5" id="portfolio">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Портфолио</h2>
                <p class="lead">Реальные проекты для реальных клиентов</p>
            </div>
        </div>

        <div id="portfolioCarousel" class="carousel slide col-lg-9 mx-auto mb-4" data-bs-ride="carousel" data-bs-interval="4000" data-bs-wrap="true">
            <div class="carousel-inner">
                <?php
                $chunks_p = array_chunk($portfolio_items, 3);
                foreach ($chunks_p as $idx => $chunk):
                ?>
                <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($chunk as $item): ?>
                        <div class="col-md-4">
                            <div class="portfolio-item h-100 border rounded shadow-sm p-3 d-flex flex-column">
                                <img src="Image/<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid w-100" style="height: 150px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['customer']); ?>" onerror="this.src='Image/Corp_primer.png'">
                                <div class="mt-3 flex-grow-1 d-flex flex-column">
                                    <h3 class="h5 fw-bold mb-1"><?php echo htmlspecialchars($item['customer']); ?></h3>
                                    <p class="mb-1 small text-muted"><?php echo htmlspecialchars($item['site_type']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-indicators">
                <?php foreach ($chunks_p as $idx => $chunk): ?>
                <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>" aria-label="Проекты <?php echo $idx + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>

        <a href="portfolio.php" class="btn w-100 but">Посмотреть больше наших проектов</a>
    </div>
</section>
<section class="reviews py-5" id="reviews">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Что говорят клиенты</h2>
            </div>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-lg-4 col-md-6">
                <div class="review-card text-center shadow-sm p-4 h-100 d-flex flex-column">
                    <div class="mb-4">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="mb-4 flex-grow-1">
                        "Отличная работа! Сайт вышел современным и быстрым. Клиенты пошли сразу"
                    </p>
                    <hr class="text-muted opacity-25">
                    <h3 class="h6 fw-bold mb-0">Иван Петров, директор</h3>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="review-card text-center shadow-sm p-4 h-100 d-flex flex-column">
                    <div class="mb-4">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="mb-4 flex-grow-1">"SEO продвижение на высоте. За 2 месяца в топ-10 Яндекса!"</p>
                    <hr class="text-muted opacity-25">
                    <h3 class="h6 fw-bold mb-0">Мария Сидорова, маркетолог</h3>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="review-card text-center shadow-sm p-4 h-100 d-flex flex-column">
                    <div class="mb-4">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="mb-4 flex-grow-1">
                        "Профессиональный подход и четкое соблюдение сроков. Рекомендую!"
                    </p>
                    <div class="mt-auto">
                        <hr class="my-3 opacity-25">
                        <h3 class="h6 fw-bold mb-0">Алексей Смирнов, владелец бизнеса</h3>
                    </div>
                </div>
            </div>
        </div>
        <a href="reviews.php" class="btn w-100 but mt-4">Все отзывы</a>
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
                <h4 class="h6 fw-bold mb-3">Услуги</h4>
                <ul class="list-unstyled">
                    <li><a href="#services" class="text-white-50 text-decoration-none py-1 d-block small">Дизайн сайтов</a></li>
                    <li><a href="#services" class="text-white-50 text-decoration-none py-1 d-block small">Разработка</a></li>
                    <li><a href="#services" class="text-white-50 text-decoration-none py-1 d-block small">SEO-продвижение</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="h6 fw-bold mb-3">Контакты</h4>
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
                <h4 class="h6 fw-bold mb-3">Цены</h4>
                <div class="mb-3">
                    <p class="text-white-50 small mb-1">от 25 000 ₽</p>
                    <p class="text-white-50 small mb-0">Сайты под ключ</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <div class="text-center text-white-50 small">
                    © 2026 SR-Studio. Все права защищены.
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>