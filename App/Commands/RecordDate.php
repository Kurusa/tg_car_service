<?php

namespace App\Commands;

use App\TgHelpers\GoogleClient;
use App\TgHelpers\TelegramKeyboard;
use Carbon\Carbon;

class RecordDate extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->parser::getByKey('a') == 'r_date') {
            $google = new GoogleClient();
            $free = $google->getRecords($this->parser::getByKey('s'), $this->parser::getByKey('e'));
            if ($free) {
                TelegramKeyboard::$list = $free;
                TelegramKeyboard::$button_title = 'time';
                TelegramKeyboard::$columns = 2;
                TelegramKeyboard::$action = 'time';
                TelegramKeyboard::$add_id = 'timestamp';
                TelegramKeyboard::build();
                $this->tg->updateMessageKeyboard($this->parser::getMsgId(), 'Выберите время', TelegramKeyboard::get());
            } else {
                $this->tg->sendMessage('😔 Нет свободного места на этот день');
            }
        } else {

            $week = [
                1 => 'Пон',
                2 => 'Вт',
                3 => 'Ср',
                4 => 'Чт',
                5 => 'Пт',
                6 => 'Сб',
                7 => 'Вт',
            ];

            TelegramKeyboard::addButton('сегодня ' . $week[date('N')] . ' ' . date('d.m'), [
                'a' => 'r_date',
                's' => Carbon::now()->addHour()->floorHours()->timestamp,
                'e' => Carbon::now()->endOfDay()->timestamp
            ]);
            TelegramKeyboard::addButton('завтра ' . $week[date('N', Carbon::now()->addDays(1)->startOfDay()->timestamp)] . ' ' . date('d.m', Carbon::now()->addDays(1)->startOfDay()->timestamp), [
                'a' => 'r_date',
                's' => Carbon::now()->addDays(1)->startOfDay()->timestamp,
                'e' => Carbon::now()->addDays(1)->endOfDay()->timestamp
            ]);
            TelegramKeyboard::addButton('послезавтра ' . $week[date('N', Carbon::now()->addDays(2)->startOfDay()->timestamp)] . ' ' . date('d.m', Carbon::now()->addDays(2)->startOfDay()->timestamp), [
                'a' => 'r_date',
                's' => Carbon::now()->addDays(2)->startOfDay()->timestamp,
                'e' => Carbon::now()->addDays(2)->endOfDay()->timestamp
            ]);

            $this->tg->sendMessageWithInlineKeyboard('📅 Дата регулировки?', TelegramKeyboard::get());
        }
    }

}