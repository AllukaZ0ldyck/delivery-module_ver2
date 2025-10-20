<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\BorrowedGallon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountApprovedMail;
// use App\Models\Gallon;
use App\Models\Admin;
use App\Models\Gallon;
use Carbon\Carbon;


class AdminController extends Controller
{

    public $adminService;
    public $roleService;

    public function __construct(AdminService $adminService, RoleService $roleService) {

        $this->middleware(function ($request, $next) {

            if (!Gate::any(['admin'])) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });

        $this->adminService = $adminService;
        $this->roleService = $roleService;
    }

    public function index()
    {
        $data = $this->adminService::getData();

        if (request()->ajax()) {
            return $this->datatable($data);
        }

        // ─────────────────────────────
        // 📊 DASHBOARD METRICS
        // ─────────────────────────────
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')
            ->where('approval_status', 'approved')
            ->count();
        $totalRevenue = \App\Models\Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // 🆕 New Orders (Today)
        $todayOrders = Order::with(['user', 'items.product'])
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        $newOrdersCount = $todayOrders->count();

        // ─────────────────────────────
        // 💹 SALES TREND: Last 7 Days
        // ─────────────────────────────
        $salesLast7Days = [
            'dates' => [],
            'amounts' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLast7Days['dates'][] = $date->format('M d');
            $salesLast7Days['amounts'][] = Order::where('status', 'delivered')
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        // ─────────────────────────────
        // 🧾 BORROWED GALLON STATS
        // ─────────────────────────────
        $borrowStats = [
            BorrowedGallon::where('status', 'pending')->count(),
            BorrowedGallon::where('status', 'approved')->count(),
            BorrowedGallon::where('status', 'returned')->count()
        ];

        // ─────────────────────────────
        // 💰 SALES SUMMARIES
        // ─────────────────────────────
        // ✅ Sales today and YTD
        $salesToday = \App\Models\Order::whereDate('created_at', today())
            ->where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $salesYTD = \App\Models\Order::whereYear('created_at', now()->year)
            ->where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // ─────────────────────────────
        // RETURN VIEW
        // ─────────────────────────────
        return view('admin.dashboard', compact(
            'data',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'todayOrders',
            'newOrdersCount',
            'salesLast7Days',
            'borrowStats',
            'salesToday',
            'salesYTD'
        ));
    }



    public function create() {

        $roles = $this->roleService::getData();

        return view('admin.form', compact('roles'));
    }

    public function store(Request $request) {

        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role' => 'required|exists:roles,name',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $response = $this->adminService::create($payload);

        if ($response['status'] === 'success') {
            return redirect()->back()->with('alert', [
                'status' => 'success',
                'message' => $response['message']
            ]);
        } else {
            return redirect()->back()->withInput()->with('alert', [
                'status' => 'error',
                'message' => $response['message']
            ]);
        }
    }

    public function edit(int $id) {

        $data = $this->adminService::getData($id);
        $roles = $this->roleService::getData();

        return view('admin.form', compact('data', 'roles'));
    }

    public function update(int $id, Request $request) {

        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'required',
            'email' => [
            'required',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:8|required_with:confirm_password',
            'confirm_password' => 'nullable|same:password|required_with:password',
        ]);

        if($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $response = $this->adminService::update($id, $payload);

        if ($response['status'] === 'success') {
            return redirect()->back()->with('alert', [
                'status' => 'success',
                'message' => $response['message']
            ]);
        } else {
            return redirect()->back()->withInput()->with('alert', [
                'status' => 'error',
                'message' => $response['message']
            ]);
        }

    }

    public function destroy(int $id) {

        $response = $this->adminService::delete($id);

        if ($response['status'] === 'success') {

            return response()->json([
                'status' => 'success',
                'message' => $response['message']
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response['message']
            ]);
        }
    }

    public function datatable($query)
    {
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . route('admin.edit', $row->id) . '"
                            class="btn btn-secondary text-white text-uppercase fw-bold"
                            id="update-btn" data-id="' . e($row->id) . '">
                            <i class="bx bx-edit-alt"></i>
                        </a>
                        <button class="btn btn-danger text-white text-uppercase fw-bold btn-delete" id="delete-btn" data-id="' . e($row->id) . '">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function dashboard()
    {
        // Fetch analytics data
        $totalSales = Order::where('status', 'delivered')->sum('total_price');
        $totalGallonsBorrowed = BorrowedGallon::where('status', 'borrowed')->count(); // Ensure you have the model for borrowed gallons
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Payment::sum('amount');

        $recentOrders = Order::with(['user', 'items.product', 'product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalGallonsBorrowed',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'recentOrders'
        ));
    }

    // app/Http/Controllers/AdminController.php

    public function borrowedGallons()
    {
        $borrowedGallons = BorrowedGallon::all();  // Admin can view all borrowed gallons
        return view('admin.borrowed-gallons', compact('borrowedGallons'));
    }

    public function updateBorrowedGallon(Request $request, $id)
    {
        // Fetch the borrowed gallon record
        $borrowedGallon = BorrowedGallon::findOrFail($id);

        // Update the status or any other attributes
        $borrowedGallon->status = $request->input('status');
        $borrowedGallon->save();

        // Redirect back to the list of borrowed gallons with a success message
        return redirect()->route('admin.borrowed-gallons')->with('success', 'Borrowed Gallon status updated!');
    }

    public function salesReport(Request $request)
    {
        $sales = [];
        $dateRange = $request->input('date_range', 'weekly');  // Default to weekly

        if ($dateRange == 'daily') {
            $sales = Order::whereDate('created_at', now()->toDateString())->sum('total_price');
        } elseif ($dateRange == 'weekly') {
            $sales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
        } elseif ($dateRange == 'monthly') {
            $sales = Order::whereMonth('created_at', now()->month)->sum('total_price');
        }

        return view('admin.sales-report', compact('sales', 'dateRange'));
    }

    public function gallonDashboard()
    {
        // Get total gallons and usage statistics
        $totalGallons = Gallon::count();
        $totalRefills = Gallon::where('status', 'refilled')->count();
        $gallonUsageStats = Gallon::groupBy('created_at')
            ->selectRaw('count(*) as total, created_at')
            ->get();

        return view('admin.gallon-dashboard', compact('totalGallons', 'totalRefills', 'gallonUsageStats'));
    }

    public function pendingCustomers()
    {
        $pendingUsers = User::where('role', 'customer')
            ->where('approval_status', 'pending')
            ->get();

        return view('admin.customers.pending', compact('pendingUsers'));
    }

    public function approveCustomer($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'approved';
        $user->save();

        // ✅ Send account approval email
        \Mail::to($user->email)->send(new \App\Mail\AccountApprovedMail($user));

        return redirect()->back()->with('success', 'Customer approved successfully, and notification email sent.');
    }


    public function rejectCustomer($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'rejected';
        $user->save();

        return back()->with('error', 'Customer registration rejected.');
    }


    public function manageBorrowedGallons()
    {
        $borrowedGallons = BorrowedGallon::with('user', 'approver')->get();
        return view('admin.borrowed-gallons', compact('borrowedGallons'));
    }

    public function approveBorrowedGallon(Request $request, $id)
    {
        $borrow = BorrowedGallon::findOrFail($id);
        $borrow->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'borrowed_at' => now(),
        ]);

        return back()->with('success', 'Borrow request approved successfully.');
    }

