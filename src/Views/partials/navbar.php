<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-indigo-600">HealthTracker</span>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="/albi/dashboard" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300">Dashboard</a>
                    <a href="/albi/food-journal" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300">Jurnal Makanan</a>
                    <a href="/albi/workout" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300">Jadwal Workout</a>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-gray-700 mr-4">Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna'); ?></span>
                <a href="/albi/logout" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Logout</a>
            </div>
        </div>
    </div>
</nav> 