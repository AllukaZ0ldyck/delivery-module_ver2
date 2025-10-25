<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Order::with('user');

        switch ($this->filter) {
            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'yearly':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            default: // daily
                $query->whereDate('created_at', Carbon::today());
        }

        return $query->get()->map(function ($order) {
            return [
                'Order ID' => $order->id,
                'Customer' => $order->user->name ?? 'N/A',
                'Total' => $order->total_price,
                'Status' => $order->status,
                'Payment' => $order->payment_status,
                'Date' => $order->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Order ID', 'Customer', 'Total', 'Status', 'Payment', 'Date'];
    }
}
