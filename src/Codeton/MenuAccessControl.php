<?php
namespace App\Codeton;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

trait MenuAccessControl
{
    public function acl($access_name, $session = 'access'): bool 
    {
        if (!Session::has($session)) {
            // Log session expired
            Log::warning('Session expired or not found. Redirecting to login page.');
            
            // Clear all sessions
            Session::flush();
            
            // Redirect to login page
            header('Location: ' . url('login'));
            exit();
        }

        $sessionAccess = session($session);
        
        if (!is_array($sessionAccess)) {
            Log::error('Session access is not an array');
            return false;
        }

        try {
            return in_array($access_name, array_column($sessionAccess, 'sess_name'));
        } catch (\Throwable $th) {
            Log::error('ACL Error: ' . $th->getMessage());
            return false;
        }
    }
}