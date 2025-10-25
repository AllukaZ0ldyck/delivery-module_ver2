<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>New Pending Account</title></head>
<body>
    <h2>New Customer Registration Pending Approval</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Contact:</strong> {{ $user->contact ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
    <p><strong>Gallon Type:</strong> {{ $user->gallon_type ?? 'N/A' }}</p>
    <p><strong>Gallon Count:</strong> {{ $user->gallon_count ?? 'N/A' }}</p>
    <p>Go to <a href="{{ url('/admin/customers/pending') }}">Pending Customers</a> to review and approve this account.</p>
</body>
</html>
