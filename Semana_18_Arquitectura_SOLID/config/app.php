<?php
return [
 'name'=>env('APP_NAME','Laravel'),
 'env'=>env('APP_ENV','production'),
 'debug'=>(bool)env('APP_DEBUG',false),
 'url'=>env('APP_URL','http://localhost'),
 'timezone'=>'America/El_Salvador',
 'locale'=>'es',
 'fallback_locale'=>'en',
 'faker_locale'=>'es_SV',
 'key'=>env('APP_KEY'),
 'cipher'=>'AES-256-CBC',
 'maintenance'=>['driver'=>'file'],
];
