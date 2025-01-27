<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'admin',
'namespace'=>'Administrator',
'middleware' => ['CORS']],function ()
{
    //sleep(5);
    Route::post('/login', 'AuthAdminController@login');
     //this might be useful though an admin registering another admin
    Route::post("/register","AuthAdminController@register");
    //this is to display all the available roles
    Route::post("/logout","AuthAdminController@logout");
    //display admin profile
    Route::post("/profilephoto/add","AuthAdminController@AddProfilePicture");
    Route::post("/profile","AuthAdminController@getAuthadmin");
    Route::post("/change-password","AuthAdminController@changePassword");
    Route::get("/all-admins/{pagination}","AuthAdminController@viewAdmins");
    Route::post("/save-push-token","AuthAdminController@saveOrUpdatePushToken");
    //users section
    Route::post("/register-staff","AdminManagesStaffController@register");
    Route::get("/all-users/{pagination}","AdminManagesStaffController@LoadUsers");
    Route::get("/user/{id}","AdminManagesStaffController@LoadUser");
    Route::get("/search-users/{searchval}/{pagination}","AdminManagesStaffController@SearchUsers");
   Route::post("/delete-user/{id}","AdminManagesStaffController@DeleteUser");
   Route::post("/create-task","AdminTaskController@CreateTask");
   Route::get("/all-tasks/{pagination}", "AdminTaskController@loadPaginatedTasks");
   Route::get("/search-task/{searchval}/{pagination}","AdminTaskController@SearchTask");
   Route::get("single-task/{id}","AdminTaskController@loadSingleTask");
   Route::post("/dissaprove-task","AdminTaskController@dissaproveTask");
   Route::post("/approve-task","AdminTaskController@approveTask");
   Route::post("/lay-complaints","AdminTaskController@layComplaints");
   Route::post("/update-complaints","AdminTaskController@UpdateComplaints");
   Route::post("/delete-task","AdminTaskController@deleteTask");
   Route::post("/update-task/{id}","AdminTaskController@updateTask");

   //fines
   Route::get("/monthly-balance/{id}","FinancialDisciplineController@loadFinanceForTheMonth");
   Route::get("/get-year/{id}","FinancialDisciplineController@loadYearAvailableForUser");
   Route::post("/full-financial-report","FinancialDisciplineController@GetUserFinancialReport");
   Route::get("/get-daily-login-details","StaffLoginDetailsController@StaffLoginDetails");
   Route::post("/show-full-login-details","StaffLoginDetailsController@ShowFullLoginDetails");
   Route::post("/deduct-money-for-attendance","StaffLoginDetailsController@deductMoneyFromStaff");
   Route::post("/update_deducted-money","StaffLoginDetailsController@UpdateStaffSalaryDeduct");
   Route::get("/get-search-daily-login-details/{search}","StaffLoginDetailsController@GetUserSearchLoginReport");
   Route::post("/delete-deducted-money-for-attendance","StaffLoginDetailsController@DeleteFinancialPunishement");
   Route::post("/deduct-salary-by-default","StaffLoginDetailsController@DeductSalaryByDefault");
   Route::post("/deduct-salary-by-custom","StaffLoginDetailsController@DeductSalaryCustom");
   Route::post("/update-deduct-salary-by-custom","StaffloginDetailsController@UpdateDeductSalaryCustom");
   Route::post("/update-deduct-salary-by-default","StaffloginDetailsController@UpdateDeductSalaryDefault");
});



Route::group(['prefix' => 'staffs',
'namespace'=>'Staff',
'middleware' => ['CORS']],function ()
{
    //sleep(5);
    Route::post('/login', 'AuthStaffController@login');
     //this might be useful though an admin registering another admin
    //this is to display all the available roles
    Route::post("/profilephoto/add/{token}","AuthStaffController@AddProfilePicture");
    Route::post("/logout","AuthStaffController@logout");
    Route::post("/change-password","AuthStaffController@resetPassword");
    //auth login face image
    Route::post("/upload-auth-image/{token}","AuthStaffController@UploadAuthImage");
    //display admin profile
    Route::post("/reset-password-link","AuthStaffController@sendResetPasswordLink");
    Route::get("/profile/{token}","AuthStaffController@getAuthStaff");
    Route::get('/load-tasks/{token}', 'TaskController@LoadTasks');    
    Route::get("/load-full-task/{token}/{id}","TaskController@FullTaskDetails");
    Route::get("/load-search-data/{token}/{date}","TaskController@LoadSearchData");
    Route::get("/monthly-balance/{token}","FinancialReportController@MonthlyReport");
    Route::get("/available-years/{token}","FinancialReportController@loadYearAvailableForUser");
    Route::get("/monthly-search-response/{token}/{month}/{year}","FinancialReportController@GetUserFinancialReport");
    Route::get("/notifications/{token}","NotificationsController@returnStaffNotifications");
    Route::get("/notifications-data/{token}/{pagination}","NotificationsController@loadPaginatedNotificationsData");
    Route::post("/notifications-viewed","NotificationsController@dismissAllNotifications");
    Route::post("/task-notifications-viewed","NotificationsController@dismissTaskNotifications");
});

Route::post("/send/admin-push-notifications","SendPushNotificationsController@AdminGetUserLoginPush")->middleware("CorsPush");
//Route::get("/load-models","LoadModelsController@LoadModels")->middleware("CORS");
