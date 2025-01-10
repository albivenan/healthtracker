<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthTracker - Aplikasi Pengelolaan Kesehatan Pribadi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .nav-blur {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        .cta-button {
            position: relative;
            overflow: hidden;
        }
        .cta-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: linear-gradient(115deg, transparent 0%, transparent 40%, rgba(255,255,255,0.2) 40%, rgba(255,255,255,0.2) 60%, transparent 60%, transparent 100%);
            animation: shine 3s infinite;
        }
        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .wave-bottom {
            position: relative;
            overflow: hidden;
        }
        .wave-bottom::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") repeat-x;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="nav-blur fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/albi" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">HealthTracker</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/albi/login" class="text-gray-600 hover:text-blue-500 transition duration-150">Masuk</a>
                    <a href="/albi/register" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-full hover:shadow-lg transform hover:scale-105 transition duration-150">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="pt-32 pb-16 px-4 wave-bottom">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right">
                        <h1 class="text-5xl font-bold text-gray-800 mb-6 leading-tight">
                            Kelola Kesehatan Anda dengan <span class="bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Mudah</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-8">Pantau, catat, dan analisis data kesehatan Anda dalam satu aplikasi yang intuitif dan mudah digunakan.</p>
                        <div class="space-x-4">
                            <a href="/albi/register" class="cta-button bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-full text-lg hover:shadow-lg transform hover:scale-105 transition duration-150 inline-block">Mulai Sekarang</a>
                            <a href="#fitur" class="text-gray-600 hover:text-blue-500 px-8 py-3 rounded-full text-lg inline-block">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                    <div data-aos="fade-left" class="relative">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-purple-100 rounded-full filter blur-3xl opacity-30"></div>
                        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-100 rounded-full filter blur-3xl opacity-30"></div>
                        <lottie-player
                            src="https://assets5.lottiefiles.com/packages/lf20_x1gjdldd.json"
                            background="transparent"
                            speed="1"
                            style="width: 100%; height: 400px;"
                            loop
                            autoplay>
                        </lottie-player>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan</h2>
                    <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk menjalani gaya hidup sehat</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-card p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 mx-auto mb-6">
                            <lottie-player
                                src="https://assets3.lottiefiles.com/packages/lf20_5n8yfkac.json"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%;"
                                loop
                                autoplay>
                            </lottie-player>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Pencatatan Kesehatan</h3>
                        <p class="text-gray-600 text-center">Catat dan pantau data kesehatan harian Anda seperti berat badan, tekanan darah, dan kadar gula darah dengan mudah.</p>
                    </div>

                    <div class="feature-card p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 mx-auto mb-6">
                            <lottie-player
                                src="https://assets3.lottiefiles.com/packages/lf20_jcikwtux.json"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%;"
                                loop
                                autoplay>
                            </lottie-player>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Rekomendasi Pola Makan</h3>
                        <p class="text-gray-600 text-center">Dapatkan rekomendasi menu makanan sehat yang disesuaikan dengan kondisi dan tujuan kesehatan Anda.</p>
                    </div>

                    <div class="feature-card p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-16 h-16 mx-auto mb-6">
                            <lottie-player
                                src="https://assets3.lottiefiles.com/packages/lf20_oz8zxcib.json"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%;"
                                loop
                                autoplay>
                            </lottie-player>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Pelacakan Aktivitas</h3>
                        <p class="text-gray-600 text-center">Monitor aktivitas fisik dan kalori yang terbakar untuk mendukung gaya hidup sehat Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="text-4xl font-bold text-blue-600 mb-2">10K+</div>
                        <div class="text-gray-600">Pengguna Aktif</div>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="text-4xl font-bold text-purple-600 mb-2">50K+</div>
                        <div class="text-gray-600">Data Kesehatan</div>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="text-4xl font-bold text-green-600 mb-2">95%</div>
                        <div class="text-gray-600">Pengguna Puas</div>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="text-4xl font-bold text-yellow-600 mb-2">24/7</div>
                        <div class="text-gray-600">Dukungan</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 gradient-bg">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div data-aos="zoom-in">
                    <div class="w-24 h-24 mx-auto mb-8">
                        <lottie-player
                            src="https://assets3.lottiefiles.com/packages/lf20_uwR49r.json"
                            background="transparent"
                            speed="1"
                            style="width: 100%; height: 100%;"
                            loop
                            autoplay>
                        </lottie-player>
                    </div>
                    <h2 class="text-4xl font-bold text-white mb-8">Mulai Perjalanan Sehat Anda Hari Ini</h2>
                    <p class="text-xl text-white opacity-90 mb-8">Bergabunglah dengan ribuan pengguna yang telah merasakan manfaat HealthTracker</p>
                    <a href="/albi/register" class="bg-white text-blue-600 px-8 py-3 rounded-full text-lg hover:shadow-lg transform hover:scale-105 transition duration-150 inline-block">Daftar Gratis</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">HealthTracker</h3>
                    <p class="text-gray-400">Solusi terbaik untuk mengelola kesehatan Anda</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Pencatatan Kesehatan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Rekomendasi Menu</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Pelacakan Aktivitas</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Kontak</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Karir</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2023 HealthTracker. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
</body>
</html> 