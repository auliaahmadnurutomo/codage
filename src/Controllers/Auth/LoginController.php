<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\Codeton\GenerateMenuSidebar;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }




    public function login(Request $request)
    {
        // Validasi input pengguna
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba untuk mengautentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Jika autentikasi berhasil, periksa status akun pengguna
            $user = Auth::user();
            $userInfo = $this->getUserInfo($user);
            if ($userInfo->user_status == 0) {
                // Jika akun tidak aktif, keluarkan pengguna dan kembalikan pesan error
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ]);
                // dd($userInfo);
            }
            // Jika akun aktif, tambahkan informasi ke session
            $menu_access = DB::table('skeleton_setting_menu_access as a')->join('skeleton_setting_template_access as b','b.id_menu_access','a.id')->where('b.id_menu_template',$userInfo->id_access_template)->select('a.*')->orderBy('a.menu_order')->get();
            // $menu_access = DB::table('skeleton_setting_menu_access as a')->select('a.*')->orderBy('a.menu_order')->get();
            $menu = new GenerateMenuSidebar();
            session([
                'department' => $userInfo->department,
                'position' => $userInfo->position,
                'office' => $userInfo->office,
                'menu' => $menu->get_menu_access($menu_access), //id_access
                'access' => $menu_access->toArray()
            ]);

            // dd(session()->all());
            // Redirect ke halaman yang dituju
            return redirect()->intended('home');
        }

        // Jika autentikasi gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function getUserInfo($user)
    {
        return DB::table('users as a')
            ->leftJoin('skeleton_users_info as b', 'b.id_user', 'a.id')
            ->leftJoin('skeleton_setting_office as c', 'c.id', 'b.id_office')
            ->leftJoin('skeleton_setting_department as d', 'd.id', 'b.id_department')
            ->leftJoin('skeleton_setting_position as e', 'e.id', 'b.id_position')
            ->select('a.status as user_status', 'b.status','b.id_access_template' ,'c.name as office', 'd.name as department', 'e.name as position')
            ->where('a.id', $user->id)->first();
    }

    private function getMenuAccess($user)
    {
    }
}
