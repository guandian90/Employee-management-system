<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessExcelConversion;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $envDir = env('APP_DATA_ROOT');
        $path = $envDir . '/data';
        $outPath = $envDir . '/convert';
        $processedPath = $envDir . '/processed';

        // 检查目录是否存在
        if (!File::isDirectory($path)) {
            Log::error("Directory does not exist: $path");
            return response()->json(['status' => 'error', 'message' => 'Directory does not exist'], 400);
        }

        // 检查输出目录是否存在，如果不存在则创建
        if (!File::isDirectory($outPath)) {
            File::makeDirectory($outPath, 0777, true, true);
            Log::info("Output directory created: $outPath");
        }

        // 检查处理目录是否存在，如果不存在则创建
        if (!File::isDirectory($processedPath)) {
            File::makeDirectory($processedPath, 0777, true, true);
            Log::info("Processed directory created: $processedPath");
        }

        // 遍历目录中的所有文件
        $files = File::files($path);

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $extension = $file->getExtension();

            // 检查文件是否为 .xls 文件
            if (strtolower($extension) === 'xls') {
                // 调度队列任务
                ProcessExcelConversion::dispatch($filePath, $outPath, $processedPath);
            }
        }

        return response()->json(['status' => 'queued']);
    }
}
