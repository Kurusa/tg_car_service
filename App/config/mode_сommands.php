<?php
return [
    \App\Services\UserStatusService::USER_INFO => \App\Commands\RecordUserInfo::class,
    \App\Services\UserStatusService::CAR_INFO => \App\Commands\RecordCarInfo::class,
    \App\Services\UserStatusService::PROMO => \App\Commands\RecordPromoCode::class,
];