<?php

define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY') ?: die('env ENCRYPTION_KEY not set'));


define('ERR_REP_TELEGRAM_BOT_TOKEN', getenv('ERR_REP_TELEGRAM_BOT_TOKEN') ?: die('env ERR_REP_TELEGRAM_BOT_TOKEN not set'));
define('ERR_REP_TELEGRAM_TARGET_CHAT', getenv('ERR_REP_TELEGRAM_TARGET_CHAT') ?: die('env ERR_REP_TELEGRAM_TARGET_CHAT not set'));
