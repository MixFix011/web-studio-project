<?php
/**
 * install.php - Скрипт установки/переустановки базы данных
 * Запустить один раз, затем УДАЛИТЬ файл с сервера!
 */

// ========== НАСТРОЙКИ ПОДКЛЮЧЕНИЯ ==========
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'kurs_pr';

// ========== ПОДКЛЮЧЕНИЕ БЕЗ БАЗЫ ДАННЫХ ==========
$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// ========== УДАЛЯЕМ СТАРУЮ БАЗУ (если есть) ==========
$mysqli->query("DROP DATABASE IF EXISTS `$dbname`");
echo "✅ Старая база удалена<br>";

// ========== СОЗДАЁМ НОВУЮ БАЗУ ==========
$mysqli->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
echo "✅ Новая база создана<br>";

// ========== ВЫБИРАЕМ БАЗУ ==========
$mysqli->select_db($dbname);

// ========== СОЗДАЁМ ТАБЛИЦЫ ==========

// 1. Таблица услуг
$mysqli->query("
CREATE TABLE `services` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `price` int(11) NOT NULL,
    `duration` int(11) NOT NULL,
    `icon` varchar(50) NOT NULL,
    `sort_order` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4
");
echo "✅ Таблица `services` создана<br>";

// 2. Таблица портфолио
$mysqli->query("
CREATE TABLE `portfolio` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `image` varchar(255) NOT NULL,
    `customer` varchar(100) NOT NULL,
    `description` text NOT NULL,
    `site_type` varchar(50) NOT NULL,
    `deadline` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4
");
echo "✅ Таблица `portfolio` создана<br>";

// 3. Таблица отзывов
$mysqli->query("
CREATE TABLE `otzyvy` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `customer_name` varchar(100) NOT NULL,
    `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
    `text` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `is_active` tinyint(1) DEFAULT 1,
    `Likes` int(11) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4
");
echo "✅ Таблица `otzyvy` создана<br>";

// 4. Таблица заявок
$mysqli->query("
CREATE TABLE `zayavki` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `email` varchar(100) DEFAULT NULL,
    `service` varchar(50) NOT NULL,
    `message` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `is_read` tinyint(1) DEFAULT 0,
    `status` enum('новая','в работе','завершена') DEFAULT 'новая',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4
");
echo "✅ Таблица `zayavki` создана<br>";

// ========== ЗАПОЛНЯЕМ ТАБЛИЦЫ ДАННЫМИ ==========

// ----- Услуги (id 19-30) -----
$services = [
    [19, 'Веб-дизайн', 'Создаем современный адаптивный дизайн который привлекает клиентов и повышает конверсию. Уникальные макеты под ваш бренд с учетом трендов 2026', 25000, 5, 'bi-brush', 1, 1],
    [20, 'Верстка HTML/CSS', 'Семантичная быстрая верстка любой сложности. Адаптив под все устройства планшеты телефоны Retina. Чистый валидный код', 30000, 7, 'bi-code-slash', 2, 1],
    [21, 'Frontend JS', 'Интерактивные функции анимации слайдеры формы модалки. React/Vue/Angular по необходимости. Плавные переходы и микроанимации', 45000, 10, 'bi-play-circle', 3, 1],
    [22, 'Backend PHP', 'Базы данных MySQL/PostgreSQL. CMS WordPress/ModX самописные админ-панели. API REST GraphQL безопасность CSRF SQL-инъекции', 55000, 14, 'bi-server', 4, 1],
    [23, 'SEO-продвижение', 'Семантика скорость CoreWebVitals. ТОП-10 Яндекс/Google за 2-3 месяца. Локальное продвижение Ярославль техническая оптимизация', 25000, 30, 'bi-graph-up-arrow', 5, 1],
    [24, 'Техподдержка', 'Обновления плагинов хостинг миграции. Резервное копирование ежедневно мониторинг uptime. Исправление багов за 24 часа', 8000, 30, 'bi-headset', 6, 1],
    [25, 'Лендинги', 'Высокая конверсия A/B-тесты продающие тексты. Funnel-оптимизация триггеры персонализация. Popup формы обратная связь', 45000, 7, 'bi-lightning-charge', 7, 0],
    [26, 'Корпоративные сайты', 'Многостраничники каталог товаров CRM AmoCRM/Bitrix24. Интеграции Яндекс.Метрика Google Analytics чат-боты', 95000, 21, 'bi-building', 8, 1],
    [27, 'Интернет-магазины', 'Корзина оплата Сбербанк/Тинькофф ЮKassa. Доставка СДЭК/Boxberry складской учет личный кабинет B2B/B2C', 150000, 30, 'bi-cart', 9, 0],
    [28, 'Логотип + фирстиль', 'Уникальный брендинг гайдлайны Brandbook. Визитки бланки соцсети аватарки. Векторные исходники AI/EPS/SVG/PDF', 25000, 5, 'bi-palette', 10, 1],
    [29, 'Контент-маркетинг', 'Статьи блог соцсети ВК/Telegram/Instagram. SMM стратегия контент-план вирусный контент. Копирайтинг SEO-тексты', 15000, 30, 'bi-megaphone', 11, 0],
    [30, 'Тестирование QA', 'Полное тестирование нагрузка JMeter кроссбраузер BrowserStack. Безопасность SQLMap XSS безопасность мобильная верстка', 20000, 5, 'bi-bug', 12, 0],
];

foreach ($services as $s) {
    $stmt = $mysqli->prepare("INSERT INTO services (id, title, description, price, duration, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiiisi", $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $s[6], $s[7]);
    $stmt->execute();
}
echo "✅ Данные в `services` добавлены<br>";

// ----- Портфолио -----
$portfolio = [
    [5, '1775564165_69d4f58514eb7.png', 'SuperHi.cv', 'SuperHi.cv provides personalized reviews of creative portfolios, résumés, and cover letters from i...', 'Лендинговый, корпоративный сайт.', 7, '2026-04-07 15:16:05', 1],
    [6, '1775564423_69d4f687c1abb.png', 'ООО «Завод «Лекс»', 'Это официальный ресурс крупного промышленного предприятия, специализирующегося на производстве и продаже спецтехники и навесного оборудования.', 'Корпоративный сайт', 7, '2026-04-07 15:20:23', 1],
    [7, '1775564613_69d4f74535fda.png', 'Intercom', 'Внедрение Fin --- продвинутого ИИ-агента на базе GPT-4 для автоматизации службы поддержки. Проект включает интеграцию Fin в текущую экосистему (чат, почта, мессенджеры), настройку базы знаний и прописывание сложных сценариев (Procedures) для решения запросов без участия оператора. Цель: достижение 50%+ автоматического разрешения тикетов с сохранением высокого качества ответов.', 'SaaS-платформа / AI Service', 7, '2026-04-07 15:23:33', 1],
    [10, '1778196727_69fd20f7aab40.png', 'Кафе «Уют»', 'Онлайн-бронирование столиков, меню, контакты', 'Сайт-визитка', 10, '2026-05-08 02:32:07', 1],
    [11, '1778196750_69fd210e6405f.png', 'Студия йоги «Asana»', 'Запись на занятия, расписание, цены', 'Лендинг', 7, '2026-05-08 02:32:30', 1],
    [12, '1778196778_69fd212a253ca.png', 'Автосервис «Мастер»', 'Запись на ремонт, прайс-лист, контакты', 'Корпоративный сайт', 21, '2026-05-08 02:32:58', 1],
];

foreach ($portfolio as $p) {
    $stmt = $mysqli->prepare("INSERT INTO portfolio (id, image, customer, description, site_type, deadline, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssisi", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
    $stmt->execute();
}
echo "✅ Данные в `portfolio` добавлены<br>";

// ----- Отзывы -----
$otzyvy = [
    [1, 'Анна Петрова', 5, 'Отличная работа! Сайт сделали быстро и качественно. Очень довольна результатом!', '2026-04-07 12:06:40', 1, 10],
    [2, 'Михаил Сидоров', 4, 'Хороший сервис, сделали лендинг за неделю. Немного подправить пришлось, но в целом ок.', '2026-04-07 12:06:40', 1, 2],
    [3, 'Елена Козлова', 5, 'Рекомендую! Профессиональный подход, креативный дизайн, соблюдены сроки.', '2026-04-07 12:06:40', 1, 0],
    [4, 'Дмитрий Иванов', 3, 'Сайт сделали, но функционал не полностью работает. Пришлось дорабатывать.', '2026-04-07 12:06:40', 0, 3],
    [5, 'Ольга Смирнова', 5, 'Супер команда! Корпоративный сайт получился современным и удобным.', '2026-04-07 12:06:40', 1, 2],
    [6, 'Алексей Морозов', 5, 'Все четко по ТЗ, без задержек. Буду заказывать еще!', '2026-04-07 12:06:40', 1, 3],
    [7, 'Максим Крюковский', 4, 'Работа выполнена прекрасно!', '2026-05-08 01:51:10', 1, 0],
    [8, 'Максим Крюковский', 5, 'Работа по продвижению выполнена на уровне!', '2026-05-08 01:58:51', 1, 0],
];

foreach ($otzyvy as $o) {
    $stmt = $mysqli->prepare("INSERT INTO otzyvy (id, customer_name, rating, text, created_at, is_active, Likes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissii", $o[0], $o[1], $o[2], $o[3], $o[4], $o[5], $o[6]);
    $stmt->execute();
}
echo "✅ Данные в `otzyvy` добавлены<br>";

// ----- Заявки -----
$zayavki = [
    [1, 'Анна Иванова', '+7(999)123-45-67', 'anna@test.ru', 'лендинг', 'Нужен современный лендинг для моего салона красоты. 1 главная страница + форма заявки.', '2026-04-07 12:28:58', 1, 'новая'],
    [2, 'Михаил Петров', '+7(912)456-78-90', 'mike@mail.ru', 'сайт', 'Корпоративный сайт для компании. 10 страниц: каталог, услуги, контакты, блог.', '2026-04-07 12:28:58', 1, 'новая'],
    [3, 'Елена Смирнова', '+7(903)111-22-33', NULL, 'дизайн', 'Редизайн главной страницы. Текущий дизайн устарел, нужен современный.', '2026-04-07 12:28:58', 1, 'новая'],
    [4, 'Дмитрий Козлов', '+7(905)333-44-55', 'dima@company.ru', 'лендинг', 'Простой лендинг под акцию. Срочно до конца недели!', '2026-04-07 12:28:58', 1, 'новая'],
    [5, 'Ольга Морозова', '+7(908)777-88-99', 'olga@gmail.com', 'сайт', NULL, '2026-04-07 12:28:58', 1, 'новая'],
    [6, 'Сергей Волков', '+7(901)555-66-77', 'sergey@yandex.ru', 'seo', 'Настройка SEO для существующего сайта. Нужно вывести в топ-10 Яндекс.', '2026-04-07 12:28:58', 1, 'новая'],
    [7, 'Крюковский Максим', '+7 (961) 162-66-50', 'maxkryukovsky@gmail.com', '', 'Проект рассчитан на создание динамического сайта угадывания числа и базируется на С1+', '2026-04-07 18:09:01', 1, 'новая'],
    [8, 'Максимс', '7 961 162 66 50', 'gag@gmail.com', 'design', 'afgaf', '2026-04-15 20:24:35', 1, 'в работе'],
    [9, 'одиночка крюк', '+7 (961) 162-66-50', 'maxkryukovsky@gmail.com', 'support', 'Проект обсуждается ЛИЧНО!', '2026-05-08 01:18:36', 0, 'новая'],
    [10, 'Максим', '+7 (961) 162-66-50', 'maxkryukovsky@gmail.com', 'seo', 'Проект по проталкиванию сайта в рейтинги', '2026-05-08 01:58:18', 0, 'новая'],
];

foreach ($zayavki as $z) {
    $stmt = $mysqli->prepare("INSERT INTO zayavki (id, name, phone, email, service, message, created_at, is_read, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssis", $z[0], $z[1], $z[2], $z[3], $z[4], $z[5], $z[6], $z[7], $z[8]);
    $stmt->execute();
}
echo "✅ Данные в `zayavki` добавлены<br>";

// ========== СОЗДАЁМ ПАПКУ ДЛЯ ИЗОБРАЖЕНИЙ ==========
if (!is_dir('Image')) {
    mkdir('Image', 0777, true);
    echo "✅ Папка `Image` создана<br>";
} else {
    echo "⚠️ Папка `Image` уже существует<br>";
}

// ========== ФИНИШ ==========
echo "<hr>";
echo "<h3>🎉 УСТАНОВКА ЗАВЕРШЕНА!</h3>";
echo "<p>База данных `$dbname` создана и заполнена тестовыми данными.</p>";
echo "<p><strong>⚠️ ВАЖНО: УДАЛИТЕ ФАЙЛ `install.php` С СЕРВЕРА ПОСЛЕ УСТАНОВКИ!</strong></p>";
echo "<p><a href='index.php' style='background:#2c5aa0; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>👉 Перейти на сайт</a></p>";

$mysqli->close();
?>