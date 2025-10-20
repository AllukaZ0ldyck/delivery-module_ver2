<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily'); // default: daily

        switch ($filter) {
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default: // daily
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
        }

        // Fetch data within range
        $orders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();

        return view('admin.reports.index', compact(
            'orders', 'totalRevenue', 'totalOrders', 'deliveredOrders', 'pendingOrders', 'filter'
        ));
    }

   

    public function export(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $fileName = 'orders_' . $filter . '_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(new OrdersExport($filter), $fileName);
    }

}
