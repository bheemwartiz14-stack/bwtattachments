<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
</head>
<body>

<h2>Welcome {{ $user->name }}</h2>

<p>Your account has been created successfully.</p>

<p>
    <strong>Name:</strong> {{ $user->name }}
</p>

<p>
    <strong>Email:</strong> {{ $user->email }}
</p>

<p>
    <strong>Phone:</strong> {{ $user->phone }}
</p>

<p>
    <strong>Password:</strong> {{ $password }}
</p>

</body>
</html>