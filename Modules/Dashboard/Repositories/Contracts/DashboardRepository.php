<?php

namespace Modules\Dashboard\Repositories\Contracts;

use Illuminate\Http\Request;

interface DashboardRepository
{
    public function uniqueEventsDay(string $gaViewId, array $optParams, string $startDate, string $endDate);

    public function uniqueEventsMonth(string $gaViewId, array $optParams, string $startDate, string $endDate);

    public function wishesMonth($whitelabel, string $startDate, string $endDate);

    public function wishesDay($whitelabel, string $startDate, string $endDate);

    public function calculateBrowserData(array $result, array $browsers, int $sum);

    public function calculateResponseData($result, $data, $stack);

    public function loadClickRate($whitelabel, $start, $end);

    public function loadOpenRate($whitelabel, $start, $end);

    public function loadClickRateauto($whitelabel, $start, $end);

    public function loadOpenRateauto($whitelabel, $start, $end);

    public function getFilterCategory(string $category);

    public function getFilterCategoryPosition(string $category);

    public function setFilterCategory(Request $request);

    public function setFilterCategoryPosition($result, string $position, int $id1, int $id2);

    public function setFilterCategoryPositionById($dashboard);
}
