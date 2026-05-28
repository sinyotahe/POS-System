<?php

return [
    /*
    | Supported connections: 'file', 'network'
    | - file:    Direct file output (e.g., /dev/usb/lp0, /dev/lp0, LPT1)
    | - network: Network printer (e.g., IP: 192.168.1.100, port: 9100)
    */
    'connection' => env('THERMAL_PRINTER_CONNECTION', 'file'),

    'path' => env('THERMAL_PRINTER_PATH', '/dev/usb/lp0'),

    'ip' => env('THERMAL_PRINTER_IP', '192.168.1.100'),

    'port' => env('THERMAL_PRINTER_PORT', 9100),
];
