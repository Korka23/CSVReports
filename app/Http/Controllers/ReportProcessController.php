<?php

namespace App\Http\Controllers;

use App\Models\ReportProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportProcessController extends Controller
{
    public function index()
    {
        $processes = ReportProcess::query()->orderByDesc('start_datetime')->get();

        return view('report_processes.index', compact('processes'));
    }

    public function download(ReportProcess $process)
    {
        $filePath = storage_path("app/{$process->file_save_path}");

        if (!$process->file_save_path || !file_exists($filePath)) {
            abort(404, 'Файл не найден');
        }

        return response()->download($filePath);
    }
}
