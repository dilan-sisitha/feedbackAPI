<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Haruncpi\LaravelLogReader\LaravelLogReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function view;

class DashboardController extends Controller
{

    public function show()
    {
        return view('dashboard')->with(['api_token'=>Auth::user()->api_token]);
    }
    public function viewLog()
    {
        return view('pages/sys-log');
    }
    public function getLogs(Request $request)
    {
        if ($request->has('date')) {
            return (new LaravelLogReader(['date' => $request->get('date')]))->get();
        } else {
            return (new LaravelLogReader())->get();
        }
    }

}
