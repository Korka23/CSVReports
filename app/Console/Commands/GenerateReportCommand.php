<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateReportJob;
use Illuminate\Support\Str;

class GenerateReportCommand extends Command
{
    protected $signature = 'report:generate {category_id}';
    protected $description = 'Generate product report for a category';

    public function handle(): void
    {
        $categoryId = $this->argument('category_id');

        $manufacturers = \DB::table('products')
            ->where('category_id', $categoryId)
            ->distinct()
            ->pluck('manufacturer_id');

        if ($manufacturers->isEmpty()) {
            $this->error("No products found for category {$categoryId}");
        }

        foreach ($manufacturers as $manufacturerId) {
            GenerateReportJob::dispatch($categoryId, $manufacturerId);
        }
    }
}
