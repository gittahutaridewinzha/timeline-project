<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f9f9]">

<div class="min-h-screen flex items-center justify-center">
    <div class="flex w-full max-w-screen-xl shadow-lg rounded-lg overflow-hidden">

        <!-- Kiri: Gambar -->
        <div class="w-1/2 bg-cover bg-center relative hidden md:block" style="background-image: url('assets/images/background/contact.jpg');">

        </div>

        <!-- Kanan: Form -->
        <div class="w-full md:w-1/2 bg-white px-8 py-12 flex flex-col justify-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('assets/images/konfigurasi/logo.png') }}" alt="Logo" class="h-12 w-auto">
            </div>
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Selamat datang kembali!</h2>

            <!-- Menampilkan Pesan Error -->
            @if ($errors->has('login_error'))
                <div class="mb-4 text-red-500">
                    <p>{{ $errors->first('login_error') }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <!-- Tombol Login -->
                <button type="submit" class="w-full bg-[#D3D3D3] text-black py-2 rounded">
                    Login
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
