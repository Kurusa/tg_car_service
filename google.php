<?php

use Carbon\Carbon;

require_once 'vendor/autoload.php';
echo Carbon::now()->addHour()->floorHours()->timestamp;