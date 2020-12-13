<?php

namespace validateHelper;

function validNewOrder($orderArray)
{
    if (!isset($orderArray['name']) || !isset($orderArray['surname']) || !isset($orderArray['email']) || !isset($orderArray['phone'])
        || !isset($orderArray['delivery']) || !isset($orderArray['pay'])) {
        return false;
    }
    if ($orderArray['delivery'] === 'dev-yes' && !isset($orderArray['city']) && !isset($orderArray['street']) && !isset($orderArray['home'])
        || !isset($orderArray['apartment'])) {
        return false;
    }
    return true;
}