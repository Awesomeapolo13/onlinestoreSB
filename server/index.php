<?php

include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/validateHelper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';;

//var_dump(empty('Привет') ? $city = null : $city = 'привет');

if (!empty($_POST)) {

    //Отправление заказа
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

    //Изменение статуса заказа
    if (isset($_POST['changeStatus'])) {
        $message = '';
        $_POST['done'] ? $message = 'Статус заказа изменен на выполнено' : $message = 'Статус заказа изменен на не выполнено';
        requestDBHelper\changeStatus($_POST);
        echo json_encode($message);
    }

    //Удаление товара
    if (isset($_POST['deleteProduct'])) {
        $message = '';
        $request = null;
        $error = false;
        //Проверка целостности информации (в случае успеха - запрос)
        if ($_POST['deleteProduct'] === 'delete' && !empty($_POST['id'])) {
            $request = requestDBHelper\deleteProduct($_POST['id']);
        } else {
            $message = 'Ошибка при удалении товара, попробуйте в другой раз';
            $error = true;
        }
        //Проверка успешности запроса
        if ($request) {
            $message = 'Товар удален';
        } else {
            $message = 'Ошибка при удалении товара, попробуйте в другой раз';
            $error = true;
        }
        echo json_encode($result = [
            'message' => $message,
            'error' => $error
        ]);
    }

    //Добавление товара
    if (isset($_POST['addProduct']) || isset($_POST['changeProduct'])) {
        $message = '';
        $request = null;
        $error = !validateHelper\checkNewProduct($_POST, $_FILES) || $error = !validateHelper\checkFile($_FILES, $imgTypesArr);
        if ($error) {
            $message = 'Заполните все поля и дабавьте изображение! Допустимы изображения форматов: jpg, jpeg, png';
        } else {
            if (empty($_POST['new'])) {
                $_POST['new'] = 0;
            }
            if (empty($_POST['sale'])) {
                $_POST['sale'] = 0;
            }
            //Если делается запрос на изменение, то удалить старое изображение
            if (!empty($_POST['changeProduct'])) {
                unlink($_POST['oldPath']); // не работает, т.к. требует абсолютный путь вплоть до диска!!
            }
            //Загрузка изображения товара
            move_uploaded_file($_FILES['productImg']['tmp_name'], $uploadPath . $_FILES['productImg']['name']);
            //Поле с путем до файла
            $_POST['imgPath'] = $uploadPath . $_FILES['productImg']['name'];
            //Направление соответствующего запроса
            if (!empty($_POST['changeProduct'])) {
                $request = requestDBHelper\changeProduct($_POST);
            } else {
                $request = requestDBHelper\addProduct($_POST);
            }
            //Действия в случае успеха или неудачи запроса
            if ($request) {
                $message = 'Товар успешно добавлен';
            } else {
                $error = true;
                $message = '*Ошибка при добавлении/изменении товара, попробуйте позднее';
            }
        }
        echo json_encode($result = [
            'message' => $message,
            'error' => $error,
        ]);
    }
}

mysqli_close(requestDBHelper\getConnection());
