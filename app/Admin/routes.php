<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $router->post('wechat_materials/broadcast_to_wechat', 'WechatMaterialController@broadcast_to_wechat');
    $router->any('wechat_menus/update_menu_to_wechat', 'WechatMenuController@update_menu_to_wechat');
    $router->any('wechat_users/tagUser', 'WechatUserController@tagUser');

    $router->any('wechat_tags/synchro', 'WechatUserTagController@synchro');
    $router->any('wechat_users/synchro', 'WechatUserController@synchro');
    $router->get('/', 'HomeController@index');
    $router->resource('wechat_users', WechatUserController::class);
    $router->resource('wechat_materials', WechatMaterialController::class);
    $router->resource('wechat_statistics', WechatStatisticsController::class);
    $router->resource('wechat_menus', WechatMenuController::class);
    $router->resource('wechat_broadcast_logs', WechatBroadcastLogController::class);
    $router->resource('wechat_tags', WechatUserTagController::class);
    $router->resource('wechat_messages', WechatMessageController::class);
    $router->resource('wechat_short_links', WechatShortLinkController::class);
    $router->resource('wechat_qrcodes', WechatQrCodeController::class);


});
