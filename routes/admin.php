<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BannerTextController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DepositRequestController;
use App\Http\Controllers\Admin\Live22\ReportController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\SeniorController;
use App\Http\Controllers\Admin\WithdrawRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin', 'as' => 'admin.',
    'middleware' => ['auth', 'checkBanned'],
], function () {

    Route::get('/changePassword/{user}', [HomeController::class, 'changePassword'])->name('changePassword');
    Route::post('/updatePassword/{user}', [HomeController::class, 'updatePassword'])->name('updatePassword');
    Route::get('/agent-list', [HomeController::class, 'agentList'])->name('agentList');
    Route::get('/player-list', [HomeController::class, 'playerList'])->name('playerList');
    Route::post('balance-up', [HomeController::class, 'balanceUp'])->name('balanceUp');
    Route::get('transaction-list', [TransactionController::class, 'index'])->name('transaction');
    Route::get('logs/{id}', [HomeController::class, 'logs'])
        ->name('logs');

    Route::resource('bannerText', BannerTextController::class);
    //Route::resource('senior', SeniorController::class);
    Route::resource('bank', BankController::class);
    Route::resource('promotion', PromotionController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('master', MasterController::class);
    Route::post('master/{master}/ban', [MasterController::class, 'ban'])->name('master.ban');
    Route::get('master/{master}/deposit', [MasterController::class, 'deposit'])->name('master.deposit');
    Route::post('master/{master}/deposit', [MasterController::class, 'makeDeposit'])->name('master.makeDeposit');
    Route::get('master/{master}/withdraw', [MasterController::class, 'withdraw'])->name('master.withdraw');
    Route::post('master/{master}/withdraw', [MasterController::class, 'makeWithdraw'])->name('master.makeWithdraw');
    Route::get('master/{master}/changePassword', [MasterController::class, 'changePassword'])->name('master.changePassword');
    Route::post('master/{master}/changePassword', [MasterController::class, 'makePassword'])->name('master.makePassword');
    Route::resource('agent', AgentController::class);
    Route::post('agent/{agent}/ban', [AgentController::class, 'ban'])->name('agent.ban');
    Route::get('agent/{agent}/deposit', [AgentController::class, 'deposit'])->name('agent.deposit');
    Route::post('agent/{agent}/deposit', [AgentController::class, 'makeDeposit'])->name('agent.makeDeposit');
    Route::get('agent/{agent}/withdraw', [AgentController::class, 'withdraw'])->name('agent.withdraw');
    Route::post('agent/{agent}/withdraw', [AgentController::class, 'makeWithdraw'])->name('agent.makeWithdraw');
    Route::get('agent/{agent}/changePassword', [AgentController::class, 'changePassword'])->name('agent.changePassword');
    Route::post('agent/{agent}/changePassword', [AgentController::class, 'makePassword'])->name('agent.makePassword');
    Route::resource('player', PlayerController::class);
    Route::post('player/{player}/ban', [PlayerController::class, 'ban'])->name('player.ban');
    Route::get('player/{player}/deposit', [PlayerController::class, 'deposit'])->name('player.deposit');
    Route::post('player/{player}/deposit', [PlayerController::class, 'makeDeposit'])->name('player.makeDeposit');
    Route::get('player/{player}/withdraw', [PlayerController::class, 'withdraw'])->name('player.withdraw');
    Route::post('player/{player}/withdraw', [PlayerController::class, 'makeWithdraw'])->name('player.makeWithdraw');
    Route::get('player/{player}/changePassword', [PlayerController::class, 'changePassword'])->name('player.changePassword');
    Route::post('player/{player}/changePassword', [PlayerController::class, 'makePassword'])->name('player.makePassword');
    Route::get('paymentType', [PaymentTypeController::class, 'index'])->name('paymentType.index');

    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('/', [DepositRequestController::class, 'index'])->name('index');
        Route::post('/{deposit}/approve', [DepositRequestController::class, 'approve'])->name('approve');
        Route::post('/{deposit}/reject', [DepositRequestController::class, 'reject'])->name('reject');
    });
    Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
        Route::get('/', [WithdrawRequestController::class, 'index'])->name('index');
        Route::post('/{withdraw}/approve', [WithdrawRequestController::class, 'approve'])->name('approve');
        Route::post('/{withdraw}/reject', [WithdrawRequestController::class, 'reject'])->name('reject');
    });

    Route::group(['prefix' => 'live22', 'as' => 'live22.'], function () {
        Route::get('/win-lose-report', [ReportController::class, 'index'])->name('wlreport');
        Route::get('/w-l-reports/{id}/detail', [ReportController::class, 'show'])->name('winloseReport.detail');
        Route::get('/agent-win-lose-report', [ReportController::class, 'AgentReport'])->name('Awlreport');

    });
});
