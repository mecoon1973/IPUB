<?php

return [
    "os_system" => env("OS_SYSTEM", PHP_OS_FAMILY),
    "path_libreoffice" => env("PATH_LIBRE_OFFICE", ""),
    "libreoffice_timeout" => env("LIBRE_OFFICE_TIMEOUT", 30),
];
