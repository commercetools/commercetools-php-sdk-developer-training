<?php

use App\Http\Controllers\CartsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExtensionsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\StoreShippingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/shipping-methods', [ShippingMethodController::class, 'index']);
Route::get('/shipping-methods/matching-location', [ShippingMethodController::class, 'getShippingMethodsByLocation']);
Route::get('/shipping-methods/{key}', [ShippingMethodController::class, 'getShippingMethodByKey']);
Route::match(['head'], '/shipping-methods/{key}', [ShippingMethodController::class, 'checkShippingMethodExistence']);

Route::get('/project/countries', [ProjectController::class, 'index']);
Route::get('/project/stores', [ProjectController::class, 'getStores']);
Route::get('/products/search', [ProductsController::class, 'search']);

Route::post('/in-store/{storeKey}/carts', [CartsController::class, 'createCart']);
Route::get('/in-store/{storeKey}/carts/{id}', [CartsController::class, 'index']);
Route::post('/in-store/{storeKey}/carts/{id}/lineitems', [CartsController::class, 'addLineItem']);
Route::post('/in-store/{storeKey}/carts/{id}/discount-codes', [CartsController::class, 'addDiscountCode']);
Route::post('/in-store/{storeKey}/carts/{id}/shipping-address', [CartsController::class, 'setShippingAddress']);
Route::post('/in-store/{storeKey}/carts/{id}/shipping-method', [CartsController::class, 'setShippingMethod']);
Route::get('/in-store/{storeKey}/shipping-methods/matching-cart', [ShippingMethodController::class, 'getShippingMethodsMatchingInStoreCart']);

Route::post('/in-store/{storeKey}/customers', [CustomersController::class, 'signupCustomer']);
Route::post('/in-store/{storeKey}/customers/login', [CustomersController::class, 'loginCustomer']);
Route::get('/in-store/{storeKey}/customers/{id}', [CustomersController::class, 'getCustomerById']);


Route::post('/in-store/{storeKey}/orders', [OrdersController::class, 'createOrder']);
Route::get('/in-store/{storeKey}/orders/{id}', [OrdersController::class, 'getOrderById']);
Route::post('/in-store/{storeKey}/orders/{orderNumber}/custom-fields', [OrdersController::class, 'updateOrderCustomFields']);

Route::post('/extensions/types', [ExtensionsController::class, 'createType']);
Route::post('/extensions/custom-objects', [ExtensionsController::class, 'createCustomObject']);
Route::get('/extensions/custom-objects/{container}/{key}', [ExtensionsController::class, 'index']);

Route::get('/imports/summary', [\App\Http\Controllers\ImportsController::class, 'index']);
Route::post('/imports/products', [\App\Http\Controllers\ImportsController::class, 'importProducts']);

Route::get('/graphql/orders', [\App\Http\Controllers\GraphQLController::class, 'index']);
