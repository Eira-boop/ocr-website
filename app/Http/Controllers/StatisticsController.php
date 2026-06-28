<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalDocuments = Document::count();

        $todayDocuments = Document::whereDate('created_at', today())->count();

        $totalUsers = User::count();

        $successOCR = Document::count();
return view('statistics', compact(
    'totalDocuments',
    'todayDocuments',
    'totalUsers',
    'successOCR'
));
    }
}