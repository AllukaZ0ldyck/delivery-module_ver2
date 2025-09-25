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
// use App\Models\Gallon;


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

    public function index() {
        $data = $this->adminService::getData();

        if(request()->ajax()) {
            return $this->datatable($data);
        }

        // Add dashboard stats
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Payment::sum('amount');
        $recentOrders = Order::with('user', 'product')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'data', 'totalOrders', 'totalCustomers', 'totalRevenue', 'recentOrders'
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

        $recentOrders = Order::with('user', 'product')->latest()->take(5)->get();

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




}
