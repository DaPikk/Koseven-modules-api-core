<?php
Route::set('api', 'api(/<version>(/<resource>(/<id>)))', ['id' => '[^/]+'])
    ->defaults([
        'directory'  => 'Api',
        'controller' => 'V1',
        'action'     => 'index',
    ]);
