<?php

Route::group(['middleware' => 'web'], function() {
    /**
     * Switch between the included languages
     */
    Route::group(['namespace' => 'Language'], function () {
        require (__DIR__ . '/Routes/Language/Language.php');
    });

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Frontend'], function () {
        require (__DIR__ . '/Routes/Frontend/Frontend.php');
        require (__DIR__ . '/Routes/Frontend/Access.php');
    });
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
    require (__DIR__ . '/Routes/Backend/Article.php');
    require (__DIR__ . '/Routes/Backend/Company.php');

    Route::get('admin.carousel.index','Carousel\CarouselController@index');
    Route::get('admin.carousel.edit','Carousel\CarouselController@edit');
    //
    Route::get('order/data', 'Order\OrderController@data');
    Route::post('order/upload', 'Order\OrderController@upload');
    Route::get('order/{id}/sub',['as' => 'order.sub','uses' => 'Order\SubOrderController@index']);
    Route::get('order/{id}/export',['as' => 'admin.subOrder.export','uses' => 'Order\OrderController@export']);
    Route::get('order/{id}/report',['as' => 'admin.orderID.report','uses' => 'Order\OrderController@report']);
    //申报海关
    Route::get('order/{id}/declear',['as' => 'admin.order.declear','uses' => 'Order\OrderController@declear']);
    //到港 --- 清关 ---已到港
    Route::get('order/{id}/port',['as' => 'admin.order.port','uses' => 'Order\OrderController@port']);
    Route::get('order/{id}/clear',['as' => 'admin.order.clear','uses' => 'Order\OrderController@clear']);
    //获取身份证
    Route::get('order/{id}/ID',['as' => 'admin.orderID.export','uses' => 'Order\OrderController@exportID']);
    Route::get('order/{id}/subData',['as' => 'order.subData','uses' => 'Order\SubOrderController@data']);

    Route::resource('order', 'Order\OrderController');
    Route::get('order/{id}/destroy',['as' => 'admin.order.destroy','uses' => 'Order\OrderController@destroy']);

    Route::get('subOrder/{id}/productData',['as' => 'subOrder.productData','uses' => 'Order\SubOrderController@productData']);
    //签收
    Route::post('subOrder/arrival',['as' => 'admin.subOrder.arrival','uses' => 'Order\SubOrderController@arrival']);
    //派送
    Route::post('subOrder/send',['as' => 'admin.subOrder.send','uses' => 'Order\SubOrderController@send']);
    //查验
    Route::post('subOrder/check',['as' => 'admin.subOrder.check','uses' => 'Order\SubOrderController@check']);
    //放行
    Route::post('subOrder/pass',['as' => 'admin.subOrder.pass','uses' => 'Order\SubOrderController@pass']);

    Route::get('subOrder/{id}/detail',['as' => 'admin.subOrder.detail','uses' => 'Order\SubOrderController@detail']);
});
