<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function shopDashboard(Shop $shop): array
    {
        $billBase = Bill::withoutGlobalScope('shop')->where('shop_id', $shop->id);

        $totalRevenue = (float) (clone $billBase)->sum('total');
        $receivedAmount = (float) (clone $billBase)->sum('paid_amount');
        $pendingAmount = (float) (clone $billBase)
            ->whereNotIn('status', ['cancelled'])
            ->sum(DB::raw('total - paid_amount'));

        $monthlySales = (clone $billBase)
            ->selectRaw('DATE_FORMAT(bill_date, "%Y-%m") as month, SUM(total) as amount')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->pluck('amount', 'month');

        $dailySales = (clone $billBase)
            ->whereDate('bill_date', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('DATE(bill_date) as day, SUM(total) as amount')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('amount', 'day');

        $yearlySalesRaw = (clone $billBase)
            ->whereYear('bill_date', now()->year)
            ->selectRaw('MONTH(bill_date) as month_num, COUNT(*) as count, SUM(total) as total')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get();

        $yearlySales = [];
        foreach ($yearlySalesRaw as $row) {
            $yearlySales[(int) $row->month_num] = [
                'count' => (int) $row->count,
                'total' => (float) $row->total,
            ];
        }

        $topCustomers = Customer::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->withSum('bills as revenue', 'total')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $mostSoldProducts = BillItem::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->select('description', DB::raw('SUM(meters) as sold_meters'))
            ->groupBy('description')
            ->orderByDesc('sold_meters')
            ->limit(5)
            ->get();

        $stockOverview = Product::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->orderBy('name')
            ->get(['id', 'name', 'current_stock_meters', 'low_stock_threshold']);

        return compact(
            'totalRevenue',
            'receivedAmount',
            'pendingAmount',
            'monthlySales',
            'dailySales',
            'yearlySales',
            'topCustomers',
            'mostSoldProducts',
            'stockOverview'
        );
    }

    public function platformOverview(): array
    {
        return [
            'activeShops' => Shop::where('is_active', true)->count(),
            'platformRevenue' => (float) Bill::withoutGlobalScope('shop')->sum('total'),
            'totalBills' => Bill::withoutGlobalScope('shop')->count(),
            'totalChallans' => \App\Models\Challan::withoutGlobalScope('shop')->count(),
        ];
    }
}
