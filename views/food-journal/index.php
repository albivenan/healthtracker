<?php
$pageTitle = "Catatan Makanan";
require_once __DIR__ . '/../layouts/header.php';

// Hitung total kalori hari ini
$totalCalories = 0;
foreach ($dailyJournal as $journal) {
    $totalCalories += $journal['calories'];
}

// Kategori waktu makan untuk filter
$mealTimes = ['Sarapan', 'Makan Siang', 'Makan Malam', 'Camilan'];
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header Section dengan Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
        <a href="/albi/dashboard" class="inline-flex items-center text-gray-700 hover:text-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2 font-medium">Catatan Makanan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Catatan Makanan Harian</h1>
            <p class="text-gray-600">Pantau asupan nutrisi Anda setiap hari</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="/albi/food-journal/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
                Tambah Catatan
            </a>
        </div>
    </div>

    <!-- Ringkasan Nutrisi -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300" data-nutrition="calories">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Kalori</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo number_format($totalCalories); ?> kkal</p>
                </div>
            </div>
            <div class="relative pt-2">
                <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-100">
                    <div style="width: <?php echo min(($totalCalories / 2500) * 100, 100); ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 to-purple-600"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Target: 2,500 kkal</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300" data-nutrition="protein">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Protein</p>
                    <p class="text-2xl font-bold text-gray-800">65g</p>
                </div>
            </div>
            <div class="relative pt-2">
                <div class="overflow-hidden h-2 text-xs flex rounded bg-green-100">
                    <div style="width: 75%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-green-500 to-green-600"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Target: 80g</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300" data-nutrition="carbs">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Karbohidrat</p>
                    <p class="text-2xl font-bold text-gray-800">280g</p>
                </div>
            </div>
            <div class="relative pt-2">
                <div class="overflow-hidden h-2 text-xs flex rounded bg-yellow-100">
                    <div style="width: 85%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-yellow-500 to-yellow-600"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Target: 325g</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300" data-nutrition="fat">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Lemak</p>
                    <p class="text-2xl font-bold text-gray-800">45g</p>
                </div>
            </div>
            <div class="relative pt-2">
                <div class="overflow-hidden h-2 text-xs flex rounded bg-red-100">
                    <div style="width: 65%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-red-500 to-red-600"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Target: 70g</p>
            </div>
        </div>
    </div>

    <!-- Filter Waktu Makan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-wrap gap-4">
            <button data-filter="all" class="filter-btn px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 active">
                Semua
            </button>
            <?php foreach ($mealTimes as $mealTime): ?>
                <button data-filter="<?php echo strtolower(str_replace(' ', '-', $mealTime)); ?>" 
                        class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                    <?php echo $mealTime; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Grafik Mingguan dengan Card Design -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Ringkasan Mingguan</h2>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                    Minggu Ini
                </button>
                <button class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                    Bulan Ini
                </button>
            </div>
        </div>
        <canvas id="weeklyChart" class="w-full" height="200"></canvas>
    </div>

    <!-- Daftar Catatan dengan Card Design -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Catatan Hari Ini</h2>
                <div class="relative">
                    <input type="text" placeholder="Cari makanan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <?php if (empty($dailyJournal)): ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada catatan makanan</h3>
                    <p class="text-gray-500 mb-6">Mulai catat makanan Anda hari ini</p>
                    <a href="/albi/food-journal/create" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Catatan Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Makanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Porsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kalori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($dailyJournal as $journal): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                        <?php 
                                        $timestamp = $journal['created_at']->toDateTime()->getTimestamp();
                                        echo date('H:i', $timestamp);
                                        ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?php echo $journal['meal_time']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($journal['food_name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo $journal['portion']; ?> porsi</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo number_format($journal['calories']); ?> kkal</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($journal['notes'] ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="/albi/food-journal/edit/<?php echo $journal['_id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <button onclick="deleteJournal('<?php echo $journal['_id']; ?>')" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function loadWeeklyChart() {
    try {
        const response = await fetch('/albi/food-journal/weekly');
        const data = await response.json();
        
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Total Kalori',
                        data: data.calories,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Makanan',
                        data: data.meals,
                        backgroundColor: 'rgba(147, 51, 234, 0.5)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1F2937',
                        bodyColor: '#4B5563',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kalori (kkal)',
                            color: '#4B5563',
                            font: {
                                size: 12,
                                weight: 'medium'
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah Makanan',
                            color: '#4B5563',
                            font: {
                                size: 12,
                                weight: 'medium'
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        grid: {
                            color: '#F3F4F6'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading weekly chart:', error);
    }
}

async function deleteJournal(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
        return;
    }

    try {
        const response = await fetch(`/albi/food-journal/delete/${id}`, {
            method: 'POST'
        });
        
        const result = await response.json();
        if (response.ok) {
            alert(result.message);
            window.location.reload();
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Error deleting journal:', error);
        alert('Terjadi kesalahan saat menghapus catatan');
    }
}

// Load chart when page loads
document.addEventListener('DOMContentLoaded', loadWeeklyChart);

// Implementasi filter waktu makan yang diperbaiki
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('tbody tr');
    let activeFilter = 'all';

    function updateActiveButton() {
        filterButtons.forEach(btn => {
            if (btn.dataset.filter === activeFilter) {
                btn.classList.add('bg-blue-500', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-gray-700');
            } else {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            }
        });
    }

    function filterTable(filterValue) {
        tableRows.forEach(row => {
            const mealTimeCell = row.querySelector('td:first-child .text-gray-500');
            if (!mealTimeCell) return;

            const mealTime = mealTimeCell.textContent.trim().toLowerCase();
            const normalizedFilter = filterValue.toLowerCase();

            if (filterValue === 'all' || mealTime === normalizedFilter.replace('-', ' ')) {
                row.classList.remove('hidden');
                row.classList.add('animate-fade-in');
            } else {
                row.classList.add('hidden');
                row.classList.remove('animate-fade-in');
            }
        });

        // Update ringkasan nutrisi berdasarkan makanan yang terlihat
        updateNutritionSummary();
    }

    function updateNutritionSummary() {
        let totalCalories = 0;
        let totalProtein = 0;
        let totalCarbs = 0;
        let totalFat = 0;

        tableRows.forEach(row => {
            if (!row.classList.contains('hidden')) {
                // Ambil nilai kalori dari kolom kalori
                const caloriesText = row.querySelector('td:nth-child(4)').textContent;
                const calories = parseInt(caloriesText.replace(/[^0-9]/g, ''));
                totalCalories += isNaN(calories) ? 0 : calories;

                // Hitung perkiraan makronutrien (contoh sederhana)
                totalProtein += calories * 0.2 / 4; // 20% protein
                totalCarbs += calories * 0.5 / 4;   // 50% karbo
                totalFat += calories * 0.3 / 9;     // 30% lemak
            }
        });

        // Update tampilan nutrisi
        document.querySelector('[data-nutrition="calories"] .text-2xl').textContent = 
            `${totalCalories.toFixed(0)} kkal`;
        document.querySelector('[data-nutrition="protein"] .text-2xl').textContent = 
            `${totalProtein.toFixed(0)}g`;
        document.querySelector('[data-nutrition="carbs"] .text-2xl').textContent = 
            `${totalCarbs.toFixed(0)}g`;
        document.querySelector('[data-nutrition="fat"] .text-2xl').textContent = 
            `${totalFat.toFixed(0)}g`;

        // Update progress bars
        updateProgressBar('calories', totalCalories, 2500);
        updateProgressBar('protein', totalProtein, 80);
        updateProgressBar('carbs', totalCarbs, 325);
        updateProgressBar('fat', totalFat, 70);
    }

    function updateProgressBar(nutrient, value, target) {
        const percentage = Math.min((value / target) * 100, 100);
        const progressBar = document.querySelector(`[data-nutrition="${nutrient}"] .overflow-hidden div`);
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
    }

    // Event listener untuk tombol filter
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            activeFilter = filterValue;
            filterTable(filterValue);
            updateActiveButton();
        });
    });

    // Implementasi pencarian yang diperbaiki
    const searchInput = document.querySelector('input[type="text"]');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            const foodName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const mealTime = row.querySelector('td:first-child .text-gray-500').textContent.toLowerCase();
            
            // Jika ada filter aktif selain 'all', pertahankan filter tersebut
            const matchesFilter = activeFilter === 'all' || 
                                mealTime === activeFilter.replace('-', ' ');
            
            // Tampilkan baris jika cocok dengan pencarian dan filter
            if ((foodName.includes(searchTerm) || mealTime.includes(searchTerm)) && matchesFilter) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });

        // Update ringkasan nutrisi setelah pencarian
        updateNutritionSummary();
    });

    // Tambahkan kelas untuk animasi
    const style = document.createElement('style');
    style.textContent = `
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);

    // Inisialisasi tampilan awal
    updateNutritionSummary();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 