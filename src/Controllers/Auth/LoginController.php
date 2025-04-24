<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Codeton\GenerateMenuSidebar;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use stdClass;

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

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate user input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // If authentication is successful, check user account status
            $user = Auth::user();
            $userInfo = $this->getUserInfo($user);
            
            if ($userInfo->user_status == 0) {
                // If account is inactive, log out user and return error message
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ]);
            }
            
            // If account is active, add information to session
            $access = $this->getAccess($userInfo);
            $menu = new GenerateMenuSidebar();
            
            session([
                'department' => $userInfo->department,
                'position' => $userInfo->position,
                'office' => $userInfo->office,
                'menu' => $menu->getMenuAccess($access), // id_access
                'access' => $access->toArray()
            ]);

            // Redirect to intended page
            return redirect()->intended('home');
        }

        // If authentication fails, return to login page with error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Get user information from database.
     *
     * @param mixed $user
     * @return stdClass
     */
    public function getUserInfo($user): stdClass
    {
        return DB::table('users as a')
            ->leftJoin('skeleton_users_info as b', 'b.id_user', 'a.id')
            ->leftJoin('skeleton_setting_office as c', 'c.id', 'b.id_office')
            ->leftJoin('skeleton_setting_department as d', 'd.id', 'b.id_department')
            ->leftJoin('skeleton_setting_position as e', 'e.id', 'b.id_position')
            ->select(
                'b.status as user_status',
                'b.id_access_template',
                'c.name as office',
                'd.name as department',
                'e.name as position'
            )
            ->where('a.id', $user->id)
            ->first();
    }

    /**
     * Get user access rights.
     *
     * @param stdClass $userInfo
     * @return \Illuminate\Support\Collection
     */
    private function getAccess(stdClass $userInfo): \Illuminate\Support\Collection
    {
        if ($userInfo->id_access_template) {
            $menuAccess = DB::table('skeleton_setting_menu_access as a')
                ->join('skeleton_setting_template_access as b', 'b.id_menu_access', 'a.id')
                ->where('b.id_menu_template', $userInfo->id_access_template)
                ->select('a.*')
                ->orderBy('a.menu_order')
                ->get();
        } else {
            $menuAccess = DB::table('skeleton_setting_menu_access')
                ->orderBy('menu_order')
                ->get();
        }
        
        return $menuAccess;
    }
}
