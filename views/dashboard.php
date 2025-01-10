<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HealthTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #EDF1F7 0%, #E2E8F3 100%);
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 30px -12px rgba(0, 0, 0, 0.15);
        }
        .form-input {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }
        .form-input:focus {
            transform: translateY(-2px);
            border-color: #3B82F6;
            box-shadow: 0 8px 16px -8px rgba(59, 130, 246, 0.3);
        }
        .stat-card {
            border-radius: 1.2rem;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }
        .health-tip {
            background: linear-gradient(135deg, #60A5FA 0%, #7C3AED 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            margin-top: 1rem;
        }
        .nutrition-card {
            background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
            border-radius: 1rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #E5E7EB;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #60A5FA 0%, #7C3AED 100%);
            transition: width 0.5s ease;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.05), rgba(147, 51, 234, 0.05));
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .stat-card:hover::before {
            opacity: 1;
        }
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #3B82F6, #9333EA);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .menu-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.95) 100%);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #3B82F6 0%, #9333EA 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.4);
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 1.5rem;
        }
        .menu-time-badge {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            color: #4B5563;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
<?php
$pageTitle = "Catatan Makanan";
require_once __DIR__ . '/layouts/header.php';
?>


    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 text-center" data-aos="fade-down">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text mb-2">
                Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!
            </h1>
            <p class="text-gray-600">Mari pantau kesehatan Anda hari ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card p-6" data-aos="fade-up">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Target Kalori</h3>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring" width="64" height="64">
                            <circle class="progress-ring-circle" stroke="#E5E7EB" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"/>
                            <circle class="progress-ring-circle" stroke="#3B82F6" stroke-width="4" fill="transparent" r="28" cx="32" cy="32" 
                                stroke-dasharray="175.93" stroke-dashoffset="88"/>
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-bold">50%</span>
                    </div>
                </div>
                <p class="text-gray-600">1200 / 2400 kkal</p>
            </div>

            <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Target Langkah</h3>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring" width="64" height="64">
                            <circle class="progress-ring-circle" stroke="#E5E7EB" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"/>
                            <circle class="progress-ring-circle" stroke="#10B981" stroke-width="4" fill="transparent" r="28" cx="32" cy="32" 
                                stroke-dasharray="175.93" stroke-dashoffset="132"/>
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-bold">25%</span>
                    </div>
                </div>
                <p class="text-gray-600">2,500 / 10,000 langkah</p>
            </div>

            <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Target Air</h3>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring" width="64" height="64">
                            <circle class="progress-ring-circle" stroke="#E5E7EB" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"/>
                            <circle class="progress-ring-circle" stroke="#60A5FA" stroke-width="4" fill="transparent" r="28" cx="32" cy="32" 
                                stroke-dasharray="175.93" stroke-dashoffset="44"/>
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-bold">75%</span>
                    </div>
                </div>
                <p class="text-gray-600">1.5 / 2.0 L</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white p-6 rounded-xl shadow-lg transform transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4 transform transition-transform duration-300 hover:scale-110">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Berat Badan</p>
                        <p class="text-2xl font-bold text-gray-800 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text" data-stat="weight">-- kg</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tekanan Darah</p>
                        <p class="text-2xl font-bold text-gray-800" data-stat="blood-pressure">--/--</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Kalori Terbakar</p>
                        <p class="text-2xl font-bold text-gray-800" data-stat="calories">-- kcal</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Langkah Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800" data-stat="steps">--</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-pink-100 mr-4">
                        <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Asupan Kalori</p>
                        <p class="text-2xl font-bold text-gray-800" data-stat="food-calories">-- kkal</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="card p-6" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Nutrisi Hari Ini</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Protein</span>
                            <span class="text-gray-800 font-medium">45g / 60g</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Karbohidrat</span>
                            <span class="text-gray-800 font-medium">180g / 300g</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 60%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Lemak</span>
                            <span class="text-gray-800 font-medium">35g / 50g</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-6" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tips Kesehatan Hari Ini</h2>
                <div class="health-tip">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold mb-2">Jaga Hidrasi</h3>
                            <p class="text-sm">Minum air putih secara teratur sepanjang hari dapat membantu metabolisme dan menjaga kesehatan tubuh Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="health-tip mt-4">
                    <div class="flex items-start">
                        <i class="fas fa-heart text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold mb-2">Aktivitas Fisik</h3>
                            <p class="text-sm">Luangkan waktu 30 menit untuk berjalan kaki atau bersepeda dapat meningkatkan kesehatan jantung.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="chart-container" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Berat Badan</h2>
                <canvas id="weightChart" class="w-full"></canvas>
            </div>

            <div class="chart-container" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Tekanan Darah</h2>
                <canvas id="bloodPressureChart" class="w-full"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-6 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Input Data Kesehatan</h2>
                <form action="/albi/health-data" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="weight">
                            Berat Badan (kg)
                        </label>
                        <input class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               type="number" step="0.1" name="weight" id="weight" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="systolic">
                            Tekanan Darah Sistolik
                        </label>
                        <input class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               type="number" name="systolic" id="systolic" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="diastolic">
                            Tekanan Darah Diastolik
                        </label>
                        <input class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               type="number" name="diastolic" id="diastolic" required>
                    </div>

                    <button type="submit" class="btn-gradient w-full text-white py-3 px-4 rounded-lg transform hover:scale-105 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Data
                    </button>
                </form>
            </div>

            <div class="card bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-6 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Rekomendasi Menu Hari Ini</h2>
                <div class="space-y-6">
                    <div class="menu-card p-4 rounded-lg transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-blue-800">Sarapan</h3>
                            <span class="menu-time-badge">06:00 - 09:00</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2 transform transition-transform duration-300 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-blue-600">Oatmeal dengan pisang dan madu</p>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2 transform transition-transform duration-300 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-blue-600">Yogurt rendah lemak</p>
                            </div>
                            <p class="text-sm text-blue-500 mt-2 font-medium">350 kalori</p>
                </div>
            </div>

                    <div class="card bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">Aktivitas Fisik</h2>
                
                        <div id="activityAlert" class="hidden mb-6">
                    <div class="p-4 rounded-lg"></div>
                </div>

                        <form id="activityForm" action="/albi/activity" method="POST" class="space-y-6" onsubmit="submitActivityForm(event)">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="steps">
                            Jumlah Langkah
                        </label>
                                <input class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               type="number" name="steps" id="steps" min="0" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="exercise_duration">
                            Durasi Olahraga (menit)
                        </label>
                                <input class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               type="number" name="exercise_duration" id="exercise_duration" min="0" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="exercise_type">
                            Jenis Olahraga
                        </label>
                                <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                name="exercise_type" id="exercise_type" required>
                            <option value="">Pilih jenis olahraga</option>
                            <option value="walking">Jalan</option>
                            <option value="running">Lari</option>
                            <option value="cycling">Bersepeda</option>
                            <option value="swimming">Berenang</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>

                            <button type="submit" class="btn-gradient w-full text-white py-3 px-4 rounded-lg transform hover:scale-105 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Catat Aktivitas
                    </button>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        AOS.init();

        async function loadStats() {
            try {
                const healthResponse = await fetch('/albi/health-data/weekly');
                const healthData = await healthResponse.json();
                
                if (healthData.weight && healthData.weight.length > 0) {
                    document.querySelector('[data-stat="weight"]').textContent = 
                        healthData.weight[healthData.weight.length - 1] + ' kg';
                }
                
                if (healthData.systolic && healthData.systolic.length > 0 && 
                    healthData.diastolic && healthData.diastolic.length > 0) {
                    document.querySelector('[data-stat="blood-pressure"]').textContent = 
                        healthData.systolic[healthData.systolic.length - 1] + '/' + 
                        healthData.diastolic[healthData.diastolic.length - 1];
                }

                const activityResponse = await fetch('/albi/activity/daily');
                const activityData = await activityResponse.json();
                
                document.querySelector('[data-stat="calories"]').textContent = 
                    (activityData.total_calories || 0) + ' kkal';
                document.querySelector('[data-stat="steps"]').textContent = 
                    activityData.total_steps || 0;

                const foodResponse = await fetch('/albi/food-journal/weekly');
                const foodData = await foodResponse.json();
                
                if (foodData.calories && foodData.calories.length > 0) {
                    const todayCalories = foodData.calories[foodData.calories.length - 1] || 0;
                    document.querySelector('[data-stat="food-calories"]').textContent = 
                        todayCalories + ' kkal';
                }
                
                updateWeightChart(healthData);
                updateBloodPressureChart(healthData);
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        loadStats();

        function updateWeightChart(data) {
            const ctx = document.getElementById('weightChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Berat Badan (kg)',
                        data: data.weight || [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }

        function updateBloodPressureChart(data) {
            const ctx = document.getElementById('bloodPressureChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [
                        {
                        label: 'Sistolik',
                            data: data.systolic || [],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                    },
                    {
                        label: 'Diastolik',
                            data: data.diastolic || [],
                            borderColor: 'rgb(147, 51, 234)',
                            backgroundColor: 'rgba(147, 51, 234, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }

        async function submitActivityForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                const alert = document.getElementById('activityAlert');
                alert.classList.remove('hidden');

                if (response.ok) {
                    alert.querySelector('div').className = 'p-4 rounded-lg bg-green-100 text-green-700';
                    alert.querySelector('div').textContent = result.message;
                    form.reset();
                    loadStats();
                } else {
                    alert.querySelector('div').className = 'p-4 rounded-lg bg-red-100 text-red-700';
                    alert.querySelector('div').textContent = result.error;
                }

                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 3000);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data aktivitas');
            }
        }

        function setProgress(percent, circle) {
            const radius = circle.r.baseVal.value;
            const circumference = radius * 2 * Math.PI;
            const offset = circumference - (percent / 100 * circumference);
            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = offset;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const circles = document.querySelectorAll('.progress-ring-circle');
            circles.forEach(circle => {
                const percent = parseInt(circle.nextElementSibling.textContent);
                setProgress(percent, circle);
            });
        });
    </script>
</body>
</html> 