<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsertData {
    private $status,
            $data,
            $subject,
            $message,
            $db,
            $request;

    public function __construct(DB $db, Request $request, $data = null) {
        $this->data = $data;
        $this->db = $db;
        $this->request = $request;
    }

    private function InsertData() {
        return $this->db;
    }
}
