<?php

declare(strict_types=1);

final class WebRoutes
{
    public static function routes(): array
    {
        return [
            'home'           => ['method' => 'GET',  'action' => 'home'],
            'auth.login'     => ['method' => 'GET',  'action' => 'login'],
            'auth.authenticate' => ['method' => 'POST', 'action' => 'authenticate'],
            'auth.logout'    => ['method' => 'GET',  'action' => 'logout'],
            'users.index'    => ['method' => 'GET',  'action' => 'index'],
            'users.create'   => ['method' => 'GET',  'action' => 'create'],
            'users.store'    => ['method' => 'POST', 'action' => 'store'],
            'users.edit'     => ['method' => 'GET',  'action' => 'edit'],
            'users.update'   => ['method' => 'POST', 'action' => 'update'],
            'users.delete'   => ['method' => 'POST', 'action' => 'delete'],
            // ESTA ES LA RUTA QUE TE FALTABA:
            'show'     => ['method' => 'GET',  'action' => 'show'],
        ];
    }
}