    public function personnelsIndex()
    {
        $personnels = Admin::whereIn('user_type', ['Delivery', 'staff'])->get();
        return view('admin.personnels.index', compact('personnels'));
    }

    public function personnelsCreate()
    {
        return view('admin.personnels.create');
    }

    public function personnelsStore(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|min:6',
            'user_type' => 'required|in:Delivery,staff',
        ]);

        Admin::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('admin.personnels.index')->with('success', 'Personnel added successfully!');
    }

    public function personnelsEdit($id)
    {
        $personnel = Admin::findOrFail($id);
        return view('admin.personnels.edit', compact('personnel'));
    }

    public function personnelsUpdate(Request $request, $id)
    {
        $personnel = Admin::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email,' . $id,
            'password'  => 'nullable|min:6',
            'user_type' => 'required|in:Delivery,staff',
        ]);

        $personnel->name = $request->name;
        $personnel->email = $request->email;
        $personnel->user_type = $request->user_type;

        if ($request->filled('password')) {
            $personnel->password = Hash::make($request->password);
        }

        $personnel->save();

        return redirect()->route('admin.personnels.index')->with('success', 'Personnel updated successfully!');
    }

    public function personnelsDestroy($id)
    {
        $personnel = Admin::findOrFail($id);
        $personnel->delete();

        return redirect()->route('admin.personnels.index')->with('success', 'Personnel deleted successfully!');
    }

    public function getSalesData($filter)
    {
        $labels = [];
        $values = [];

        if ($filter === 'week') {
            // 🗓 Last 7 Days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M d');
                $values[] = \App\Models\Order::where('status', 'delivered')
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
            }
        } elseif ($filter === 'month') {
            // 📅 Last 30 Days grouped by week
            for ($i = 4; $i >= 0; $i--) {
                $startOfWeek = now()->subWeeks($i)->startOfWeek();
                $endOfWeek = now()->subWeeks($i)->endOfWeek();
                $labels[] = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d');
                $values[] = \App\Models\Order::where('status', 'delivered')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->sum('total_price');
            }
        } else {
            // 📆 Yearly — group by month
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = now()->startOfYear()->addMonths($i - 1)->format('M');
                $values[] = \App\Models\Order::where('status', 'delivered')
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_price');
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }


}
