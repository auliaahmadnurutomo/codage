<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Logger {
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

    private function add() {
        $str = Str::uuid();
        $log = [
            'uuid' => $str,
            'id_user' => optional(auth()->user())->id ?? 0,
            'subject' => $this->subject,
            'url' => $this->request->fullUrl(),
            'method' => $this->request->method(),
            'ip' => $this->request->ip(),
            'status' => $this->status,
            'agent' => $this->request->header('user-agent'),
            'data' => json_encode($this->request->all()),
            'data_modify' => json_encode(['data' => $this->data]),
            'message' => $this->message
        ];
        if (env('NGE_LOG')) {
            $this->db::table('log_activity')->insert($log);
        }
    }

    public function success($message='',$subject='') {
        $this->status = 'success';
		// $this->message = $message;
		$this->subject = $subject;
        $this->add();
    }

    public function error($message='',$subject='') {
        $this->status = 'error';
		$this->message = $message->getMessage().'<|>'.$message->getFile().'<|>'.$message->getLine();
		$this->subject = $subject;
        $this->add();
    }
}
