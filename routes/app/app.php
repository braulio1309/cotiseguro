<?php

use App\Http\Controllers\App\Roles\RoleController;
use App\Http\Controllers\App\Users\UserController;
use App\Http\Controllers\App\Users\AppUserController;
use App\Http\Controllers\App\Users\UserRoleController;
use App\Http\Controllers\App\Users\UserSocialLinkController;
use App\Http\Controllers\App\Auth\AuthenticateUserController;
use App\Http\Controllers\App\Notification\NotificationController;
use App\Http\Controllers\App\Settings\ReCaptchaSettingController;
use App\Http\Controllers\App\PaymentMethod\StripeController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\CompaniaController;
use Illuminate\Support\Facades\Storage;

Route::get('check', function (){
    dd(redirect(config('app.url') . '/payment-view'));
});
//Route::get('/user/registration', [AuthenticateUserController::class, 'registerView']);

Route::get('/download-pdf/{filename}', function($filename) {
    $path = public_path('/public/resumen_cotizacion_13.pdf');
    
    if (!File::exists($path)) {
        abort(404);
    }

   $filePath = "public/public/{$filename}"; // Ruta relativa en storage
    
    if (!Storage::exists($filePath)) {
        abort(404);
    }

    return Storage::download($filePath);
})->name('download.pdf');

Route::view('/cotizaciones', 'cotizaciones.index')->name('cotizaciones');
Route::view('/cotizaciones/listado', 'cotizaciones.listado')->name('cotizaciones.listado');

Route::view('/companias', 'companias.index')->name('companias');
Route::view('/companias/listado', 'companias.listado')->name('companias.listado');

Route::get('/reset/password', [AuthenticateUserController::class, 'resetPasswordView']);
Route::view('/my-profile', 'user.profile');
Route::view('/users-and-roles', 'user-roles.user-roles')->name('user-role.list');

Route::get('companias/listar', [CompaniaController::class, 'listado'])->name('compania.listar');
Route::get('get/companias', [CompaniaController::class, 'getCompanias'])->name('compania.listar2');

Route::get('cotizaciones/listar', [CotizacionController::class, 'listado'])->name('cotizacion.listar');
Route::post('companias/create', [CompaniaController::class, 'create'])->name('porcino.crear');
Route::post('cotizaciones/create', [CotizacionController::class, 'create'])->name('nacimeintos.crear');
Route::post('edit/compania/{id}', [CompaniaController::class, 'editCompania'])->name('porcino.edit');
Route::post('edit/cotizacion/{id}', [CotizacionController::class, 'edit'])->name('nacimiento.edit');

//User
Route::resource('user-list', UserController::class);

// update user name
Route::post('/update-user-name/{id}', [UserController::class, 'updateUserName']);

// role
Route::get('users/{role}', [RoleController::class, 'getUsersByRole']);

// user
Route::get('all-users', [UserController::class, 'getUsers']);

//users
Route::get('get/users', [AppUserController::class, 'index']);

// role_user
Route::post('attach-user/{role}', [UserRoleController::class, 'store']);
// Route::delete('attach-user/{id}',[UserRoleController::class,'destroy']);

// Sample Pages Routes
Route::view('/blank-page', 'sample-pages.sample');

// Payment Methods
Route::view('/payment-view', 'sample-pages.payment-view');

// roles
Route::get('all-roles', [RoleController::class, 'getAllRoles']);

// ALl Notifications page
Route::get('/all-notifications', [NotificationController::class, 'view']);

Route::get('login-user', [AuthenticateUserController::class, 'show'])
    ->name('user.login-user');

Route::post('change-social-link', [UserSocialLinkController::class, 'update'])
    ->name('user.change-social-link');

//get captcha
Route::get('/get-re-captcha-setting', [ReCaptchaSettingController::class, 'getReCaptchaSettings'])
    ->name('re-captcha-settings.get');


Route::get('stripe', [StripeController::class, 'stripe']);
Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');