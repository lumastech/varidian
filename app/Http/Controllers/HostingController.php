<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Inertia\Inertia;
use Inertia\Response;

class HostingController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('marketing/Hosting', [
            'plans' => Plan::query()->forSale()->get()->map->toMarketingArray(),
        ]);
    }
}
