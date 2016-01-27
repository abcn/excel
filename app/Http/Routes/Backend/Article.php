<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:18
 */
Route::group([
    'namespace' => 'Article'
],function(){
    Route::get('article/data', 'ArticleController@data');
    /**
     *
     */
    Route::resource('article/type', 'ArticleTypeController');
    /**
     * Specific Article
     */
    Route::group(['prefix' => 'article/type/{id}', 'where' => ['id' => '[0-9]+']], function() {
        Route::get('mark/{status}', 'ArticleController@mark')->name('admin.article.mark')->where(['status' => '[0,1]']);
    });

    Route::resource('article', 'ArticleController');
    /**
     * Specific Article
     */
    Route::group(['prefix' => 'article/{id}', 'where' => ['id' => '[0-9]+']], function() {
        Route::get('mark/{status}', 'ArticleController@mark')->name('admin.article.mark')->where(['status' => '[0,1]']);
    });

});
