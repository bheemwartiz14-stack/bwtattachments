<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
}
?>
