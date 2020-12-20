<?php

include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/validateHelper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';;

//var_dump(empty('Привет') ? $city = null : $city = 'привет');

if (!empty($_POST)) {
    if (isset($_POST['send_order']) && $_POST['send_order']) {

        $message = 'Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время';
        $requestError = false;
        $isValidOrder = validateHelper\validNewOrder($_POST);

        if (!$isValidOrder) {
            $requestError = true;
            $message = 'Заполните все обязательные поля';
        } else {
            $_POST['productPrice'] >= $limitFreeDelivery ?
                $_POST['orderPrice'] = $_POST['productPrice']
                :
                $_POST['orderPrice'] = $_POST['productPrice'] + $standardDeliveryPrice;

            requestDBHelper\createOrder($_POST);
        }

        $result = [
            'error' => $requestError,
            'message' => $message,
        ];
        echo json_encode($result);
    }
}

mysqli_close(requestDBHelper\getConnection());
