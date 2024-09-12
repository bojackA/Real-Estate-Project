    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\PostController;
    use App\Http\Controllers\SearchController;
    use App\Http\Controllers\PropertyController;
    use illuminate\Support\Facades\Routes;
    use App\Http\Controllers\dummyAPI;


    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    //User
    Route::post('save', [UserController::class, 'register']);
    Route::post('log', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('user/{id}', [UserController::class, 'showProfile']);



    Route::group(['middleware' =>['auth:sanctum']],function () {
        Route::get('profile', [UserController::class, 'showProfile']);
        Route::post('makePost', [PostController::class, 'createPost']);
        Route::get('viewPeople', [UserController::class, 'viewPeople']);
        Route::post('/findProfile/{id}', [UserController::class, 'showOtherProfile']);
        Route::get('MyApproved', [PostController::class, 'showApprovedPosts']);
        Route::get('ViewListings', [PostController::class, 'showOtherPosts']);
        Route::post('searchListing', [SearchController::class, 'search']);
        Route::post('AddToCart', [PropertyController::class, 'addToCart']); 
        Route::post('/SavedListing/{id}', [PostController::class, 'saveToBookmarks']); 
        Route::get('viewMySaved', [PostController::class, 'viewSavedPosts']);
        Route::post('PostImage',[PostController::class,'store']);
        Route::post('updateProfile',[UserController::class,'updateProfile']);
        Route::post('giveImage',[PostController::class,'uploadImages']);
    });

    //Admin
    Route::post('logAdmin', [AdminController::class, 'login']);
    Route::post('logoutadmin', [AdminController::class, 'logout']);





    Route::group(['middleware' =>['auth:sanctum']],function () {
        Route::delete('/delete-post/{id}', [AdminController::class, 'deletePost']);
        Route::delete('/deleteUser/{id}', [AdminController::class, 'destroy']);
        Route::get('allpost', [AdminController::class, 'showAllPosts']);
        Route::get('view-user', [AdminController::class, 'viewUsers']);
        Route::put('/acceptPost/{id}', [AdminController::class, 'accept']);
        


        // Add other authenticated routes here
    });