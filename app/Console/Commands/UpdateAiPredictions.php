<?php

namespace App\Console\Commands;

use App\Services\AiPredictionService;
use Illuminate\Console\Command;

class UpdateAiPredictions extends Command
{
    protected $signature   = 'aqari:ai:update';
    protected $description = 'Refresh AI prediction scores (logged to cache for dashboard use)';

    public function handle(AiPredictionService $service): int
    {
        if (!config('aqari.ai_predictions', true)) {
            return 0;
        }

        $risk    = $service->latePaymentRisk()->count();
        $vacancy = $service->vacancyForecast()->count();
        $maint   = $service->preventiveMaintenance()->count();

        $this->info("AI updated: {$risk} risk records, {$vacancy} vacancies, {$maint} maintenance predictions.");
        return 0;
    }
}
