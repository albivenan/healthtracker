<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kesehatan - HealthTracker</title>
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
        .severity-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .severity-low {
            background-color: #DEF7EC;
            color: #03543F;
        }
        .severity-medium {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .severity-high {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            background-color: #e9e9e9 !important;
        }

        /* Style untuk select multiple */
        .form-multiselect {
            min-height: 150px;
            padding: 0.5rem;
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid #D1D5DB;
        }

        .form-multiselect option {
            padding: 0.5rem;
            margin: 0.25rem 0;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-multiselect option:checked {
            background: linear-gradient(0deg, #60A5FA 0%, #3B82F6 100%);
            color: white;
        }

        .form-multiselect option:hover:not(:checked) {
            background-color: #F3F4F6;
        }

        .form-multiselect option:disabled {
            color: #9CA3AF;
            font-style: italic;
            background-color: #F3F4F6;
        }

        /* Style untuk group gejala */
        .symptoms-group {
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .symptoms-group-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Severity styles */
        .severity-option input:checked + div {
            border-color: currentColor;
        }
        .severity-1 { color: #059669; }
        .severity-2 { color: #D97706; }
        .severity-3 { color: #DC2626; }
        .severity-4 { color: #7C3AED; }
        .severity-5 { color: #1E3A8A; }

        /* Status styles */
        .status-option input:checked + div {
            border-color: currentColor;
        }
        .status-ongoing { color: #D97706; }
        .status-recovered { color: #059669; }
        .status-chronic { color: #DC2626; }

        /* Progress bar animation */
        .progress-bar {
            transition: width 0.3s ease-in-out;
        }

        /* Step transition */
        .form-step {
            transition: all 0.3s ease-in-out;
        }

        .step-enter {
            opacity: 0;
            transform: translateX(20px);
        }

        .step-enter-active {
            opacity: 1;
            transform: translateX(0);
        }

        .step-exit {
            opacity: 1;
            transform: translateX(0);
        }

        .step-exit-active {
            opacity: 0;
            transform: translateX(-20px);
        }
    </style>
</head>
<body class="bg-gray-50">
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-teal-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Kesehatan</h1>
            <p class="text-gray-600">Pantau dan kelola riwayat kesehatan Anda dengan mudah</p>
        </div>

        <!-- Health Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Riwayat</h3>
                <p class="text-2xl font-bold text-gray-800" id="totalRecords">0</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Sudah Sembuh</h3>
                <p class="text-2xl font-bold text-gray-800" id="recoveredCount">0</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Sedang Dirawat</h3>
                <p class="text-2xl font-bold text-gray-800" id="ongoingCount">0</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Kondisi Kronis</h3>
                <p class="text-2xl font-bold text-gray-800" id="chronicCount">0</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Kesehatan</h2>
                <canvas id="healthHistoryChart"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi Keparahan</h2>
                <canvas id="severityChart"></canvas>
            </div>
        </div>

        <!-- Form Tambah Riwayat -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Tambah Riwayat Kesehatan</h2>
                <button type="button" onclick="toggleForm()" class="text-blue-600 hover:text-blue-800 font-medium">
                    <span id="formToggleText">Tampilkan Form</span>
                </button>
            </div>

            <form id="medicalHistoryForm" class="hidden space-y-6">
                <!-- Informasi Dasar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Penyakit/Kondisi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="condition" required 
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Contoh: Demam, Flu, Diabetes">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu Diagnosis <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="diagnosis_date" required 
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Gejala -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gejala yang Dialami <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Demam" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Demam</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Batuk" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Batuk</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Pilek" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Pilek</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Sakit Kepala" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Sakit Kepala</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Nyeri Otot" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Nyeri Otot</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Mual" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Mual</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Muntah" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Muntah</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="symptoms[]" value="Diare" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Diare</label>
                        </div>
                    </div>
                    <input type="text" name="other_symptoms" 
                           class="mt-3 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Gejala lain (pisahkan dengan koma)">
                </div>

                <!-- Tingkat Keparahan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tingkat Keparahan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-4">
                        <label class="severity-option cursor-pointer">
                            <input type="radio" name="severity" value="1" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-green-600 font-medium">Ringan</div>
                                <div class="text-xs text-gray-500">Level 1</div>
                            </div>
                        </label>
                        <label class="severity-option cursor-pointer">
                            <input type="radio" name="severity" value="2" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-yellow-600 font-medium">Sedang</div>
                                <div class="text-xs text-gray-500">Level 2</div>
                            </div>
                        </label>
                        <label class="severity-option cursor-pointer">
                            <input type="radio" name="severity" value="3" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-orange-600 font-medium">Berat</div>
                                <div class="text-xs text-gray-500">Level 3</div>
                            </div>
                        </label>
                        <label class="severity-option cursor-pointer">
                            <input type="radio" name="severity" value="4" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-red-600 font-medium">Sangat Berat</div>
                                <div class="text-xs text-gray-500">Level 4</div>
                            </div>
                        </label>
                        <label class="severity-option cursor-pointer">
                            <input type="radio" name="severity" value="5" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-purple-600 font-medium">Kritis</div>
                                <div class="text-xs text-gray-500">Level 5</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tindakan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tindakan yang Diambil <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="action-option cursor-pointer">
                            <input type="radio" name="action_type" value="self" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-blue-600 font-medium">Penanganan Mandiri</div>
                                <div class="text-xs text-gray-500">Mengobati sendiri/dibiarkan</div>
                            </div>
                        </label>
                        <label class="action-option cursor-pointer">
                            <input type="radio" name="action_type" value="doctor" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-blue-600 font-medium">Konsultasi Dokter</div>
                                <div class="text-xs text-gray-500">Berobat ke dokter/rumah sakit</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Pengobatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Konsumsi Obat
                        </label>
                        <textarea name="medication" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Daftar obat yang dikonsumsi"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tindakan/Pengobatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="treatment" required rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Jelaskan tindakan atau pengobatan yang dilakukan"></textarea>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Kondisi <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="status-option cursor-pointer">
                            <input type="radio" name="status" value="Sedang Berlangsung" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-yellow-600 font-medium">Sedang Berlangsung</div>
                                <div class="text-xs text-gray-500">Masih dalam perawatan</div>
                            </div>
                        </label>
                        <label class="status-option cursor-pointer">
                            <input type="radio" name="status" value="Sembuh" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-green-600 font-medium">Sembuh</div>
                                <div class="text-xs text-gray-500">Sudah pulih</div>
                            </div>
                        </label>
                        <label class="status-option cursor-pointer">
                            <input type="radio" name="status" value="Kronis" required class="hidden">
                            <div class="p-4 rounded-lg border-2 text-center hover:shadow-md transition-all duration-200">
                                <div class="text-red-600 font-medium">Kronis</div>
                                <div class="text-xs text-gray-500">Kondisi jangka panjang</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="resetForm()" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan Riwayat
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Riwayat Kesehatan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gejala</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keparahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="medicalHistoryTable"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit Riwayat Kesehatan</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm" class="space-y-6">
            <!-- Edit form fields will be similar to add form -->
        </form>
    </div>
</div>

<style>
/* Elegant health-focused styles */
.severity-option input:checked + div,
.status-option input:checked + div,
.action-option input:checked + div {
    border-color: currentColor;
    transform: scale(1.02);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.severity-option:hover div,
.status-option:hover div,
.action-option:hover div {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Smooth transitions */
.severity-option div,
.status-option div,
.action-option div {
    transition: all 0.2s ease-in-out;
}

/* Form animations */
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-show {
    animation: slideDown 0.3s ease-out forwards;
}
</style>

<script>
// ... Previous JavaScript code ...

let healthHistoryChart;
let severityChart;

// Load and initialize everything
document.addEventListener('DOMContentLoaded', function() {
    loadMedicalHistory();
    initializeFormHandlers();
});

function initializeFormHandlers() {
    // Form submission handler
    document.getElementById('medicalHistoryForm').addEventListener('submit', submitMedicalHistory);

    // Fetch medical history on page load
    document.addEventListener('DOMContentLoaded', fetchMedicalHistory);
}

function submitMedicalHistory(event) {
    event.preventDefault();
            
    // Collect form data
    const form = document.getElementById('medicalHistoryForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Convert symptoms to array
    data.symptoms = formData.getAll('symptoms[]');

    // Send data to server
    fetch('/medical-history/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        // Check if response is ok (status in 200-299 range)
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showNotification('Riwayat kesehatan berhasil ditambahkan');
            form.reset(); // Clear form
            toggleForm(); // Hide form
            fetchMedicalHistory(); // Refresh table
        } else {
            showNotification(result.message || 'Gagal menambahkan riwayat kesehatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Terjadi kesalahan saat menambahkan riwayat kesehatan', 'error');
    });
}

function fetchMedicalHistory() {
    fetch('/medical-history/history', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include' // Important for sending cookies
    })
    .then(response => {
        // Log response details for debugging
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Check if response is ok
        if (response.status === 401) {
            // Unauthorized, redirect to login
            console.warn('Unauthorized access. Redirecting to login.');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                console.error('Server error:', errorData);
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateTable(data.data);
            updateStatistics(data.data);
        } else {
            console.error('Failed to fetch medical history');
            showNotification(data.message || 'Gagal mengambil riwayat medis', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching medical history:', error);
        if (error.message !== 'Unauthorized') {
            showNotification(error.message || 'Terjadi kesalahan saat mengambil riwayat medis', 'error');
        }
    });
}

function updateStatistics(data) {
    const totalRecords = data.length;
    const recoveredCount = data.filter(item => item.status === 'Sembuh').length;
    const ongoingCount = data.filter(item => item.status === 'Sedang Berlangsung').length;
    const chronicCount = data.filter(item => item.status === 'Kronis').length;

    document.getElementById('totalRecords').textContent = totalRecords;
    document.getElementById('recoveredCount').textContent = recoveredCount;
    document.getElementById('ongoingCount').textContent = ongoingCount;
    document.getElementById('chronicCount').textContent = chronicCount;
}

function showNotification(message, type = 'success') {
    Swal.fire({
        icon: type,
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

function toggleForm() {
    const form = document.getElementById('medicalHistoryForm');
    const toggleText = document.getElementById('formToggleText');
    
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        toggleText.textContent = 'Sembunyikan Form';
    } else {
        form.classList.add('hidden');
        toggleText.textContent = 'Tampilkan Form';
    }
}

function loadMedicalHistory() {
    fetch('/medical-history/history', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include' // Important for sending cookies
    })
    .then(response => {
        // Log response details for debugging
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Check if response is ok
        if (response.status === 401) {
            // Unauthorized, redirect to login
            console.warn('Unauthorized access. Redirecting to login.');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                console.error('Server error:', errorData);
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            updateTable(result.data);
            updateCharts(result.data);
            updateStatistics(result.data);
        } else {
            showNotification(result.message || 'Gagal memuat data riwayat kesehatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memuat data riwayat kesehatan', 'error');
    });
}

function updateTable(data) {
    const tableBody = document.getElementById('medicalHistoryTable');
    tableBody.innerHTML = '';
    
    if (!Array.isArray(data)) {
        console.error('Data is not an array:', data);
        return;
    }
    
    data.forEach(item => {
        if (!item) return; // Skip if item is undefined
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                ${new Date(item.diagnosis_date).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${item.condition || '-'}</td>
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-1">
                    ${Array.isArray(item.symptoms) ? item.symptoms.map(symptom => 
                        `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">${symptom}</span>`
                    ).join('') : '-'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getSeverityBadge(item.severity || 1)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getActionTypeBadge(item.action_type || 'self')}
                ${item.medication ? 
                    `<div class="text-sm text-gray-500 mt-1">Obat: ${item.medication}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(item.status || 'Sedang Berlangsung')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                    <button onclick="editRecord('${item._id || item.id}')" 
                            class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteRecord('${item._id || item.id}')" 
                            class="text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function getSeverityBadge(severity) {
    const badges = {
        1: ['bg-green-100 text-green-800', 'Ringan'],
        2: ['bg-yellow-100 text-yellow-800', 'Sedang'],
        3: ['bg-orange-100 text-orange-800', 'Berat'],
        4: ['bg-red-100 text-red-800', 'Sangat Berat'],
        5: ['bg-purple-100 text-purple-800', 'Kritis']
    };
    
    // Default to level 1 if severity is invalid
    const [classes, label] = badges[severity] || badges[1];
    return `<span class="px-2 py-1 text-xs rounded-full ${classes}">${label}</span>`;
}

function getActionTypeBadge(actionType) {
    const badges = {
        self: ['bg-blue-100 text-blue-800', 'Penanganan Mandiri'],
        doctor: ['bg-blue-100 text-blue-800', 'Konsultasi Dokter']
    };
    
    // Default to self if action type is invalid
    const [classes, label] = badges[actionType] || badges['self'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

function getStatusBadge(status) {
    const badges = {
        'Sedang Berlangsung': ['bg-yellow-100 text-yellow-800', 'Sedang Berlangsung'],
        'Sembuh': ['bg-green-100 text-green-800', 'Sembuh'],
        'Kronis': ['bg-red-100 text-red-800', 'Kronis']
    };
    
    // Default to 'Sedang Berlangsung' if status is invalid
    const [classes, label] = badges[status] || badges['Sedang Berlangsung'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

// ... rest of the code remains the same ...
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
        if (response.status === 401) {
            // Unauthorized, redirect to login
            console.warn('Unauthorized access. Redirecting to login.');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                console.error('Server error:', errorData);
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            updateTable(result.data);
            updateCharts(result.data);
            updateStatistics(result.data);
        } else {
            showNotification(result.message || 'Gagal memuat data riwayat kesehatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memuat data riwayat kesehatan', 'error');
    });
}

function updateTable(data) {
    const tableBody = document.getElementById('medicalHistoryTable');
    tableBody.innerHTML = '';
    
    if (!Array.isArray(data)) {
        console.error('Data is not an array:', data);
        return;
    }
    
    data.forEach(item => {
        if (!item) return; // Skip if item is undefined
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                ${new Date(item.diagnosis_date).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${item.condition || '-'}</td>
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-1">
                    ${Array.isArray(item.symptoms) ? item.symptoms.map(symptom => 
                        `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">${symptom}</span>`
                    ).join('') : '-'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getSeverityBadge(item.severity || 1)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getActionTypeBadge(item.action_type || 'self')}
                ${item.medication ? 
                    `<div class="text-sm text-gray-500 mt-1">Obat: ${item.medication}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(item.status || 'Sedang Berlangsung')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                    <button onclick="editRecord('${item._id || item.id}')" 
                            class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteRecord('${item._id || item.id}')" 
                            class="text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function getSeverityBadge(severity) {
    const badges = {
        1: ['bg-green-100 text-green-800', 'Ringan'],
        2: ['bg-yellow-100 text-yellow-800', 'Sedang'],
        3: ['bg-orange-100 text-orange-800', 'Berat'],
        4: ['bg-red-100 text-red-800', 'Sangat Berat'],
        5: ['bg-purple-100 text-purple-800', 'Kritis']
    };
    
    // Default to level 1 if severity is invalid
    const [classes, label] = badges[severity] || badges[1];
    return `<span class="px-2 py-1 text-xs rounded-full ${classes}">${label}</span>`;
}

function getActionTypeBadge(actionType) {
    const badges = {
        self: ['bg-blue-100 text-blue-800', 'Penanganan Mandiri'],
        doctor: ['bg-blue-100 text-blue-800', 'Konsultasi Dokter']
    };
    
    // Default to self if action type is invalid
    const [classes, label] = badges[actionType] || badges['self'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

function getStatusBadge(status) {
    const badges = {
        'Sedang Berlangsung': ['bg-yellow-100 text-yellow-800', 'Sedang Berlangsung'],
        'Sembuh': ['bg-green-100 text-green-800', 'Sembuh'],
        'Kronis': ['bg-red-100 text-red-800', 'Kronis']
    };
    
    // Default to 'Sedang Berlangsung' if status is invalid
    const [classes, label] = badges[status] || badges['Sedang Berlangsung'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

// ... rest of the code remains the same ...
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
        if (response.status === 401) {
            // Unauthorized, redirect to login
            console.warn('Unauthorized access. Redirecting to login.');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                console.error('Server error:', errorData);
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            updateTable(result.data);
            updateCharts(result.data);
            updateStatistics(result.data);
        } else {
            showNotification(result.message || 'Gagal memuat data riwayat kesehatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memuat data riwayat kesehatan', 'error');
    });
}

function updateTable(data) {
    const tableBody = document.getElementById('medicalHistoryTable');
    tableBody.innerHTML = '';
    
    if (!Array.isArray(data)) {
        console.error('Data is not an array:', data);
        return;
    }
    
    data.forEach(item => {
        if (!item) return; // Skip if item is undefined
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                ${new Date(item.diagnosis_date).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${item.condition || '-'}</td>
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-1">
                    ${Array.isArray(item.symptoms) ? item.symptoms.map(symptom => 
                        `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">${symptom}</span>`
                    ).join('') : '-'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getSeverityBadge(item.severity || 1)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getActionTypeBadge(item.action_type || 'self')}
                ${item.medication ? 
                    `<div class="text-sm text-gray-500 mt-1">Obat: ${item.medication}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(item.status || 'Sedang Berlangsung')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                    <button onclick="editRecord('${item._id || item.id}')" 
                            class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteRecord('${item._id || item.id}')" 
                            class="text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function getSeverityBadge(severity) {
    const badges = {
        1: ['bg-green-100 text-green-800', 'Ringan'],
        2: ['bg-yellow-100 text-yellow-800', 'Sedang'],
        3: ['bg-orange-100 text-orange-800', 'Berat'],
        4: ['bg-red-100 text-red-800', 'Sangat Berat'],
        5: ['bg-purple-100 text-purple-800', 'Kritis']
    };
    
    // Default to level 1 if severity is invalid
    const [classes, label] = badges[severity] || badges[1];
    return `<span class="px-2 py-1 text-xs rounded-full ${classes}">${label}</span>`;
}

function getActionTypeBadge(actionType) {
    const badges = {
        self: ['bg-blue-100 text-blue-800', 'Penanganan Mandiri'],
        doctor: ['bg-blue-100 text-blue-800', 'Konsultasi Dokter']
    };
    
    // Default to self if action type is invalid
    const [classes, label] = badges[actionType] || badges['self'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

function getStatusBadge(status) {
    const badges = {
        'Sedang Berlangsung': ['bg-yellow-100 text-yellow-800', 'Sedang Berlangsung'],
        'Sembuh': ['bg-green-100 text-green-800', 'Sembuh'],
        'Kronis': ['bg-red-100 text-red-800', 'Kronis']
    };
    
    // Default to 'Sedang Berlangsung' if status is invalid
    const [classes, label] = badges[status] || badges['Sedang Berlangsung'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

// ... rest of the code remains the same ...
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
        if (response.status === 401) {
            // Unauthorized, redirect to login
            console.warn('Unauthorized access. Redirecting to login.');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        
        if (!response.ok) {
            // Try to parse error response
            return response.json().then(errorData => {
                console.error('Server error:', errorData);
                throw new Error(errorData.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            updateTable(result.data);
            updateCharts(result.data);
            updateStatistics(result.data);
        } else {
            showNotification(result.message || 'Gagal memuat data riwayat kesehatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memuat data riwayat kesehatan', 'error');
    });
}

function updateTable(data) {
    const tableBody = document.getElementById('medicalHistoryTable');
    tableBody.innerHTML = '';
    
    if (!Array.isArray(data)) {
        console.error('Data is not an array:', data);
        return;
    }
    
    data.forEach(item => {
        if (!item) return; // Skip if item is undefined
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                ${new Date(item.diagnosis_date).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${item.condition || '-'}</td>
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-1">
                    ${Array.isArray(item.symptoms) ? item.symptoms.map(symptom => 
                        `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">${symptom}</span>`
                    ).join('') : '-'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getSeverityBadge(item.severity || 1)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getActionTypeBadge(item.action_type || 'self')}
                ${item.medication ? 
                    `<div class="text-sm text-gray-500 mt-1">Obat: ${item.medication}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(item.status || 'Sedang Berlangsung')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                    <button onclick="editRecord('${item._id || item.id}')" 
                            class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteRecord('${item._id || item.id}')" 
                            class="text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function getSeverityBadge(severity) {
    const badges = {
        1: ['bg-green-100 text-green-800', 'Ringan'],
        2: ['bg-yellow-100 text-yellow-800', 'Sedang'],
        3: ['bg-orange-100 text-orange-800', 'Berat'],
        4: ['bg-red-100 text-red-800', 'Sangat Berat'],
        5: ['bg-purple-100 text-purple-800', 'Kritis']
    };
    
    // Default to level 1 if severity is invalid
    const [classes, label] = badges[severity] || badges[1];
    return `<span class="px-2 py-1 text-xs rounded-full ${classes}">${label}</span>`;
}

function getActionTypeBadge(actionType) {
    const badges = {
        self: ['bg-blue-100 text-blue-800', 'Penanganan Mandiri'],
        doctor: ['bg-blue-100 text-blue-800', 'Konsultasi Dokter']
    };
    
    // Default to self if action type is invalid
    const [classes, label] = badges[actionType] || badges['self'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

function getStatusBadge(status) {
    const badges = {
        'Sedang Berlangsung': ['bg-yellow-100 text-yellow-800', 'Sedang Berlangsung'],
        'Sembuh': ['bg-green-100 text-green-800', 'Sembuh'],
        'Kronis': ['bg-red-100 text-red-800', 'Kronis']
    };
    
    // Default to 'Sedang Berlangsung' if status is invalid
    const [classes, label] = badges[status] || badges['Sedang Berlangsung'];
    return `<div class="text-sm text-gray-500">${label}</div>`;
}

// ... rest of the code remains the same ...
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>