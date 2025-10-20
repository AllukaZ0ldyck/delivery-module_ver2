@component('mail::message')
# Welcome to AquaTek Water Station 💧

Hi {{ $user->firstname ?? $user->name }},

Thank you for registering with **AquaTek Water Station**!  
Your account has been successfully created and is currently **pending admin approval**.

Once approved, you’ll receive another email letting you know your account is active —  
and you can then log in to start placing orders and managing your water deliveries.

---

**Account Summary:**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Gallon Type:** {{ $user->gallon_type }}
- **Gallon Count:** {{ $user->gallon_count }}

---

@component('mail::button', ['url' => url(env('NGROK_URL'). '/login')])
Go to Login Page
@endcomponent

Thank you for choosing AquaTek Water Station!  
If you have any questions, feel free to reply to this email.

Warm regards,  
**The AquaTek Water Station Team**
@endcomponent
