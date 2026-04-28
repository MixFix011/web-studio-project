<?php
$db = new mysqli('localhost', 'root', '', 'kurs_pr');

if ($db->connect_error) {
    die('Ошибка БД: ' . $db->connect_error);
}

$db->set_charset('utf8');

function checkAdmin() { return true; }
if (!is_dir('Image')) {
    mkdir('Image', 0777, true);
}
?>