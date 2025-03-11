<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class ProcessExcelConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $outputPath;
    protected $processedPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $filePath, string $outputPath, string $processedPath)
    {
        $this->filePath = $filePath;
        $this->outputPath = $outputPath;
        $this->processedPath = $processedPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $spreadsheet = IOFactory::load($this->filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $outputFile = $this->outputPath . DIRECTORY_SEPARATOR . pathinfo($this->filePath, PATHINFO_FILENAME) . '.csv';
            $handle = fopen($outputFile, "w");

            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $value = trim($cell->getValue());
                    $rowData[] = $value;
                }
                fputcsv($handle, $rowData);
            }
            fclose($handle);

            // 移动处理后的文件
            rename($this->filePath, $this->processedPath . DIRECTORY_SEPARATOR . basename($this->filePath));
        } catch (\Exception $e) {
            // 记录异常日志
            Log::error('Excel conversion failed: ' . $e->getMessage());
        }
    }
}
