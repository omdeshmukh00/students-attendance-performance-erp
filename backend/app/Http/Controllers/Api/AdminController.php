<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');

        $name = time().'_'.$file->getClientOriginalName();

        $file->move(storage_path('app/csv'), $name);

        return response()->json([
            'message' => 'CSV uploaded successfully',
            'file' => $name
        ]);
    }
}