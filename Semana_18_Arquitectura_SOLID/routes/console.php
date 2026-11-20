<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('semana18:estado', function () {
    $this->info('Semana 18: capa de servicios, reglas OCP y Enum configurados.');
})->purpose('Comprueba que la aplicación Semana 18 carga correctamente');
