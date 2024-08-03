use Illuminate\Support\Facades\Route;
use {{ $folder }}\{{ $file }};

Route::get('/',[{{ $file }}::class,'PageIndex']);
Route::get('create',[{{ $file }}::class,'Create']);
Route::post('store',[{{ $file }}::class,'Store']);
Route::get('detail/{id}',[{{ $file }}::class,'Edit']);
Route::post('update',[{{ $file }}::class,'Update']);
Route::get('activation',[{{ $file }}::class,'Activation']);
Route::get('filter',[{{ $file }}::class,'Filter']);
Route::get('delete',[{{ $file }}::class,'Delete']);