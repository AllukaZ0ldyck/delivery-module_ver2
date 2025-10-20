<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Mail\NewUserPendingApproval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required', 'string', 'max:20', 'unique:users,contact'],
            'address' => ['required', 'string', 'max:255'],
            'gallon_type' => ['required', 'string', 'max:50'],
            'gallon_count' => ['required', 'integer', 'min:1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $nameParts = explode(' ', $data['name']);
        $confirmationCode = strtoupper(uniqid('CONF-'));

        // ✅ Create new customer account with QR token
        $user = User::create([
            'firstname' => $nameParts[0] ?? '',
            'lastname' => $nameParts[1] ?? '',
            'name' => $data['name'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'gallon_type' => $data['gallon_type'],
            'gallon_count' => $data['gallon_count'],
            'role' => 'customer',
            'password' => Hash::make($data['password']),
            'approval_status' => 'pending',
            'confirmation_code' => $confirmationCode,
            'qr_token' => Str::uuid(), // unique login token
        ]);

        /**
         * ✅ Generate a “magic login” URL embedded in the QR
         * Example: https://yourdomain.com/qr-login/{token}
         */
        $qrLoginUrl = url(env('NGROK_URL').'/qr-login/' . $user->qr_token);

        // ✅ Generate QR image with the URL
        $qrCode = new QrCode($qrLoginUrl);
        $writer = new PngWriter();
        $imageData = $writer->write($qrCode)->getString();

        // ✅ Store QR code under /storage/app/public/qrcodes/
        $path = 'qrcodes/' . $user->id . '.png';
        Storage::put( $path, $imageData);

        // ✅ Save QR code path to user record
        $user->update(['qr_code' => $path]);

        // ✅ Notify admin about pending user approval
        Mail::to(env('ADMIN_EMAIL'))
            ->send(new NewUserPendingApproval($user));

        return $user;
    }
    // Override registered() to prevent login if not approved
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        Mail::to($user->email)->send(new \App\Mail\RegistrationSuccessMail($user));


        // ✅ Do NOT log them in — requires admin approval first
        // Auth::login($user);

        return redirect('/login')->with('success', 'Registration successful! Please check your email.');

    }

}
