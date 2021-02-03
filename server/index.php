<?php

include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/validateHelper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';;

if (!empty($_POST)) {

    //Отправление заказа
    if (isset($_POST['sendOrder']) && $_POST['sendOrder']) {

        $message = 'Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время';
        $requestError = false;
        $isValidOrder = validateHelper\validNewOrder($_POST);
        $createOrder = null;
        $orderPrice = null;

        if (!$isValidOrder) {
            $requestError = true;
            $message = 'Заполните все обязательные поля';
        } else {
            $_POST['productPrice'] >= $limitFreeDelivery ?
                $orderPrice = $_POST['productPrice']
                :
                $orderPrice = $_POST['productPrice'] + $standardDeliveryPrice;
            //Запрос на добавление заказа
            $createOrder = requestDBHelper\createOrder($_POST, $orderPrice);
        }
        if (!$createOrder) {
            $requestError = true;
            $message = 'Ошибка при оформлении заказа. Попробуйте позднее.';
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
        $error = null;
        if (!empty($_POST['admin']) || !empty($_POST['operator'])) {
            $changeStatus = requestDBHelper\changeStatus($_POST);
            if ($changeStatus) {
                $error = false;

            } else {
                $error = true;
                $message = 'Ошибка при изменении статуса заказа. Попробуйте позднее';
            }
        } else {
            $error = true;
            $message = 'У вас недостаточно прав на выполнение этой операции';
        }
        echo json_encode($result = [
            'message' => $message,
            'error' => $error,
        ]);
    }

    // Удаление товара
    if (isset($_POST['deleteProduct'])) {
        $message = '';
        $request = null;
        $error = false;
        //Проверка целостности информации (в случае успеха - запрос)
        if ($_POST['deleteProduct'] === 'delete' && !empty($_POST['id']) && !empty($_POST['admin'])) {
            $request = requestDBHelper\deleteProduct($_POST['id']);

            //Проверка успешности запроса
            if ($request) {
                unlink($uploadPath . $_POST['imgName']);
                $message = 'Товар удален';
            } else {
                $message = 'Ошибка при удалении товара, попробуйте в другой раз';
                $error = true;
            }

        } else {
            $message = 'Ошибка при удалении товара, попробуйте в другой раз';
            $error = true;
        }

        echo json_encode($result = [
            'message' => $message,
            'error' => $error,
        ]);
    }

    // Добавление / Изменение товара
    if (isset($_POST['addProduct']) || isset($_POST['changeProduct'])) {
        $message = '';
        $request = null;
        $_POST['imgName'] = $_FILES['productImg']['name'];
        $error = !validateHelper\checkNewProduct($_POST);
        // Условия проверки файла
        if (isset($_POST['addProduct']) || isset($_POST['changeProduct']) && !empty($_FILES['productImg']['name'])) {
            $error = !validateHelper\checkFile($_FILES, $imgTypesArr);
        }
        if ($error) {
            $message = 'Заполните все поля и дабавьте изображение! Допустимы изображения форматов: jpg, jpeg, png';
        } elseif (empty($_POST['admin']) || !$_POST['admin']) {
            $error = true;
            $message = 'Ошибка доступа к функционалу. Недостаточно прав.';
        } else {
            $imgName = null;
            if (empty($_POST['new'])) {
                $_POST['new'] = 0;
            }
            if (empty($_POST['sale'])) {
                $_POST['sale'] = 0;
            }
            // Если делается запрос на изменение, то удалить старое изображение
            if (isset($_POST['changeProduct']) && !empty($_FILES['productImg']['name'])) {
                unlink($uploadPath . $_POST['oldImg']);
            }
            // Если запрос на добавление или на изменние товара с изменением фотографии, то загрузить новое изображение
            if (isset($_POST['addProduct']) || isset($_POST['changeProduct']) && !empty($_FILES['productImg']['name'])) {
                move_uploaded_file($_FILES['productImg']['tmp_name'], $uploadPath . $_FILES['productImg']['name']);
                $imgName = $_FILES['productImg']['name'];
            } else {
                $imgName = $_POST['oldImg'];
            }

            //Направление соответствующего запроса
            if (isset($_POST['changeProduct'])) {
                $request = requestDBHelper\changeProduct($_POST, $imgName);
            } else {
                $request = requestDBHelper\addProduct($_POST, $imgName);
            }
            //Действия в случае успеха или неудачи запроса
            if ($request) {
                $message = 'Товар успешно добавлен/изменен';
            } else {
                $error = true;
                $message = '*Ошибка при добавлении/изменении товара, попробуйте позднее';
            }
        }
        echo json_encode($result = [
            'message' => $message,
            'error' => $error,
            'data' => $_POST,
            'validFields' => !validateHelper\checkNewProduct($_POST),
            'validImg' => !validateHelper\checkFile($_FILES, $imgTypesArr),
        ]);
    }
}

mysqli_close(requestDBHelper\getConnection());
