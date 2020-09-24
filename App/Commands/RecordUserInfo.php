<?php


namespace App\Commands;


use App\Models\Record;
use App\Services\RecordStatusService;
use App\Services\UserStatusService;

class RecordUserInfo extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->status == UserStatusService::USER_INFO) {
            Record::where('chat_id', $this->user->chat_id)->where('status', RecordStatusService::FILLING)->update([
                'user_info' => $this->parser::getMessage()
            ]);
            $this->triggerCommand(RecordPromoCode::class);
        } else {
            $this->user->status = UserStatusService::USER_INFO;
            $this->user->save();

            $this->tg->sendMessageWithKeyboard('Введите свой телефон, имя, возможно комментарий?', [['отменить']]);
        }
    }
}