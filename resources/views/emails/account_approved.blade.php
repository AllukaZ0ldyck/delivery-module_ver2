@component('mail::message')
# 🎉 Account Approved — Welcome to AquaTek Water Station!

Hi {{ $user->firstname ?? $user->name }},

Good news! Your AquaTek Water Station account has been **approved**.  
You can now log in, place orders, and track your deliveries.

---

### 📱 Your Personal QR Code
This QR code is linked to your account.  
You can scan it anytime for quick login and instant order access.

<p style="text-align: center; margin: 20px 0;">
    <img src="{{ $qrPath }}" alt="QR Code" width="200" height="200" style="border: 1px solid #ccc; border-radius: 8px;">
</p>

---

@component('mail::button', ['url' => url(env('NGROK_URL').'/login')])
Go to AquaTek Water Station
@endcomponent

Your account details:
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Gallon Type:** {{ $user->gallon_type }}
- **Gallon Count:** {{ $user->gallon_count }}

---

💧 Thank you for joining AquaTek Water Station!  
We’re excited to serve your water delivery needs.

Warm regards,  
**The AquaTek Water Station Team**
@endcomponent
