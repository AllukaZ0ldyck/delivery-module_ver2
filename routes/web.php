<?php

use App\Http\Controllers\AccountOverviewController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentBreakdownController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyTypesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BaseRateController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\DeliveryPersonnelController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BorrowedGallonController;
use App\Http\Controllers\Auth\QrLoginController;
use App\Http\Controllers\AdminProfileController;
use App\Models\Admin;
use App\Models\Order;
use App\Http\Controllers\StaffPersonnelController;
use App\Http\Controllers\TicketController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/home', function () {
    return view('/profile.index'); // or any view you'd like
})->name('home');


Route::get('/', function () {
    return redirect()->to('/login');
});


// Login
Route::get('/login', [LoginController::class, 'index'])->name('auth.index');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login');


//Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register');   // <- standard name

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register');  // <- standard name

// Logout
Route::any('/logout', [LoginController::class, 'logout'])->name('auth.logout');

// -----------------------------
// CUSTOMER ROUTES
// -----------------------------
Route::middleware(['auth'])->prefix('customer')->group(function () {
    Route::prefix('my')->group(function () {
        Route::get('overview', [AccountOverviewController::class, 'index'])
            ->name('account-overview.index');

        Route::get('bills', [AccountOverviewController::class, 'bills'])
            ->name('account-overview.bills');

        Route::get('bills/{reference_no?}', [AccountOverviewController::class, 'bills'])
            ->name('account-overview.bills.reference_no');
    });

    // Route::resource('/profile', ProfileController::class)->names('profile');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/update', function () {
        return redirect()->route('profile.index')
            ->with('alert', [
                'status' => 'warning',
                'message' => 'Profile updates can only be submitted through the form.',
            ]);
    });

    // Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Remove the GET fallback or change its URL
    // Route::get('profile/update', ...) -> comment this out or rename it

    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');



    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    // Route for displaying the payment page (GET) and processing the payment (POST)
    Route::match(['get', 'post'], '/orders/{order}/pay', [PaymentController::class, 'pay'])->name('orders.pay');
    // Simulate payment (for testing/demo purposes)
    // Route::match(['get', 'post'], '/orders/{order}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('orders.simulatePayment');
    Route::post('/orders/{order}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('orders.simulatePayment');


    // Borrow Gallons
    Route::get('/borrow-gallon', [BorrowedGallonController::class, 'create'])->name('borrow-gallon.create');
    Route::post('/borrow-gallon', [BorrowedGallonController::class, 'store'])->name('borrow-gallon.store');
    Route::get('/my-borrowed-gallons', [BorrowedGallonController::class, 'index'])->name('borrow-gallon.index');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

});

