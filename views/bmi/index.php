<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Tracker - HealthTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #EDF1F7 0%, #E2E8F3 100%);
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="gradient-bg">
    <!-- Navbar -->
    <?php
$pageTitle = "Catatan Makanan";
require_once __DIR__ . '/../layouts/header.php';
?>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php 
        $currentPage = "BMI Tracker";
        include __DIR__ . '/../partials/breadcrumb.php'; 
        ?>
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">BMI Tracker</h1>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-gray-500">
                        <i class="fas fa-calculator mr-2"></i>
                        Monitor Indeks Massa Tubuh
                    </span>
                </div>
            </div>
        </div>

        <!-- Input Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="card p-6 rounded-xl shadow-lg" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Input Data BMI</h2>
                <form id="bmiForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat Badan (kg)</label>
                        <input type="number" name="weight" step="0.1" class="form-input w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tinggi Badan (cm)</label>
                        <input type="number" name="height" step="0.1" class="form-input w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Hitung BMI
                    </button>
                </form>
            </div>

            <!-- Latest BMI Result -->
            <div class="card p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Hasil BMI Terakhir</h2>
                <div id="latestResult" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">
                        Loading...
                    </div>
                </div>
            </div>
        </div>

        <!-- BMI Chart -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="card p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Grafik BMI</h2>
                    <select id="chartTimeRange" class="form-select rounded-lg border-gray-300 text-sm">
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
                <div class="w-full h-[300px]">
                    <canvas id="bmiChart"></canvas>
                </div>
            </div>

            <!-- Progress Summary -->
            <div class="card p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Rangkuman Progress</h2>
                <div id="progressSummary" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">
                        Loading...
                    </div>
                </div>
            </div>
        </div>

        <!-- BMI History Table -->
        <div class="card p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat BMI</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (kg)</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tinggi (cm)</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BMI</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Data BMI</h3>
                    <form id="editForm" class="space-y-4">
                        <input type="hidden" id="editId">
                        <div>
                            <label for="editWeight" class="block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
                            <input type="number" id="editWeight" name="editWeight" step="0.1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="editHeight" class="block text-sm font-medium text-gray-700">Tinggi Badan (cm)</label>
                            <input type="number" id="editHeight" name="editHeight" step="0.1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        AOS.init();
        let bmiChart;

        // Load latest BMI
        async function loadLatestBMI() {
            try {
                const response = await fetch('/bmi/latest');
                if (!response.ok) {
                    throw new Error('Failed to fetch latest BMI');
                }
                
                const result = await response.json();
                if (!result.success) throw new Error(result.error);
                
                const latestResult = document.getElementById('latestResult');
                const data = result.data;
                
                if (data) {
                    latestResult.innerHTML = `
                        <div class="text-center">
                            <div class="text-5xl font-bold ${getBMIColorClass(data.bmi_value)}">${data.bmi_value}</div>
                            <div class="text-xl font-semibold mt-2">${formatBMICategory(data.bmi_category)}</div>
                            <div class="text-gray-500 mt-4">
                                <div>Berat: ${data.weight} kg</div>
                                <div>Tinggi: ${data.height} cm</div>
                                <div class="text-sm mt-2">${formatDate(data.created_at)}</div>
                            </div>
                        </div>
                    `;
                } else {
                    latestResult.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            Belum ada data BMI
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('latestResult').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        Gagal memuat data: ${error.message}
                    </div>
                `;
            }
        }

        // Load BMI history
        async function loadBMIHistory() {
            try {
                const response = await fetch('/bmi/history');
                if (!response.ok) {
                    throw new Error('Failed to fetch BMI history');
                }
                
                const result = await response.json();
                if (!result.success) throw new Error(result.error);
                
                const records = result.data;
                const progress = result.progress;
                
                updateProgressSummary(progress);
                updateHistoryTable(records);
                updateBMIChart(records);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('historyTable').innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-red-500">
                            Gagal memuat data: ${error.message}
                        </td>
                    </tr>
                `;
            }
        }

        // Update progress summary
        function updateProgressSummary(progress) {
            const progressSummary = document.getElementById('progressSummary');
            
            if (!progress) {
                progressSummary.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        Belum cukup data untuk menampilkan progress
                    </div>
                `;
                return;
            }

            const weightChangeColor = progress.weight_change <= 0 ? 'text-green-500' : 'text-red-500';
            const bmiChangeColor = progress.bmi_change <= 0 ? 'text-green-500' : 'text-red-500';

            progressSummary.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Perubahan Berat</div>
                        <div class="text-2xl font-bold ${weightChangeColor}">
                            ${progress.weight_change > 0 ? '+' : ''}${progress.weight_change} kg
                        </div>
                        <div class="text-sm ${weightChangeColor}">
                            ${progress.weight_change_percentage > 0 ? '+' : ''}${progress.weight_change_percentage}%
                        </div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Perubahan BMI</div>
                        <div class="text-2xl font-bold ${bmiChangeColor}">
                            ${progress.bmi_change > 0 ? '+' : ''}${progress.bmi_change}
                        </div>
                        <div class="text-sm ${bmiChangeColor}">
                            ${progress.bmi_change_percentage > 0 ? '+' : ''}${progress.bmi_change_percentage}%
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center text-sm text-gray-500">
                    <div>Total ${progress.total_records} pengukuran dalam ${progress.days_tracked} hari</div>
                </div>
            `;
        }

        // Update history table
        function updateHistoryTable(records) {
            const historyTable = document.getElementById('historyTable');
            
            if (!records || records.length === 0) {
                historyTable.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Belum ada riwayat BMI
                        </td>
                    </tr>
                `;
                return;
            }

            historyTable.innerHTML = records.map(record => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.date_formatted}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.time_formatted}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.weight}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.height}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium ${getBMIColorClass(record.bmi_value)}">${record.bmi_value}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatBMICategory(record.bmi_category)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="openEditModal('${record.id}', ${record.weight}, ${record.height})"
                            class="text-blue-600 hover:text-blue-900">
                            Edit
                        </button>
                        <button onclick="deleteBMI('${record.id}')"
                            class="text-red-600 hover:text-red-900">
                            Hapus
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Update BMI chart
        function updateBMIChart(records) {
            const ctx = document.getElementById('bmiChart').getContext('2d');
            const timeRange = document.getElementById('chartTimeRange').value;
            
            if (bmiChart) {
                bmiChart.destroy();
            }

            // Filter records based on time range
            let filteredRecords = records;
            if (timeRange !== 'all') {
                const daysAgo = parseInt(timeRange);
                const cutoffDate = new Date();
                cutoffDate.setDate(cutoffDate.getDate() - daysAgo);
                
                filteredRecords = records.filter(record => 
                    new Date(record.created_at) >= cutoffDate
                );
            }

            const dates = filteredRecords.map(r => r.date_formatted);
            const bmiValues = filteredRecords.map(r => r.bmi_value);
            const weights = filteredRecords.map(r => r.weight);

            // Calculate moving averages
            const movingAveragePeriod = Math.min(7, Math.floor(filteredRecords.length / 2));
            const bmiMovingAvg = calculateMovingAverage(bmiValues, movingAveragePeriod);
            const weightMovingAvg = calculateMovingAverage(weights, movingAveragePeriod);

            bmiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'BMI',
                            data: bmiValues,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'BMI (Rata-rata)',
                            data: bmiMovingAvg,
                            borderColor: 'rgba(59, 130, 246, 0.5)',
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 0,
                            fill: false
                        },
                        {
                            label: 'Berat (kg)',
                            data: weights,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Berat (Rata-rata)',
                            data: weightMovingAvg,
                            borderColor: 'rgba(16, 185, 129, 0.5)',
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 0,
                            fill: false,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toFixed(1);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'BMI'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Berat (kg)'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Calculate moving average
        function calculateMovingAverage(values, period) {
            const result = [];
            for (let i = 0; i < values.length; i++) {
                if (i < period - 1) {
                    result.push(null);
                    continue;
                }
                
                let sum = 0;
                for (let j = 0; j < period; j++) {
                    sum += values[i - j];
                }
                result.push(sum / period);
            }
            return result;
        }

        // Handle form submission
        document.getElementById('bmiForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const weight = parseFloat(form.weight.value);
            const height = parseFloat(form.height.value);

            try {
                const response = await fetch('/bmi/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ weight, height })
                });

                if (!response.ok) {
                    throw new Error('Failed to save BMI data');
                }

                const result = await response.json();
                if (!result.success) throw new Error(result.error);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data BMI berhasil disimpan'
                });

                form.reset();
                loadLatestBMI();
                loadBMIHistory();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        });

        // Handle chart time range change
        document.getElementById('chartTimeRange').addEventListener('change', () => {
            loadBMIHistory();
        });

        // Edit Modal Functions
        function openEditModal(id, weight, height) {
            document.getElementById('editId').value = id;
            document.getElementById('editWeight').value = weight;
            document.getElementById('editHeight').value = height;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Handle edit form submission
        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('editId').value;
            const weight = parseFloat(document.getElementById('editWeight').value);
            const height = parseFloat(document.getElementById('editHeight').value);

            try {
                const response = await fetch('/bmi/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, weight, height })
                });

                if (!response.ok) {
                    throw new Error('Failed to update BMI data');
                }

                const result = await response.json();
                if (!result.success) throw new Error(result.error);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data BMI berhasil diperbarui'
                });

                closeEditModal();
                loadLatestBMI();
                loadBMIHistory();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        });

        // Delete BMI record
        async function deleteBMI(id) {
            try {
                const result = await Swal.fire({
                    title: 'Hapus Data BMI?',
                    text: "Data yang dihapus tidak dapat dikembalikan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    const response = await fetch('/bmi/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to delete BMI data');
                    }

                    const data = await response.json();
                    if (!data.success) throw new Error(data.error);

                    Swal.fire(
                        'Terhapus!',
                        'Data BMI berhasil dihapus',
                        'success'
                    );

                    loadLatestBMI();
                    loadBMIHistory();
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        }

        function formatDate(dateStr) {
            return new Date(dateStr).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getBMIColorClass(bmi) {
            if (bmi < 18.5) return 'text-yellow-500';
            if (bmi < 25) return 'text-green-500';
            if (bmi < 30) return 'text-orange-500';
            return 'text-red-500';
        }

        function formatBMICategory(category) {
            const categories = {
                'underweight': 'Kurus',
                'normal': 'Normal',
                'overweight': 'Gemuk',
                'obese': 'Obesitas'
            };
            return categories[category] || category;
        }

        // Load initial data
        loadLatestBMI();
        loadBMIHistory();
    </script>
</body>
</html>