<?php

if(isset($_POST['name'])) {
    $name = $_POST['name'];
    $tel = $_POST['phone'];
    $email = $_POST['email'];
}

if(isset($_POST['link'])) {
    $link = $_POST['link'];
} else {
    $link = "//";
}

if(isset($_POST['order'])) {
    $order = $_POST['order'];
} else {
    $order = "не заказано";
}

$data = "\r\nВремя заказа - " . date("d-m-Y H:i:s", time() + 3600) .
        "\r\nИмя - $name" .
        "\r\nТелефон - $tel" .
        "\r\nE-mail - $email" .
        "\r\nСсылка - $link" .
        "\r\nЗаказан пакет услуг - $order\r\n______\r\n";

file_put_contents ("orders.txt", $data, FILE_APPEND);
send_mime_mail('Робот mediacan.ru', 'tomin.artem@yandex.ua', 'Админ', 'Denis1olefirenko@yandex.ua', 'UTF-8', 'UTF-8', 'Уведомление о новом заказе', $data);

function send_mime_mail($name_from, // имя отправителя
                        $email_from, // email отправителя
                        $name_to, // имя получателя
                        $email_to, // email получателя
                        $data_charset, // кодировка переданных данных
                        $send_charset, // кодировка письма
                        $subject, // тема письма
                        $body, // текст письма
                        $html = FALSE, // письмо в виде html или обычного текста
                        $reply_to = FALSE
) {

    $to = mime_header_encode($name_to, $data_charset, $send_charset)
        . ' <' . $email_to . '>';
    $subject = mime_header_encode($subject, $data_charset, $send_charset);
    $from =  mime_header_encode($name_from, $data_charset, $send_charset)
        .' <' . $email_from . '>';
    if($data_charset != $send_charset) {
        $body = iconv($data_charset, $send_charset, $body);
    }
    $headers = "From: $from\r\n";
    $type = ($html) ? 'html' : 'plain';
    $headers .= "Content-type: text/$type; charset=$send_charset\r\n";
    $headers .= "Mime-Version: 1.0\r\n";
    if ($reply_to) {
        $headers .= "Reply-To: $reply_to";
    }

    echo json_encode($to);
    return mail($to, $subject, $body, $headers);
}

function mime_header_encode($str, $data_charset, $send_charset) {
    if($data_charset != $send_charset) {
        $str = iconv($data_charset, $send_charset, $str);
    }
    return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}