<?php

namespace App\Traits;

use Auth;

trait Helper {
    
    protected $currentUser;

    protected $modelUser = \App\User::class;
    protected $modelBook = \App\Book::class;
    protected $modelTransaction = \App\Transaction::class;

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            $this->currentUser = Auth::user();
            return $next($request);
        });
    }

    public function auth_check()
    {
        return ! is_null($this->currentUser);
    }

    public function rand_str($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper($randomString);
    }
}