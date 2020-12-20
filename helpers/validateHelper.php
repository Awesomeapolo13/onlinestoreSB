<?php

namespace validateHelper;

/** Функция валидации формы отправки заказа
 * @param $orderArray - массив с данными о заказе
 * @return bool - true, в случае успешное валидации, false - неуспешной
 */
function validNewOrder($orderArray)
{
    if (empty($orderArray['name']) || empty($orderArray['surname']) || empty($orderArray['email']) || empty($orderArray['phone'])
        || empty($orderArray['delivery']) || empty($orderArray['pay'])) {
        return false;
    }
    if ($orderArray['delivery'] === 'dev-yes' && (empty($orderArray['city']) || empty($orderArray['street']) || empty($orderArray['home'])
        || empty($orderArray['apartment']))) {
        return false;
    }
    return true;
}