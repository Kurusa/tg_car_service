<?php

namespace App\Commands;

use App\Models\Record;
use App\Services\RecordStatusService;
use App\Services\UserStatusService;

/**
 * Class MainMenu
 * @package App\Commands
 */
class MainMenu extends BaseCommand {

    /**
     * @param bool $par
     */
    function processCommand($par = false)
    {
        // delete possible undone record
        $filling_record = Record::where('chat_id', $this->user->chat_id)->where('status', RecordStatusService::FILLING)->first();
        if ($filling_record) {
            $filling_record->delete();
        }

        $this->user->status = UserStatusService::DONE;
        $this->user->save();

        $this->tg->sendMessageWithKeyboard($par ?: 'Главное меню', [
            ['🗓 Запись на регулировку Сход-развала'], ['История моих посещений'], ['Выявленные дефекты'], ['Карта проезда']
        ]);
    }

}