<?php

use App\Livewire\RoutePlanner;
use Illuminate\Support\Facades\Route;
use App\Livewire\DeliveryDashboard;
use App\Livewire\VehiclesManager;
use App\Livewire\DepotsManager;
use App\Livewire\MainDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\ReportsAnalytics;
use App\Livewire\UsersManager;

Route::view('/', 'welcome');
Route::get('/login', Login::class)->name('login')->middleware('guest');



// باقي صفحات النظام المحمية للمستخدمين المسجلين فقط
Route::middleware(['auth'])->group(function () {

    Route::get('/route-planner', RoutePlanner::class)->name('route.planner');
    Route::get('/delivery-dashboard', DeliveryDashboard::class)->name('delivery.dashboard');
    Route::get('/vehicles-manager', VehiclesManager::class)->name('vehicle.manager');
    Route::get('/depot-manager', DepotsManager::class)->name('depot.manager');
    Route::get('/chart', MainDashboard::class)->name('chart.manager');
    Route::get('/report-analytic', ReportsAnalytics::class)->name('report.analytic');
 Route::get('/user-manager', UsersManager::class)->name('user.manager');
});
