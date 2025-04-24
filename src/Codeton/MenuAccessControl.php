<?php

namespace App\Codeton;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Throwable;

/**
 * Trait MenuAccessControl
 * 
 * Provides access control functionality for menu items
 */
trait MenuAccessControl
{
    /**
     * Check if user has access to the specified menu item
     *
     * @param string $accessName The access name to check
     * @param string $session The session key containing access information
     * @return bool Returns true if user has access, false otherwise
     */
    public function acl(string $accessName, string $session = 'access'): bool 
    {
        if (!Session::has($session)) {
            // Log session expired with context
            Log::warning('Session expired or not found', [
                'session_key' => $session,
                'access_name' => $accessName
            ]);
            
            // Clear all sessions
            Session::flush();
            
            // Redirect to login page using Laravel's redirect helper
            redirect('login')->send();
            exit();
        }

        $sessionAccess = Session::get($session);
        
        if (!is_array($sessionAccess)) {
            Log::error('Session access is not an array', [
                'session_key' => $session,
                'actual_type' => gettype($sessionAccess)
            ]);
            return false;
        }

        try {
            return in_array($accessName, array_column($sessionAccess, 'sess_name'), true);
        } catch (Throwable $exception) {
            Log::error('ACL Error', [
                'message' => $exception->getMessage(),
                'access_name' => $accessName,
                'trace' => $exception->getTraceAsString()
            ]);
            return false;
        }
    }
}