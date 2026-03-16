<?php

namespace App\Jobs;

use App\Enums\ProcessStatusEnum;
use App\Models\ReportProcess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $categoryId;
    public int $manufacturerId;

    public function __construct(int $categoryId, int $manufacturerId)
    {
        $this->categoryId = $categoryId;
        $this->manufacturerId = $manufacturerId;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $start = microtime(true);
        $now = now();
        $process = ReportProcess::query()->create([
            'start_datetime' => $now,
            'status' => ProcessStatusEnum::STARTED,
        ]);

        try {
            $finalDir = storage_path("app/reports");
            if (!is_dir($finalDir)) {
                mkdir($finalDir, 0755, true);
            }

            $fileName = "report_{$this->manufacturerId}_{$this->categoryId}_"
                . $now->format('Y-m-d_H-i-s') . ".csv";

            $filePath = "{$finalDir}/{$fileName}";
            $handle = fopen($filePath, 'w');

            fputcsv($handle, ['manufacturer_name','product_name','min_price','max_price']);

            $rows = DB::select("
                SELECT m.name AS manufacturer_name,
                       p.name AS product_name,
                       MIN(pr.price) AS min_price,
                       MAX(pr.price) AS max_price
                FROM products p
                JOIN manufacturers m ON m.id = p.manufacturer_id
                JOIN prices pr ON pr.product_id = p.id
                WHERE p.category_id = :category
                  AND p.manufacturer_id = :manufacturer
                  AND pr.price_date >= now() - interval '7 days'
                GROUP BY m.name, p.name
            ", [
                'category' => $this->categoryId,
                'manufacturer' => $this->manufacturerId
            ]);
            if (empty($rows)) {
                throw new \Exception("No products found for category {$this->categoryId} and manufacturer {$this->manufacturerId}");
            }

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->manufacturer_name,
                    $row->product_name,
                    $row->min_price,
                    $row->max_price
                ]);
            }

            fclose($handle);

            $process->update([
                'status' => ProcessStatusEnum::COMPLETED,
                'file_save_path' => "reports/{$fileName}",
                'exec_time' => (int) now()->diffInSeconds($start),
            ]);

        } catch (\Throwable $e) {

            $process->update([
                'status' => ProcessStatusEnum::FAILED,
                'exec_time' => (int) now()->diffInSeconds($start),
            ]);
            \Log::error("Report failed", [
                'error' => $e->getMessage()
            ]);
            throw  $e;
        }
    }
}
