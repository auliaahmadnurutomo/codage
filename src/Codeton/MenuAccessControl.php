<?php
namespace App\Codeton;

use Illuminate\Support\Facades\Log;

trait MenuAccessControl
{
    public function acl($access_name,$session = 'access'):bool{
        // try {
        //     return in_array($access_name, session($session));
        // } catch (\Throwable $th) {
        //     Log::info('error acl');
        // }
        // return in_array($access_name, session($session));
        return in_array($access_name, array_column(session('access'), 'sess_name'));

        // return true;
    }

}