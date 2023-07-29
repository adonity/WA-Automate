<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\OutboxController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\AutoReplyController;
use App\Http\Controllers\BlastGroupController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Contact2Controller;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\IPWhitelistController;
use App\Http\Controllers\ProxyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    //Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Perangkat WhatsApp
    Route::post('devices/import', [DeviceController::class, 'import'])->name('devices.import');
    Route::get('/devices/delete-all', [DeviceController::class, "destroyAll"])->name('devices.delete-all');
    Route::get('/devices/delete-disconnected', [DeviceController::class, "destroyDisconnected"])->name('devices.delete-disconnected');
    Route::resource("devices", DeviceController::class, [
        'names' => [
            'index' => 'devices.index',
            'create' => 'devices.create',
            'store' => 'devices.store',
            'edit' => 'devices.edit',
            'update' => 'devices.update',
            'delete' => 'devices.delete',
            'show' => 'devices.show',
        ]
    ]);
    Route::get('/devices/scan/{nama}', [DeviceController::class, "scan"])->name('devices.scan');
    Route::get('devices/scan/{id}', [DeviceController::class, "tonext"])->name('tonext');

    // Kirim Pesan
    Route::resource("kirim-pesan", OutboxController::class, [
        'names' => [
            'index' => 'outbox.index',
            'create' => 'outbox.create',
            'store' => 'outbox.store',
            'edit' => 'outbox.edit',
            'update' => 'outbox.update',
            'delete' => 'outbox.delete',
            'show' => 'outbox.show',
        ]
    ]);
    Route::get('/kirim-pesan/resend/{id}', [OutboxController::class, "resend"])->name('outbox.resend');


    // Pesan Masuk
    Route::get('/pesan-masuk/get-all', [InboxController::class, 'getAPI']);
    Route::get('/pesan-masuk/get-chat/{id}', [InboxController::class, 'getChatAPI']);
    Route::get('/pesan-masuk/get-chat-list/{id}', [InboxController::class, 'getChatListAPI']);
    Route::get('/pesan-masuk/delete-all', [InboxController::class, "destroyAll"])->name('inbox.delete-all');
    Route::get('/pesan-masuk/set-all-read', [InboxController::class, "setAllRead"])->name('inbox.set-all-read');
    Route::resource("pesan-masuk", InboxController::class, [
        'names' => [
            'index' => 'inbox.index',
            'create' => 'inbox.create',
            'store' => 'inbox.store',
            'edit' => 'inbox.edit',
            'update' => 'inbox.update',
            'delete' => 'inbox.delete',
            'show' => 'inbox.show',
        ]
    ]);

    // Auto Reply
    Route::resource("balas-otomatis", AutoReplyController::class, [
        'names' => [
            'index' => 'autoreply.index',
            'create' => 'autoreply.create',
            'store' => 'autoreply.store',
            'edit' => 'autoreply.edit',
            'update' => 'autoreply.update',
            'delete' => 'autoreply.delete',
            'show' => 'autoreply.show',
        ]
    ]);

    // Broadcast
    Route::resource("blast", BlastGroupController::class, [
        'names' => [
            'index' => 'blast.index',
            'create' => 'blast.create',
            'store' => 'blast.store',
            'edit' => 'blast.edit',
            'update' => 'blast.update',
            'delete' => 'blast.delete',
            'show' => 'blast.show',
        ]
    ]);

    // Kontak
    Route::get('kontak/delete-all', [ContactController::class, 'destroyAll'])->name('kontak.delete-all');
    Route::resource("kontak", ContactController::class, [
        'names' => [
            'index' => 'kontak.index',
            'create' => 'kontak.create',
            'store' => 'kontak.store',
            'edit' => 'kontak.edit',
            'update' => 'kontak.update',
            'delete' => 'kontak.delete',
            'show' => 'kontak.show',
        ]
    ]);
    Route::post('kontak/import', [ContactController::class, 'import'])->name('kontak.import');

    Route::get('chatroom', [ChatRoomController::class, 'index'])->name('chatroom.index');
    Route::get('chatroom/{device_id}/{id}', [ChatRoomController::class, 'show'])->name('chatroom.show');

    // Users
    Route::resource("users", UsersController::class, [
        'names' => [
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'delete' => 'users.delete',
            'show' => 'users.show',
        ]
    ]);

    // Tags
    Route::resource("tags", TagsController::class, [
        'names' => [
            'index' => 'tags.index',
            'create' => 'tags.create',
            'store' => 'tags.store',
            'edit' => 'tags.edit',
            'update' => 'tags.update',
            'delete' => 'tags.delete',
            'show' => 'tags.show',
        ]
    ]);

    // Config
    Route::resource("config", ConfigController::class, [
        'names' => [
            'index' => 'config.index',
            'create' => 'config.create',
            'store' => 'config.store',
            'edit' => 'config.edit',
            'update' => 'config.update',
            'delete' => 'config.delete',
            'show' => 'config.show',
        ]
    ]);

    // IPWhitelist
    Route::resource("ipwhitelist", IPWhitelistController::class, [
        'names' => [
            'index' => 'ipwhitelist.index',
            'create' => 'ipwhitelist.create',
            'store' => 'ipwhitelist.store',
            'edit' => 'ipwhitelist.edit',
            'update' => 'ipwhitelist.update',
            'delete' => 'ipwhitelist.delete',
            'show' => 'ipwhitelist.show',
        ]
    ]);

    // Proxy
    Route::get('proxy/delete-all', [ProxyController::class, 'destroyAll'])->name('proxy.delete-all');
    Route::post('proxy/import', [ProxyController::class, 'import'])->name('proxy.import');
    Route::resource("proxy", ProxyController::class, [
        'names' => [
            'index' => 'proxy.index',
            'create' => 'proxy.create',
            'store' => 'proxy.store',
            'edit' => 'proxy.edit',
            'update' => 'proxy.update',
            'delete' => 'proxy.delete',
            'show' => 'proxy.show',
        ]
    ]);


    // Kontak 2
    Route::get('kontak2/delete-all', [Contact2Controller::class, 'destroyAll'])->name('kontak2.delete-all');
    Route::resource("kontak2", Contact2Controller::class, [
        'names' => [
            'index' => 'kontak2.index',
            'create' => 'kontak2.create',
            'store' => 'kontak2.store',
            'edit' => 'kontak2.edit',
            'update' => 'kontak2.update',
            'delete' => 'kontak2.delete',
            'show' => 'kontak2.show',
        ]
    ]);
    Route::post('kontak2/import', [Contact2Controller::class, 'import'])->name('kontak2.import');
});
