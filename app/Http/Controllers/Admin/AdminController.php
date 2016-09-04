<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\UserExchangeRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('confirmation');
    }

    public function index()
    {
        return view('admin.admin.index');
    }
}
