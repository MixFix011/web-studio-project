<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $agree   = !empty($_POST['agree']);

    $errors = [];

    if (strlen($name) < 2 || strlen($name) > 50) {
        $errors['name'] = 'Имя от 2 до 50 символов';
    }

    if (!preg_match('/^\+?7\d{10}$/', preg_replace('/[^\d]/', '', $phone))) {
        $errors['phone'] = 'Некорректный телефон';
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (!$agree) {
        $errors['agree'] = 'Необходимо согласие';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO zayavki (name, phone, email, service, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $name, $phone, $email, $service, $message);
        
        if ($stmt->execute()) {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true]);
            exit;
        } else {
            $errors['db'] = 'Ошибка сохранения';
        }
        $stmt->close();
    }

    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['errors' => $errors]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="Image/CR.png">
    <title>Заявка | SR-Studio Ярославль</title>
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
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="index.php#portfolio">Портфолио</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="about.html">О нас</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="reviews.php">Отзывы</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2 active" href="#">Заявка</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-hero pt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Оставить заявку</h1>
            <p class="lead mb-0">Получите бесплатную консультацию за 15 минут</p>
        </div>
    </section>

    <section class="form-section py-5" id="form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        <div id="formContent">
                            <h2 class="form-title text-center mb-5">Заполните форму</h2>
                            <form id="contactForm" novalidate>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="name" class="form-label fw-bold">Имя *</label>
                                        <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="50">
                                        <div class="invalid-feedback">Имя от 2 до 50 символов</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="phone" class="form-label fw-bold">Телефон *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required placeholder="+7 (___) ___-__-__">
                                        <div class="invalid-feedback">Введите корректный номер телефона</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                        <div class="invalid-feedback">Введите корректный email</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="service" class="form-label fw-bold">Услуга</label>
                                        <select class="form-select" id="service" name="service">
                                            <option value="">Выберите услугу</option>
                                            <option value="design">Веб-дизайн</option>
                                            <option value="development">Разработка сайта</option>
                                            <option value="seo">SEO-продвижение</option>
                                            <option value="support">Поддержка сайта</option>
                                            <option value="other">Другое</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-bold">Комментарий</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Расскажите о вашем проекте..."></textarea>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                                    <label class="form-check-label small" for="agree">
                                        Согласен с <a href="#" class="text-decoration-none">условиями</a> обработки персональных данных
                                    </label>
                                    <div class="invalid-feedback">Необходимо согласие</div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-modern btn-lg px-5" id="submitBtn" disabled>
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>Отправить заявку
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="successMessage" class="success-message d-none text-center p-5">
                            <i class="bi bi-check-circle-fill display-1 mb-4 d-block text-success"></i>
                            <h3 class="mb-4">Заявка отправлена!</h3>
                            <p class="lead mb-4">Мы получили вашу заявку и свяжемся с вами в течение 15 минут</p>
                            <button class="btn btn-light btn-lg px-5" onclick="resetForm()">Отправить новую</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-lg-6">
                    <div class="contact-info text-center">
                        <h3 class="mb-4">Или позвоните прямо сейчас</h3>
                        <div class="contact-item justify-content-center mb-3">
                            <i class="bi bi-telephone-fill text-warning fs-1"></i>
                            <a href="tel:+79611626650" class="text-white h4 fw-bold text-decoration-none d-block mt-2">+7 961 162 66 50</a>
                        </div>
                        <div class="contact-item justify-content-center">
                            <i class="bi bi-clock-fill text-warning"></i>
                            <span class="h6">Работаем 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contacts">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="fw-bold mb-4">SR-<span class="text-warning">Studio</span></h4>
                    <p class="mb-4 opacity-90">Веб-студия в Ярославле. Создаем современные сайты которые приносят клиентов 24/7.</p>
                    <div class="social-icons">
                        <a href="https://t.me/sr_studio" title="Telegram" class="social-icon"><img src="Image/telegram.png" alt="Telegram" width="24" height="24"></a>
                        <a href="https://vk.com/sr_studio" title="ВКонтакте" class="social-icon"><img src="Image/vk.png" alt="ВКонтакте" width="24" height="24"></a>
                        <a href="https://instagram.com/sr_studio" title="Instagram" class="social-icon"><img src="Image/Instagram.png" alt="Instagram" width="24" height="24"></a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const formContent = document.getElementById('formContent');
            const successMessage = document.getElementById('successMessage');

            document.getElementById('agree').addEventListener('change', function () {
                submitBtn.disabled = !this.checked;
            });

            ['name', 'phone', 'email', 'service', 'message'].forEach(function(id) {
                document.getElementById(id).addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (form.checkValidity() === false) {
                    form.classList.add('was-validated');
                    return;
                }

                const formData = new FormData(form);
                const spinner = submitBtn.querySelector('.spinner-border');
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        formContent.classList.add('d-none');
                        successMessage.classList.remove('d-none');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        if (data.errors.db) {
                            alert('Ошибка сервера, попробуйте позже');
                        } else {
                            for (const field in data.errors) {
                                const el = document.getElementById(field);
                                if (el) {
                                    el.classList.add('is-invalid');
                                }
                            }
                            alert('Проверьте форму на ошибки');
                        }
                    }
                })
                .catch(err => {
                    alert('Ошибка соединения, попробуйте позже');
                })
                .finally(() => {
                    spinner.classList.add('d-none');
                });
            });
        });

        function resetForm() {
            document.getElementById('formContent').classList.remove('d-none');
            document.getElementById('successMessage').classList.add('d-none');
            document.getElementById('contactForm').reset();
            document.getElementById('contactForm').classList.remove('was-validated');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('submitBtn').disabled = true;
        }
    </script>
</body>
</html>