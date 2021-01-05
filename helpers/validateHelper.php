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

/**Функция валидации файла изображения
 * @param $fileArr - массив с информцей о файле
 * @param $imgTypesArr - массив допустимых форматов изображений
 * @return bool - возвращает true в случае успешной валидации, в противном случае - false
 */
function checkFile($fileArr, $imgTypesArr)
{
    if (!in_array($fileArr['productImg']['type'], $imgTypesArr)) {
        return false;
    } else {
        return true;
    }
}

/**Функция валидации информации о продукте
 * @param $productInfoArr - массив данных о товаре
 * @param $productImgArr - массив с данными о изображении товара
 * @return bool - возвращает true в случае успешной валидации, в противном случае - false
 */
function checkNewProduct($productInfoArr, $productImgArr)
{
    if (empty($productInfoArr['productName']) || empty($productInfoArr['productPrice']) || empty($productInfoArr['category'])
        || empty($productImgArr['productImg']['name'])) {
        return false;
    } else {
        return true;
    }
}