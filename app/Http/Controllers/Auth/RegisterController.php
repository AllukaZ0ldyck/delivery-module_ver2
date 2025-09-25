<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import the QrCode facade
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {

        $name = explode(' ', $data['name']);

        // Create the user and generate the QR code
        $user = User::create([
            'firstname' => $name[0] ?? '',
            'lastname' => $name[1] ?? '',
            'name' => $data['name'],
            'email' => $data['email'],
            'user_type' => 'client',  // Set user type to client for customers
            'password' => Hash::make($data['password']),
        ]);

    // Generate the QR code (with the user's ID or any other unique value)
    $qrCode = new QrCode($user->id); // The content of the QR code will be the user ID (or you can use something else)
    $writer = new PngWriter();

    // Step 1: Generate QR code and get the image data as a string
    $imageData = $writer->write($qrCode)->getString();

    // Step 2: Set the path to save the QR code image
    $path = 'qrcodes/' . $user->id . '.png';  // This will store the QR code in the qrcodes folder inside the storage/app/public directory

    // Step 3: Save the QR code image to storage
    Storage::put('public/' . $path, $imageData);

    // Step 4: Save the QR code path in the user record
    $user->qr_code = $path;
    $user->save();

    return $user;  // Return the created user
    }
}
