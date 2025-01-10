<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - HealthTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            transform: translateY(-2px);
        }
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e2e8f0;
            margin-top: 1rem;
            border-radius: 2px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="register-container max-w-md w-full space-y-8 p-10 rounded-2xl shadow-2xl" data-aos="zoom-in">
        <div>
            <a href="/albi" class="block">
                <h1 class="text-center text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text mb-2">
                    HealthTracker
                </h1>
            </a>
            <p class="text-center text-lg text-gray-600">
                Mulai perjalanan sehat Anda
            </p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                <p class="font-medium">Oops!</p>
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="/albi/register" class="mt-8 space-y-6" id="registerForm">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Lengkap
                    </label>
                    <input id="name" name="name" type="text" required 
                           class="form-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="form-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"
                           placeholder="contoh@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required 
                           class="form-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"
                           placeholder="Minimal 8 karakter">
                    <div class="progress-bar">
                        <div class="progress-bar-fill" id="passwordStrength" style="width: 0%"></div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500" id="passwordStrengthText">
                        Kekuatan password
                    </p>
                </div>
            </div>

            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-500">Syarat dan Ketentuan</a>
                </label>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition duration-150">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-600">
                Sudah punya akun?
                <a href="/albi/login" class="font-medium text-blue-600 hover:text-blue-500">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-out-back',
            once: true
        });

        // Password strength checker
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('passwordStrengthText');

        password.addEventListener('input', function() {
            const value = password.value;
            let strength = 0;
            let tips = [];

            if (value.length >= 8) {
                strength += 25;
                tips.push('Panjang mencukupi');
            }
            if (value.match(/[A-Z]/)) {
                strength += 25;
                tips.push('Huruf besar');
            }
            if (value.match(/[0-9]/)) {
                strength += 25;
                tips.push('Angka');
            }
            if (value.match(/[^A-Za-z0-9]/)) {
                strength += 25;
                tips.push('Karakter khusus');
            }

            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.style.background = 'linear-gradient(to right, #ef4444, #ef4444)';
                strengthText.textContent = 'Password lemah';
            } else if (strength <= 50) {
                strengthBar.style.background = 'linear-gradient(to right, #f59e0b, #f59e0b)';
                strengthText.textContent = 'Password sedang';
            } else if (strength <= 75) {
                strengthBar.style.background = 'linear-gradient(to right, #10b981, #10b981)';
                strengthText.textContent = 'Password kuat';
            } else {
                strengthBar.style.background = 'linear-gradient(to right, #3b82f6, #8b5cf6)';
                strengthText.textContent = 'Password sangat kuat';
            }

            if (tips.length > 0) {
                strengthText.textContent += ': ' + tips.join(', ');
            }
        });
    </script>
</body>
</html> 