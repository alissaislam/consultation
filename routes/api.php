<?php

use App\Http\Controllers\BookedDateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExperienceAndDescriptionController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\ExpertsConsultationJoiningTableController;
use App\Http\Controllers\ExpertsConsultationJoiningTablesController;
use App\Http\Controllers\FavoriteExpertController;
use App\Http\Controllers\NormalUserController;
use App\Http\Controllers\ReviewExpertController;
use App\Http\Controllers\TheFreeTimeController;
use App\Http\Controllers\TypesOfConsulatationController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;

use Illuminate\Support\Facades\Route;


//login and singup
Route::post('/singup_normal_user', [NormalUserController::class,'signup_as_normal_user_account']);
//signup_as_expert_account
Route::post('/singup_expert', [NormalUserController::class,'signup_as_expert_account']);
Route::post('/login', [NormalUserController::class,'login']);


//find expert by name
Route::get('/experts/find',[ExpertController::class,'find']);




// expert crud
//Route::post('/experts/create',[ExpertController::class,'create']);



//to show profile and all expert accounts
Route::get('/all_experts',[ExpertController::class,'index']);

//Route::get('/s',[ExpertController::class,'show']);

 //show all consulatations
Route::get('/consulatations/index',[TypesOfConsulatationController::class,'index']);





//auth the token before continuing
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::delete('/delete_user', [NormalUserController::class,'delete_user']);
    Route::post('/logout_from_all_devices', [NormalUserController::class,'logout_from_all_devices']);
    Route::post('/logout', [NormalUserController::class,'logout_from_this_device']);
    Route::post('/update_account', [NormalUserController::class,'update_the_account']);

    //to show my profile
    Route::get('/myprofile',[NormalUserController::class,'index']);
    //to find expert
    Route::get('/experts/find',[ExpertController::class,'find']);
    //to find Consultation
    Route::get('/consultations/find',[TypesOfConsulatationController::class,'find']);
    //to get experts by Consultations
    Route::get('/experts_by_consultation/find',[ExpertsConsultationJoiningTableController::class,'find']);
    Route::get('/get_free_times_with_booked_date',[BookedDateController::class,'get_free_times_with_booked_date']);
    //security bug
    //Route::delete('/experts/{id}',[ExpertController::class,'destroy']);

    Route::get('/profile/{id}',[NormalUserController::class,'index_by_id']);
    Route::get('/allchats',[ChatController::class,'index']);
    //  consulatations crud
 //to make relationship    Consulatations

 Route::post('/expert_add_Consultation',[ExpertsConsultationJoiningTableController::class,'create_by_request'])->middleware('expert');
 //to delete all relationships for one expert
 Route::delete('/delete/Consulatations', [ExpertsConsultationJoiningTableController::class,'destroyAll']);
 //to delete one relationship
 Route::post('/delete/Consulatation', [ExpertsConsultationJoiningTableController::class,'destroy']);



        Route::post('/add_experience', [ExperienceAndDescriptionController::class,'create']);

        Route::post('/delete_experience', [ExperienceAndDescriptionController::class,'destroy']);
        //booking dates routes

        Route::post('/add_free_time', [TheFreeTimeController::class,'create']);

        Route::get('/free_time/{expert_id}', [TheFreeTimeController::class,'index']);


        Route::post('/add_booked_date', [BookedDateController::class,'create']);
        Route::get('/get_all_booked_dates_for_expert', [BookedDateController::class,'index']);


        //add review
        Route::post('/review/add',[ReviewExpertController::class,'addreview']);
        //delete review fav
        Route::delete('/review/delete', [ReviewExpertController::class,'removereview']);

        //get all favorites for the current user
        Route::get('/favorite/myFavorite',[FavoriteExpertController::class,'myFavorite']);
        //delete single fav
        Route::delete('/favorite/delete', [FavoriteExpertController::class,'destroy']);
        //create fav
        Route::post('/favorite/create', [FavoriteExpertController::class,'create']);
});


Route::get('/get_experts_by_id_of_consultations', [TypesOfConsulatationController::class,'get_experts_by_id_of_consultations']);
Route::get('/TypesOfConsulatation/index', [TypesOfConsulatationController::class,'index']);

