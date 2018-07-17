<?php
require_once('./global.php');
wcf\system\request\RequestHandler::getInstance()->handle('packages');

print_r($_REQUEST);
