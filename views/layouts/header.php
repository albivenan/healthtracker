<?php
if (!isset($_SESSION['user'])) {
    header('Location: /albi/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'HealthTracker'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/albi/dashboard" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text">HealthTracker</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/albi/dashboard" class="text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="/albi/food-journal" class="text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Catatan Makanan
                    </a>
                    <a href="/albi/workout-schedule" class="text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Jadwal Workout
                    </a>
                    <a href="/albi/bmi" class="text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Body Mass Index
                    </a>
                    <a href="/albi/medical-history" class="text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Riwayat Medis
                    </a>
                    <div class="relative">
                        <a href="/albi/profile" class="flex items-center space-x-2 text-gray-700 hover:text-blue-500 focus:outline-none">
                            <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']); ?>&background=random" alt="Profile">
                            <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
                        </a>
                    </div>
                    <a href="/albi/logout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150">Keluar</a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html> 