<?php


namespace App\Commands;


use App\Models\Record;
use App\Services\RecordStatusService;
use App\Services\UserStatusService;
use App\TgHelpers\GoogleClient;

class RecordPromoCode extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->status == UserStatusService::PROMO) {
            if ($this->parser::getMessage() == '✅ да' || $this->parser::getMessage() == '❌ нет') {
                if ($this->parser::getMessage() == '✅ да') {
                    $this->tg->sendMessageWithKeyboard('Введите промокод', [['отменить']]);
                    exit;
                }
            } else {
                Record::where('chat_id', $this->user->chat_id)->where('status', RecordStatusService::FILLING)->update([
                    'promo' => $this->parser::getMessage()
                ]);
            }
            $filling_record = Record::where('chat_id', $this->user->chat_id)->where('status', RecordStatusService::FILLING)->first();

            $google = new GoogleClient();
            $google->create('GAB) ' . $filling_record->car_info . ', ' . $filling_record->user_info . ', ' . $filling_record->promo, $filling_record->date, date('c', $filling_record->date + 60 * 30));

            $this->triggerCommand(MainMenu::class, 'Спасибо! ☺️
Вы записаны на регулировку на 📅 ' . date('d-m-Y H:i', $filling_record->date) . ' Харьковская 127, СТО Gold Auto, 3-й въезд 
📱 Тел. мастера 0665210303');
        } else {
            $this->user->status = UserStatusService::PROMO;
            $this->user->save();

            $this->tg->sendMessageWithKeyboard('Есть промо-код?', [['✅ да'], ['❌ нет']]);
        }
    }
}