<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function insertCSV(Request $request){
        $csv = $request->file('csv');
        if($csv){
            
        }
    }
}
