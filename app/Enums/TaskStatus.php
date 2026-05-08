<?php

namespace App\Enums;

enum TaskStatus: string
{
    case INBOX = 'inbox';
    case WAITING = 'waiting';
    case DONE = 'done';

    public function label(): string
    {
        return match($this) {
            self::INBOX => 'Entrada',
            self::WAITING => 'Aguardando',
            self::DONE => 'Concluído',
        };
    }
}