// -----------------------------
// ADMIN ROUTES
// -----------------------------
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Order management
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/update-status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Reading & payments
    Route::get('reading', [ReadingController::class, 'index'])->name('reading.index');
    Route::post('reading', [ReadingController::class, 'store'])->name('reading.store');
    Route::get('reading/view/bill/{reference_no}', [ReadingController::class, 'view_bill'])->name('reading.view-bill');
    Route::get('reading/bill/{reference_no}', [ReadingController::class, 'show'])->name('reading.show');
    Route::get('reading/reports/{date?}', [ReadingController::class, 'report'])->name('reading.report');

    Route::get('customers', [CustomerController::class, 'index'])->name('admin.customers.index'); // This is where the route is defined
    Route::get('customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
    Route::get('customers/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::put('customers/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');
    Route::post('customers/{id}/archive', [CustomerController::class, 'archive'])->name('admin.customers.archive');
    Route::post('customers/{id}/unarchive', [CustomerController::class, 'unarchive'])->name('admin.customers.unarchive');

    Route::prefix('users')->group(function () {
        Route::resource('roles', RoleController::class)->names('roles')->only('index','destroy');
        Route::resource('customers', ClientController::class)->names('customers')->except('show');
        Route::get('customers/import', [ClientController::class,'import_view'])->name('customers.import.view');
        Route::post('customers/import', [ClientController::class,'import_action'])->name('customers.import.action');
        Route::resource('personnel', AdminController::class)->names('admin');
    });

    // Delivery Personnel management
    Route::post('orders/{order}/assign-delivery-personnel', [OrderManagementController::class, 'assignDeliveryPersonnel'])->name('admin.orders.assignDeliveryPersonnel');


    Route::prefix('payments')->group(function() {
        Route::get('', [PaymentController::class,'index'])->name('payments.index');
        Route::get('unpaid', [PaymentController::class,'unpaid'])->name('payments.unpaid');
        Route::get('paid', [PaymentController::class,'paid'])->name('payments.paid');
        Route::match(['get','post'],'process/{reference_no}', [PaymentController::class,'pay'])->name('payments.pay');
    });



    Route::prefix('settings')->group(function() {
        Route::resource('property-types', PropertyTypesController::class)->names('property-types')->only('index');
        Route::resource('rates', RatesController::class)->names('rates')->only('index','store');
        Route::resource('base-rate', BaseRateController::class)->names('base-rate')->only('index','store');
        Route::resource('payment-breakdown', PaymentBreakdownController::class)->names('payment-breakdown');
    });

    Route::get('/transactions', [ClientController::class,'index'])->name('transactions');
    Route::get('/reports', [ClientController::class,'index'])->name('reports');

    // Support Tickets
    Route::prefix('support/ticket/submit')->group(function() {
        Route::get('/', [SupportTicketController::class,'create'])->name('admin.support-ticket.create');
        Route::post('/', [SupportTicketController::class,'store'])->name('admin.support-ticket.store');
        Route::get('/{ticket}', [SupportTicketController::class,'show'])->name('admin.support-ticket.show');
        Route::delete('/{ticket}', [SupportTicketController::class,'destroy'])->name('admin.support-ticket.destroy');
        Route::get('/edit/{ticket}', [SupportTicketController::class,'edit'])->name('admin.support-ticket.edit');
        Route::put('/edit/{ticket}', [SupportTicketController::class,'update'])->name('admin.support-ticket.update');
    });


    // Borrow Gallons
    Route::get('borrowed-gallons', [AdminController::class, 'manageBorrowedGallons'])->name('admin.borrowed-gallons');
    Route::post('borrowed-gallons/{id}/approve', [AdminController::class, 'approveBorrowedGallon'])->name('admin.borrowed-gallons.approve');
    Route::post('borrowed-gallons/{id}/update', [AdminController::class, 'updateBorrowedGallon'])->name('admin.borrowed-gallons.update');

    // Sales Reports
    Route::get('/sales-report', [AdminController::class, 'salesReport'])->name('admin.sales-report');

    // Gallons Report
     Route::get('/gallon-dashboard', [AdminController::class, 'gallonDashboard'])->name('admin.gallon-dashboard');


    Route::get('admin/customers/pending', [AdminController::class, 'pendingCustomers'])->name('admin.customers.pending');
    Route::post('admin/customers/{id}/approve', [AdminController::class, 'approveCustomer'])->name('admin.customers.approve');
    Route::post('admin/customers/{id}/reject', [AdminController::class, 'rejectCustomer'])->name('admin.customers.reject');


    Route::match(['post', 'patch'], '/orders/{id}/verify-payment', [OrderManagementController::class, 'verifyPayment'])
    ->name('admin.orders.verifyPayment');

    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)
        ->names('admin.products')
        ->except('show');
    
    Route::patch('products/{id}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])
    ->name('admin.products.toggle');

    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
    ->name('admin.reports.index');

    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
    ->name('admin.reports.export');

    Route::get('/personnels', [AdminController::class, 'personnelsIndex'])->name('admin.personnels.index');
    Route::get('/personnels/create', [AdminController::class, 'personnelsCreate'])->name('admin.personnels.create');
    Route::post('/personnels', [AdminController::class, 'personnelsStore'])->name('admin.personnels.store');
    Route::get('/personnels/{id}/edit', [AdminController::class, 'personnelsEdit'])->name('admin.personnels.edit');
    Route::put('/personnels/{id}', [AdminController::class, 'personnelsUpdate'])->name('admin.personnels.update');
    Route::delete('/personnels/{id}', [AdminController::class, 'personnelsDestroy'])->name('admin.personnels.destroy');




});


