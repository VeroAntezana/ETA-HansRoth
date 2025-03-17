<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"
/>
</head>
<body>
    <div class="container">
        <h2>ETA HANS ROT</h2>
        <form action="{{ route('login.verificar') }}" method="POST">
            @csrf
             <div class="input">
                <input type="text" name="usuario" placeholder="Usuario">
             <i class="ri-user-line"></i>
             </div>

             <div class="input">
                <input type="password" name="password" placeholder="contrasena">
                <i class="ri-lock-line"></i>
             </div>
             <div class="forget">

             </div>

             <button type="submit">Submit</button>

        </form>
    </div>
</body>
</html>
