<?php

include $_SERVER['DOCUMENT_ROOT'] . '/helpers/validateHelper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';;

if (!empty($_POST)) {
    if (isset($_POST['send_order']) && $_POST['send_order']) {
        $isValidOrder = validateHelper\validNewOrder($_POST);
        if ($isValidOrder) {
            $result = [
                'message' => 'Все четко, все загрузилось',
            ];
        } else {
            $result = [
                'message' => 'Заполните все обязательные поля',
            ];
        }
    }
}