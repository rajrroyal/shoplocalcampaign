<?php

use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------
// Public routes
// ---------------------------------------------------------------------

Route::get('/featured', 'FeaturedController@index')->name('featured.index');

// ---------------------------------------------------------------------
// Account routes
// ---------------------------------------------------------------------

// Shopify oAuthRedirect
Route::get('/account/connect/shopify/auth', 'Account\Shopify\ShopifyAuthorizeController@oAuthRedirect')->name('account-connect-shopify-authorize.auth');

Route::prefix('/account')->middleware(['auth'])->group(function() {

    // Add new stores to an account
    Route::prefix('/connect')->group(function() {

        Route::prefix('/powershop')->group(function() {

            // Show authorization "form" (Just a button? Instructions?)
            Route::get('/create', 'Account\Ecwid\EcwidAuthorizeController@create')->name('account-connect-ecwid-authorize.create');

            // Ecwid oAuth callback
            Route::get('/confirm', 'Account\Ecwid\EcwidAuthorizeController@confirm')->name('account-connect-ecwid-authorize.confirm');

            // Update an existing Ecwid shop
            Route::get('/update/{storeId}', 'Account\Ecwid\EcwidUpdateController@index')->name('account-connect-ecwid-update.index');
        });

        Route::prefix('/shopify')->group(function() {

            // Show form asking for shop name
            Route::get('/create', 'Account\Shopify\ShopifyAuthorizeController@create')->name('account-connect-shopify-authorize.create');

            // Shopify oauth callback
            Route::get('/confirm', 'Account\Shopify\ShopifyAuthorizeController@confirm')->name('account-connect-shopify-authorize.confirm');


            // Update an existing Shopify store
            Route::get('/update/{storeId}', 'Account\Shopify\ShopifyUpdateController@index')->name('account-connect-shopify-update.index');

            // webhooks
            // Shopify erase the customer information for that store from your database
            Route::get('/webhook/shop/redact', 'Account\Shopify\ShopifyAuthorizeController@shops_redact')->name('shopify-webhook.shop-redact');

            // Shopify If your app has been granted access to the store's customers or orders,
            //    then you receive a redaction request webhook with the resource IDs that you need to redact or delete.
            //    In some cases, a customer record contains only the customer's email address.
            Route::get('/webhook/customers/redact', 'Account\Shopify\ShopifyAuthorizeController@customers_redact')->name('shopify-webhook.customers-redact');

            // Shopify If your app has been granted access to customers or orders, then you receive a data request webhook
            //    with the resource IDs of the data that you need to provide to the store owner.
            //    It's your responsibility to provide this data to the store owner directly.
            //    In some cases, a customer record contains only the customer's email address.
            Route::get('/webhook/customers/data_request', 'Account\Shopify\ShopifyAuthorizeController@customers_data_request')->name('shopify-webhook.customers-data_request');
        });

        Route::prefix('/shopcity')->group(function() {

            // Show form asking for Shopcity profile URL
            Route::get('/create', 'Account\Shopcity\ShopcitySetupController@create')->name('account-connect-shopcity-setup.create');
            Route::post('/store', 'Account\Shopcity\ShopcitySetupController@store')->name('account-connect-shopcity-setup.store');

            // Update an existing Shopcity listing
            Route::get('/update/{storeId}', 'Account\Shopcity\ShopcityUpdateController@index')->name('account-connect-shopcity-update.index');
        });

        // Select type of store to connect
        Route::get('/', 'Account\ConnectController@index')->name('account-connect.index');
    });

    // Store and product overview
    Route::get('/store/{storeId}/{productId}', 'Account\ProductController@show')->name('account-product.show');
    Route::get('/store/{storeId}', 'Account\StoreController@show')->name('account-store.show');

    Route::get('/', 'Account\DashboardController@index')->name('account-dashboard.index');
});

// ---------------------------------------------------------------------
// Auth routes
// ---------------------------------------------------------------------

Route::get('/register', 'Auth\RegisterController@create')->name('register.create');
Route::post('/register', 'Auth\RegisterController@store')->name('register.store');

Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::post('/login', 'Auth\LoginController@attempt')->name('login.attempt');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ---------------------------------------------------------------------
// Dev
// ---------------------------------------------------------------------

Route::prefix('/dev')->group(function() {

    Route::get('/dump', 'Dev\DumpController@index');
});

// ---------------------------------------------------------------------
// Homepage
// ---------------------------------------------------------------------

Route::get('/', 'HomeController@index')->name('home');
