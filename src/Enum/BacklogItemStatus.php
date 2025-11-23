<?php
namespace App\Enum;

enum BacklogItemStatus: string
{
    case PLANNED = 'planned';
    case LISTENING = 'listening';
    case COMPLETED = 'completed';
}

?>