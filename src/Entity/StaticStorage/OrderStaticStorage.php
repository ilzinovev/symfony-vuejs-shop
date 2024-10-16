<?php

namespace App\Entity\StaticStorage;

class OrderStaticStorage
{
    public const ORDER_STATUS_CREATED = 0;
    public const ORDER_STATUS_PROCESSED = 1;
    public const ORDER_STATUS_COMPLECTED = 2;
    public const ORDER_STATUS_DELIVERED = 3;
    public const ORDER_STATUS_DENIED = 4;

    public static function getOrderStatusChoices(): array
    {
        return [
            self::ORDER_STATUS_CREATED    => 'Создан',
            self::ORDER_STATUS_PROCESSED  => 'Обработан',
            self::ORDER_STATUS_COMPLECTED => 'Обработан',
            self::ORDER_STATUS_DELIVERED  => 'Доставлен',
            self::ORDER_STATUS_DENIED     => 'Отклонен',
        ];
    }
}