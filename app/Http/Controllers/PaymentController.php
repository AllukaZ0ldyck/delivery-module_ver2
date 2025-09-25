<?php

namespace App\Http\Controllers;

use App\Services\MeterService;
use App\Services\GenerateService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $meterService;
    protected $generateService;

    public function __construct(MeterService $meterService, GenerateService $generateService) {
        $this->meterService = $meterService;
        $this->generateService = $generateService;
    }

    // Handles both unpaid and paid tabs
    public function index(Request $request)
    {
        // Get the filter from the request, default to 'unpaid' if not set
        $filter = $request->query('filter', 'unpaid');
        $isPaid = $filter === 'paid';

        // Fetch the bills based on whether they are paid or unpaid
        $bills = $this->meterService::getPayments(null, $isPaid);

        // Pass both the bills and the filter variable to the view
        return view('account-overview.bills', compact('bills', 'filter'));
    }




    // Process payment (online or cash)
    public function pay(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($request->isMethod('post')) {
            $payment_type = $request->input('payment_type'); // cash or online
            $payment_amount = $request->input('payment_amount');

            // Process cash payment
            if ($payment_type === 'cash') {
                return $this->processCashPayment($order, $payment_amount);
            }

            // Process online payment (redirect to payment gateway)
            return $this->processOnlinePayment($order);
        }

        // Show payment form for order
        return view('orders.pay', compact('order'));
    }


    private function processCashPayment(string $reference_no, array $payload)
    {
        $result = $this->meterService::payCashBill($reference_no, $payload);

        if(isset($result['error'])) {
            return redirect()->back()->with('alert', ['status' => 'error', 'message' => $result['error']]);
        }

        return redirect()->back()->with('alert', ['status' => 'success', 'message' => 'Bill paid successfully']);
    }

    private function processOnlinePayment(string $reference_no, array $payload)
    {
        return redirect()->away(env('NOVUPAY_URL') . '/payment/merchants/' . $reference_no);
    }
}
