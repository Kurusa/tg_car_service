<?php


namespace App\Commands;


use App\Models\Record;
use App\Services\RecordStatusService;
use App\Services\UserStatusService;

class RecordCarInfo extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->status == UserStatusService::CAR_INFO) {
            Record::where('chat_id', $this->user->chat_id)->where('status', RecordStatusService::FILLING)->update([
                'car_info' => $this->parser::getMessage()
            ]);
            $this->triggerCommand(RecordUserInfo::class);
        } else {
            $this->tg->deleteMessage($this->parser::getMsgId());
            Record::create([
                'chat_id' => $this->user->chat_id,
                'date' => $this->parser::getByKey('add_id')
            ]);

            $this->user->status = UserStatusService::CAR_INFO;
            $this->user->save();

            $this->tg->sendMessageWithKeyboard('🚘 Введите Марку, Модель, Год', [['отменить']]);
        }
    }
}