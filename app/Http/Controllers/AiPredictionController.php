<?php

namespace App\Http\Controllers;

use App\Services\AiPredictionService;
use Illuminate\View\View;

class AiPredictionController extends Controller
{
    public function index(AiPredictionService $service): View
    {
        $this->authorize('ai-predictions.view');

        return view('ai.index', [
            'riskData'    => $service->latePaymentRisk(),
            'vacancyData' => $service->vacancyForecast(),
            'maintData'   => $service->preventiveMaintenance(),
        ]);
    }
}