Route::middleware(['auth'])->prefix('customer/my')->group(function () {
    // Payments page & tabs, pass filter as query
    Route::get('payments', [PaymentController::class, 'index'])->name('customer-payments.index');
    Route::get('payments/unpaid', [PaymentController::class, 'index'])->name('customer-payments.unpaid')->defaults('filter', 'unpaid');
    Route::get('payments/paid', [PaymentController::class, 'index'])->name('customer-payments.paid')->defaults('filter', 'paid');

    // Payment processing
    Route::match(['get', 'post'], 'payments/process/{reference_no}', [PaymentController::class, 'pay'])->name('customer-payments.pay');

    Route::post('customer/{id}/reactivation-request', [CustomerController::class, 'requestReactivation'])->name('customer.reactivation.request');

});


    Route::middleware(['auth:admin'])->prefix('delivery')->group(function () {
        /**
         * Delivery Dashboard — show assigned orders
         */
        Route::get('orders', function () {
            $admin = Auth::guard('admin')->user();

            // ✅ Ensure user is Delivery personnel
            if (!$admin || $admin->user_type !== 'delivery') {
                return redirect()->route('home')->with('error', 'Access Denied');
            }

            // ✅ Fetch orders assigned to this delivery personnel
            $orders = Order::where('delivery_personnel_id', $admin->id)
                ->with(['user', 'items.product'])
                ->latest()
                ->get();

            return view('delivery-personnel.index', compact('orders'));
        })->name('delivery-personnel.index');

        /**
         * View a specific order
         */
        Route::get('orders/{id}', function ($id) {
            $admin = Auth::guard('admin')->user();

            if (!$admin || $admin->user_type !== 'Delivery') {
                return redirect()->route('home')->with('error', 'Access Denied');
            }

            // ✅ Fetch specific order assigned to this delivery personnel
            $order = Order::with(['user', 'items.product'])
                ->where('delivery_personnel_id', $admin->id)
                ->findOrFail($id);

            return view('delivery-personnel.show', compact('order'));
        })->name('delivery-personnel.show');

        /**
         * Update order delivery status
         */
        Route::post('orders/{id}/update-status', function (Request $request, $id) {
            $admin = Auth::guard('admin')->user();

            if (!$admin || $admin->user_type !== 'Delivery') {
                return redirect()->route('home')->with('error', 'Access Denied');
            }

            $request->validate([
                'status' => 'required|in:out_for_delivery,delivered,cancelled'
            ]);

            $order = Order::where('delivery_personnel_id', $admin->id)->findOrFail($id);

            $order->status = $request->input('status');
            $order->save();

            return redirect()->route('delivery-personnel.index')
                ->with('success', 'Order status updated successfully!');
        })->name('delivery-personnel.updateStatus');
    });


    Route::get('/admin/dashboard/sales-data/{filter}', [App\Http\Controllers\AdminController::class, 'getSalesData'])
    ->name('admin.dashboard.sales-data');

    Route::get('/qr-login/{token}', [QrLoginController::class, 'loginViaQr'])->name('qr.login');


    // staff Dashboard (optional)
    Route::get('staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');


    Route::get('{role}/profile', [AdminProfileController::class, 'show'])->name('role.profile.show');
    Route::get('{role}/profile/edit', [AdminProfileController::class, 'edit'])->name('role.profile.edit');
    Route::post('{role}/profile/update', [AdminProfileController::class, 'update'])->name('role.profile.update');


    

    Route::middleware(['auth:admin'])->prefix('staff')->group(function () {
        Route::get('/dashboard', [StaffPersonnelController::class, 'index'])->name('staff.index');
        Route::get('/view/{type}/{id}', [StaffPersonnelController::class, 'show'])->name('staff.show');

        // Order Processing
        Route::post('/order/{id}/update-status', [StaffPersonnelController::class, 'updateOrderStatus'])->name('staff.updateOrderStatus');

        // Inventory Control
        Route::post('/product/{id}/update-stock', [StaffPersonnelController::class, 'updateInventory'])->name('staff.updateInventory');

        // Borrowed Gallons
        Route::post('/borrowed/{id}/approve', [StaffPersonnelController::class, 'approveBorrowed'])->name('staff.approveBorrowed');
        Route::post('/borrowed/{id}/returned', [StaffPersonnelController::class, 'markReturned'])->name('staff.markReturned');

        // Customer Management (for Staff)
        Route::get('/customer/{id}/manage', [StaffPersonnelController::class, 'manageCustomer'])->name('staff.customer.manage');
        Route::post('/customer/{id}/update', [StaffPersonnelController::class, 'updateCustomer'])->name('staff.customer.update');
        Route::post('/customer/{id}/regenerate-qr', [StaffPersonnelController::class, 'regenerateQr'])->name('staff.customer.regenerateQr');

        Route::get('/customers', [StaffPersonnelController::class, 'listCustomers'])->name('staff.customer.list');

        Route::get('/tickets', [TicketController::class, 'staffIndex'])->name('staff.tickets.index');
        Route::post('/tickets/{id}/update', [TicketController::class, 'updateStatus'])->name('staff.tickets.update');


    });
