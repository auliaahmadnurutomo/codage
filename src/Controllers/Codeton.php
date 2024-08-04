<?php

namespace App\Http\Controllers;

use App\Codeton\SimpleAction;
use App\Codeton\ReturnResponse;
use App\Codeton\MenuAccessControl;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Codeton extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, MenuAccessControl, ReturnResponse, SimpleAction;
}
