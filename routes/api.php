<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MailTemplateController;
use App\Http\Controllers\IndustryTypeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TemporaryMemberController;
use App\Http\Controllers\FieldTypeController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\BusinessCardController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\MatterController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\EventSeminarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    // 認証
    Route::namespace('Auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    // 定義マスタ
    Route::group(['prefix' => 'common'], function () {
        Route::get('/prefectures/all', [CommonController::class, 'prefectures']);
        Route::get('/qualifications/all', [CommonController::class, 'qualifications']);
        Route::get('/grouping-list/all', [CommonController::class, 'groupingList']);
        Route::get('/owned-qualification/all', [CommonController::class, 'ownedQualifications']);

        Route::get('/matter-status/all', [CommonController::class, 'matterStatus']);
        Route::get('/contract-status/all', [CommonController::class, 'contractStatus']);
        Route::get('/agent/all', [CommonController::class, 'agent']);
        Route::get('/dashboard', [CommonController::class, 'dashboard']);
        Route::get('/seminar-time/all', [CommonController::class, 'seminarTimes']);
        Route::get('/advisories/all', [CommonController::class, 'advisories']);
    });

    // 認証済み
    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::get('/local-file-download', function () {
            return view('OK');
        })->name('files.download');

        Route::get('/client/all', [ClientController::class, 'all'])->name('client.all');
        Route::resource('/client', ClientController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);

        Route::group(['prefix' => 'billing'], function () {
            Route::get('/', [BillingController::class, 'index']);
            Route::get('/show/{id}', [BillingController::class, 'show']);
            Route::put('/{id}', [BillingController::class, 'update']);
            Route::get('/{id}/pdf', [BillingController::class, 'pdf']);
            Route::post('/{yearMonth}/fix', [BillingController::class, 'fix']);
            Route::post('/m/exclude', [BillingController::class, 'exclude']);
            Route::post('/m/target', [BillingController::class, 'target']);
            Route::post('/m/{id}/fix', [BillingController::class, 'updateBill']);
            Route::post('/{id}/cancel', [BillingController::class, 'cancel']);
        });

        Route::resource('/m/user', UserController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);

        Route::post('/m/user/{id}/password-reset', [UserController::class, 'resetPassword']);
        Route::post('/m/user/password-update', [UserController::class, 'changePassword']);
        Route::resource('/link', LinkController::class)->only([
            'index', 'show', 'update', 'store'
        ]);
        Route::resource('/m/mail-template-type', MailTemplateController::class)->only([
            'index', 'show', 'update'
        ]);
        Route::get('/m/industry-type/all', [IndustryTypeController::class, 'getAll']);
        Route::resource('/m/industry-type', IndustryTypeController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);
        Route::get('/member/all', [MemberController::class, 'getAll']);
        Route::get('/member/{id}/operating_status', [MemberController::class, 'getOperatingStatus']);
        Route::post('/member/{id}/leave', [MemberController::class, 'leave']);
        Route::post('/member/{id}/restore', [MemberController::class, 'restore']);
        Route::get('/member/{id}/resume', [MemberController::class, 'getResume'])->name('member.resume');
        Route::resource('/member', MemberController::class)->only([
            'index', 'show', 'update'
        ]);
        Route::post('/temporary-member/{code}/approval', [TemporaryMemberController::class, 'approval'])->name('temporary-member.approval');
        Route::post('/temporary-member/{code}/disapproval', [TemporaryMemberController::class, 'disapproval'])->name('temporary-member.disapproval');
        Route::resource('/temporary-member', TemporaryMemberController::class)->only([
            'index', 'show', 'update'
        ]);
        Route::get('/m/field-type/all', [FieldTypeController::class, 'all'])->name('field-type.all');
        Route::resource('/m/field-type', FieldTypeController::class);

        Route::get('/m/format/{id}/download', [FormatController::class, 'download'])->name('format.download');
        Route::resource('/m/format', FormatController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);

        Route::resource('/information', InformationController::class)->only([
            'index', 'store', 'show', 'update', 'destroy'
        ]);

        Route::resource('/matter', MatterController::class)->only([
            'index', 'store', 'show', 'update', 'destroy'
        ]);
        Route::get('/matter/all/user/{user_id}', [MatterController::class, 'showByUser'])->name('matter.showByUser');
        Route::get('/matter/all/client/{client_id}', [MatterController::class, 'showByClient'])->name('matter.showByClient');
        Route::post('/matter/{id}/assign-user/{user_id}', [MatterController::class, 'assignUser'])->name('matter.assignUser');
        Route::post('/matter/{id}/assign-member/{member_id}', [MatterController::class, 'assignMember'])->name('matter.assignMember');
        Route::post('/matter/{id}/automatic-cancel', [MatterController::class, 'automaticCancel'])->name('matter.automaticCancel');
        Route::post('/matter/{id}/manual-cancel', [MatterController::class, 'manualCancel'])->name('matter.manualCancel');
        Route::post('/matter/{id}/entry-stop', [MatterController::class, 'entryStop'])->name('matter.entryStop');
        Route::post('/matter/{id}/unassign-member/{member_id}', [MatterController::class, 'unassignMember'])->name('matter.unassignMember');
        Route::post('/matter/{id}/restart', [MatterController::class, 'restart'])->name('matter.restart');
        Route::post('/matter/{id}/public', [MatterController::class, 'public'])->name('matter.public');
        Route::post('/matter/{id}/private', [MatterController::class, 'private'])->name('matter.private');

        //マスタ管理＠システム設定
        //会費
        Route::get('/s/fee', [SystemSettingController::class, 'feeDetail']);
        Route::post('/s/fee', [SystemSettingController::class, 'feeUpdate']);
        //会員規約
        Route::get('/s/member-policy', [SystemSettingController::class, 'memberPolicyDetail']);
        Route::post('/s/member-policy', [SystemSettingController::class, 'memberPolicyUpdate']);
        //個人情報取り扱い
        Route::get('/s/privacy-policy', [SystemSettingController::class, 'privacyPolicyDetail']);
        Route::post('/s/privacy-policy', [SystemSettingController::class, 'privacyPolicyUpdate']);

    
    	Route::put('/inquiry/{id}/status/restart', [InquiryController::class, 'updateRestart'])->name('inquiry.updateRestart');
        Route::post('/inquiry/{id}/status/start', [InquiryController::class, 'updateStart'])->name('inquiry.updateStart');
        Route::put('/inquiry/{id}/status/support-in-email', [InquiryController::class, 'updateSupportInEmail'])->name('inquiry.updateSupportInEmail');
        Route::post('/inquiry/{id}/status/close', [InquiryController::class, 'updateClose'])->name('inquiry.updateClose');
        Route::post('/inquiry/{id}/comment', [InquiryController::class, 'storeComment'])->name('inquiry.storeComment');
        Route::get('/inquiry/comment/file/{file_id}', [InquiryController::class, 'download'])->name('inquiry.download');
        Route::delete('/inquiry/comment/file/{file_id}', [InquiryController::class, 'destroy'])->name('inquiry.destroy');
        Route::resource('/inquiry', InquiryController::class)->only([
            'index', 'show'
        ]);

        Route::resource('/interview', InterviewController::class)->only([
                'index','show', 'store', 'update'
            ]);
     
        //イベント・セミナー
        Route::post('/event-seminar/{id}/piece/{piece_id}', [EventSeminarController::class, 'updatePiece'])->name('event-seminar.updatePiece');
        Route::post('/event-seminar/{id}/piece', [EventSeminarController::class, 'piece'])->name('event-seminar.piece');
        Route::post('/event-seminar/{id}/restart', [EventSeminarController::class, 'restart'])->name('event-seminar.restart');
        Route::post('/event-seminar/{id}/entry-stop', [EventSeminarController::class, 'entryStop'])->name('event-seminar.entry-stop');
        Route::post('/event-seminar/{id}/private', [EventSeminarController::class, 'private'])->name('event-seminar.private');
        Route::post('/event-seminar/{id}/public', [EventSeminarController::class, 'public'])->name('event-seminar.public');
        Route::post('/event-seminar/{id}/cancel', [EventSeminarController::class, 'cancel'])->name('event-seminar.cancel');

        Route::get('/event-seminar/{id}/piece/{piece_id}/csv', [EventSeminarController::class, 'exportCSV'])->name('event-seminar.exportCSV');
        Route::get('/event-seminar/{id}/entry-info/piece/{piece_id}', [EventSeminarController::class, 'showApplicationComma'])->name('event-seminar.showApplicationComma');
        Route::delete('/event-seminar/{id}/piece/{piece_id}', [EventSeminarController::class, 'deleteComma'])->name('event-seminar.deleteComma');
        Route::post('/event-seminar/{id}/event-stop', [EventSeminarController::class, 'stopEvent'])->name('event-seminar.stopEvent');
        Route::post('/event-seminar/{id}/times', [EventSeminarController::class, 'registerTimes'])->name('event-seminar.registerTimes');
        Route::put('/event-seminar/{id}/times/{time_num}', [EventSeminarController::class, 'updateTimes'])->name('event-seminar.updateTimes');
        Route::delete('/event-seminar/{id}/times/{time_num}', [EventSeminarController::class, 'deleteTimes'])->name('event-seminar.deleteTimes');
        Route::resource('/event-seminar', EventSeminarController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);

    	//名刺作成
        Route::resource('/business-card', BusinessCardController::class)->only([
            'index', 'show', 'update'
        ]);
        Route::post('/business-card/{id}/support', [BusinessCardController::class, 'support']);
        Route::post('/business-card/{id}/complete', [BusinessCardController::class, 'complete']);
        Route::get('/business-card/{id}/download', [BusinessCardController::class, 'download']);
    });
});
