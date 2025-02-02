<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

<nav class="bg-white shadow-lg sticky top-0 z-50 backdrop-filter backdrop-blur-lg bg-opacity-90">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="/albi/dashboard" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text hover:scale-105 transition-transform">HealthTracker</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="/albi/food-journal" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-utensils mr-2"></i>Catatan Makanan
                </a>
                <a href="/albi/workout-schedule" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-dumbbell mr-2"></i>Jadwal Workout
                </a>
                <a href="/albi/medical-history" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-notes-medical mr-2"></i>Riwayat Medis
                </a>
                <a href="/albi/bmi" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-calculator mr-2"></i>BMI Tracker
                </a>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="relative">
                        <a href="/albi/profile" class="flex items-center space-x-3 text-gray-700 hover:text-blue-500 focus:outline-none bg-gray-50 rounded-full px-4 py-2 transition-all duration-300 hover:bg-gray-100">
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-blue-500 ring-offset-2" 
                                src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']); ?>&background=random" 
                                alt="Profile">
                            <span class="font-medium"><?php echo htmlspecialchars($_SESSION['user']); ?></span>
                        </a>
                    </div>
                    <a href="/albi/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-full hover:from-red-600 hover:to-red-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </a>
                <?php else: ?>
                    <a href="/albi/login" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-full hover:from-blue-600 hover:to-blue-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
