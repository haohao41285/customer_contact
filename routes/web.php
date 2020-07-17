<?php

Route::get('/', 'ContactController@index')->name('index');
Route::post('contact', 'ContactController@postForm')->name('contact.post');
Route::get('assign/{customer_id}', 'ContactController@assgin')->name('contact.assgin');
Route::post('assign', 'ContactController@assignPost')->name('contact.assign.post');
Route::get('contact-reporting/{contact_id}/{token}', 'ContactController@finish')->name('contact.finish');
Route::post('contact-finish', 'ContactController@finishPost')->name('contact.finish.post');
Route::post('confirm-from-sheets','ContactController@confirmFromSheets')->name('contact.confirm')