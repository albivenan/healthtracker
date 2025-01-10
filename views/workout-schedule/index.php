<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Workout - HealthTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .btn-action {
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
        .btn-action:active {
            transform: scale(0.95);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .program-card {
            opacity: 0;
        }
        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .program-card, .btn-action, .form-input {
            transition: all 0.3s ease;
        }
        .btn-action {
            @apply rounded-full p-2 transition-all duration-300;
        }
        .btn-action:hover {
            @apply transform scale-110;
        }
        tbody tr {
            transition: all 0.2s ease;
        }
        tbody tr:hover {
            @apply bg-blue-50;
        }
    </style>
</head>
<body class="bg-gray-50">
<?php
$pageTitle = "Catatan Makanan";
require_once __DIR__ . '/../layouts/header.php';
?>
    <main class="max-w-7xl mx-auto px-4 py-8">
    <?php 
        $currentPage = "Jadwal Workout";
        include __DIR__ . '/../partials/breadcrumb.php'; 
        ?>
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Jadwal Workout</h1>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-gray-500">
                        <i class="fas fa-dumbbell mr-2"></i>
                        Atur program latihan Anda
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Tambah Program dengan UI yang lebih baik -->
        <div class="card bg-white p-6 rounded-xl shadow-lg mb-8 transform hover:scale-[1.01] transition-all duration-300" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 p-3 rounded-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Tambah Program Workout</h2>
                </div>
                <span class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Isi detail program workout Anda
                </span>
            </div>
            <form id="workoutForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-500"></i>Nama Program
                        </label>
                        <input type="text" name="program_name" 
                            class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" 
                            placeholder="Contoh: Push Day, Pull Day, etc." required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-2 text-blue-500"></i>Hari
                        </label>
                        <select name="day" 
                            class="form-select w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                </div>

                <div class="exercises-container">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">
                            <i class="fas fa-dumbbell mr-2 text-blue-500"></i>Daftar Gerakan
                        </h3>
                        <button type="button" onclick="addExercise()" 
                            class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Gerakan</span>
                        </button>
                    </div>
                    <div id="exercises" class="space-y-4">
                        <!-- Template gerakan -->
                        <div class="exercise-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Gerakan</label>
                                    <input type="text" name="exercise_name[]" 
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" 
                                        placeholder="Contoh: Bench Press, Squat, etc." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Set</label>
                                    <input type="number" name="sets[]" min="1" 
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Repetisi</label>
                                    <input type="number" name="reps[]" min="1" 
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all" required>
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                                    <input type="number" name="weight[]" step="0.5" min="0" 
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
                                    <button type="button" onclick="removeExercise(this)" 
                                        class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-all">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                        Reset
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Program
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Program dengan UI yang lebih baik -->
        <div class="card bg-white p-6 rounded-xl shadow-lg transform hover:scale-[1.01] transition-all duration-300" data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-lg shadow-md">
                        <i class="fas fa-dumbbell text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Program Workout</h2>
                        <p class="text-sm text-gray-500">Daftar program latihan Anda</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-bookmark text-gray-400"></i>
                                    <span>Nama Program</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-list text-gray-400"></i>
                                    <span>Detail Program</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="fas fa-cog text-gray-400"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="workoutList" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Edit Program -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-lg bg-white">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b">
                        <h3 class="text-xl font-semibold text-gray-900">Edit Program Workout</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="editForm" class="space-y-6">
                        <input type="hidden" id="editProgramId">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heading mr-2 text-blue-500"></i>Nama Program
                                </label>
                                <input type="text" id="editProgramName" 
                                    class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-day mr-2 text-blue-500"></i>Hari
                                </label>
                                <select id="editDay" 
                                    class="form-select w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-dumbbell mr-2 text-blue-500"></i>Daftar Gerakan
                                </label>
                                <button type="button" onclick="addEditExercise()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-150">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Gerakan
                                </button>
                            </div>
                            <div id="editExercises" class="space-y-4">
                                <!-- Exercise items will be added here -->
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" onclick="closeEditModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-150">
                                Batal
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            AOS.init();

            function addExercise() {
                const exercisesDiv = document.getElementById('exercises');
                const newExercise = document.querySelector('.exercise-item').cloneNode(true);
                
                // Reset input values
                newExercise.querySelectorAll('input').forEach(input => input.value = '');
                
                exercisesDiv.appendChild(newExercise);
            }

            function removeExercise(button) {
                const exercisesDiv = document.getElementById('exercises');
                if (exercisesDiv.children.length > 1) {
                    button.closest('.exercise-item').remove();
                }
            }

            document.getElementById('workoutForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                try {
                    const formData = new FormData(e.target);
                    const data = {
                        program_name: formData.get('program_name'),
                        day: formData.get('day'),
                        exercises: []
                    };

                    // Collect exercises
                    const exerciseNames = formData.getAll('exercise_name[]');
                    const sets = formData.getAll('sets[]');
                    const reps = formData.getAll('reps[]');
                    const weights = formData.getAll('weight[]');

                    for (let i = 0; i < exerciseNames.length; i++) {
                        if (exerciseNames[i].trim()) {
                            data.exercises.push({
                                name: exerciseNames[i].trim(),
                                sets: parseInt(sets[i]) || 0,
                                reps: parseInt(reps[i]) || 0,
                                weight: parseFloat(weights[i]) || 0
                            });
                        }
                    }

                    console.log('Sending data:', data);  // Debug log

                    const response = await fetch('/albi/workout-schedule', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    console.log('Response:', response);  // Debug log

                    const result = await response.json();
                    console.log('Result:', result);  // Debug log

                    if (!response.ok) {
                        throw new Error(result.message || 'Gagal menyimpan program workout');
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    e.target.reset();
                    await loadWorkouts();

                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });

            async function loadWorkouts() {
                try {
                    const workoutList = document.getElementById('workoutList');
                    workoutList.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4"></div>
                                    <p class="text-gray-500">Memuat program workout...</p>
                                </div>
                            </td>
                        </tr>
                    `;

                    const response = await fetch('/albi/workout-schedule/data');
                    const result = await response.json();
                    
                    if (!result.success) {
                        throw new Error(result.error || 'Failed to fetch workouts');
                    }

                    const data = result.data;
                    if (!Array.isArray(data)) {
                        throw new Error('Data is not in the expected format');
                    }

                    if (data.length === 0) {
                        workoutList.innerHTML = `
                            <tr>
                                <td colspan="3" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 rounded-full p-4 mb-4">
                                            <i class="fas fa-dumbbell text-4xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg mb-2">Belum ada program workout</p>
                                        <p class="text-gray-400 text-sm">Mulai tambahkan program workout Anda</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    // Group workouts by program name
                    const groupedWorkouts = data.reduce((acc, workout) => {
                        if (!acc[workout.program_name]) {
                            acc[workout.program_name] = [];
                        }
                        acc[workout.program_name].push(workout);
                        return acc;
                    }, {});

                    const tableBody = Object.entries(groupedWorkouts).map(([programName, workouts]) => {
                        const programDetails = workouts.map(workout => {
                            const exercises = Array.isArray(workout.exercises) 
                                ? workout.exercises.map((ex, index) => `
                                    <div class="exercise-item mb-4 last:mb-0 bg-white rounded-lg shadow-sm hover:shadow transition-shadow duration-200">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <button onclick="toggleExerciseCompletion('${workout.id}', ${index})" 
                                                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200 
                                                            ${ex.completed ? 
                                                                'bg-green-500 border-green-500 hover:bg-green-600' : 
                                                                'border-gray-300 hover:border-gray-400'}"
                                                            title="${ex.completed ? 'Tandai belum selesai' : 'Tandai selesai'}">
                                                            ${ex.completed ? '<i class="fas fa-check text-white text-sm"></i>' : ''}
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                ${ex.completed ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                                                ${workout.day}
                                                            </span>
                                                            <h4 class="text-gray-900 font-medium ${ex.completed ? 'line-through text-gray-500' : ''}">${ex.name}</h4>
                                                        </div>
                                                        ${ex.completed && ex.completed_at ? `
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Selesai pada: ${new Date(ex.completed_at.$date).toLocaleString('id-ID')}
                                                            </p>
                                                        ` : ''}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        ${ex.sets} set × ${ex.reps} rep
                                                    </div>
                                                    ${ex.weight ? `
                                                        <div class="text-sm text-gray-500">
                                                            ${ex.weight} kg
                                                        </div>
                                                    ` : ''}
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-500" 
                                                        style="width: ${(ex.completed ? 100 : 0)}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  `).join('')
                                : '';
                            return exercises;
                        }).join('<div class="border-t border-gray-200 my-4"></div>');

                        return `
                            <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-dumbbell text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">${programName}</div>
                                            <div class="text-sm text-gray-500">
                                                ${workouts.length} latihan
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        ${programDetails}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button onclick="editWorkout('${workouts[0].id}')" 
                                            class="transition-all duration-150 ease-in-out bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-lg group">
                                            <i class="fas fa-edit group-hover:scale-110 transform transition-transform duration-150"></i>
                                        </button>
                                        <button onclick="deleteWorkout('${workouts[0].id}')" 
                                            class="transition-all duration-150 ease-in-out bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg group">
                                            <i class="fas fa-trash group-hover:scale-110 transform transition-transform duration-150"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    workoutList.innerHTML = tableBody;

                } catch (error) {
                    console.error('Error:', error);
                    const workoutList = document.getElementById('workoutList');
                    workoutList.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-8">
                                <div class="flex flex-col items-center justify-center text-red-500">
                                    <div class="bg-red-100 rounded-full p-4 mb-4">
                                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                                    </div>
                                    <p class="text-lg mb-2">Gagal memuat data</p>
                                    <p class="text-sm text-red-400">${error.message}</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }

            async function deleteWorkout(id) {
                try {
                    const willDelete = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (willDelete.isConfirmed) {
                        const response = await fetch(`/albi/workout-schedule/delete/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });

                        if (response.ok) {
                            Swal.fire(
                                'Terhapus!',
                                'Program workout berhasil dihapus.',
                                'success'
                            );
                            await loadWorkouts();
                        } else {
                            const result = await response.json();
                            throw new Error(result.error || 'Gagal menghapus program workout');
                        }
                        }
                    } catch (error) {
                    console.error('Delete error:', error);
                    Swal.fire(
                        'Error!',
                        error.message,
                        'error'
                    );
                }
            }

            // Fungsi untuk menampilkan modal edit
            async function editWorkout(id) {
                try {
                    // Show loading state
                    Swal.fire({
                        title: 'Memuat...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`/albi/workout-schedule/get/${id}`);
                    if (!response.ok) {
                        throw new Error('Failed to fetch workout data');
                    }

                    const result = await response.json();
                    if (!result.success || !result.data) {
                        throw new Error(result.error || 'Invalid workout data');
                    }

                    const workout = result.data;

                    // Populate form fields
                    document.getElementById('editProgramId').value = workout.id;
                    document.getElementById('editProgramName').value = workout.program_name;
                    document.getElementById('editDay').value = workout.day;

                    // Clear and populate exercises
                    const editExercisesDiv = document.getElementById('editExercises');
                    editExercisesDiv.innerHTML = '';

                    if (Array.isArray(workout.exercises)) {
                        workout.exercises.forEach(exercise => {
                            addEditExercise(exercise);
                        });
                    } else {
                        addEditExercise(); // Add one empty exercise form
                    }

                    // Close loading and show modal
                    Swal.close();
                    document.getElementById('editModal').classList.remove('hidden');

                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Gagal memuat data workout'
                    });
                }
            }

            // Function to add exercise field in edit form
            function addEditExercise(exercise = null) {
                const exerciseHtml = `
                    <div class="exercise-item bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Gerakan</label>
                                <input type="text" name="edit_exercise_name[]" 
                                    value="${exercise ? exercise.name : ''}"
                                    class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Set × Rep</label>
                                <div class="flex space-x-2">
                                    <input type="number" name="edit_sets[]" 
                                        value="${exercise ? exercise.sets : ''}"
                                        placeholder="Set" min="1"
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    <input type="number" name="edit_reps[]" 
                                        value="${exercise ? exercise.reps : ''}"
                                        placeholder="Rep" min="1"
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                                <div class="flex items-center">
                                    <input type="number" name="edit_weight[]" 
                                        value="${exercise ? exercise.weight || '' : ''}"
                                        step="0.5" min="0"
                                        class="form-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <button type="button" onclick="removeEditExercise(this)" 
                                        class="ml-2 p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('editExercises').insertAdjacentHTML('beforeend', exerciseHtml);
            }

            // Function to remove exercise field
            function removeEditExercise(button) {
                const exercisesDiv = document.getElementById('editExercises');
                if (exercisesDiv.children.length > 1) {
                    button.closest('.exercise-item').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Program workout harus memiliki minimal satu gerakan'
                    });
                }
            }

            // Function to close edit modal
            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            // Handle edit form submission
            document.getElementById('editForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                try {
                    const id = document.getElementById('editProgramId').value;
                    const programName = document.getElementById('editProgramName').value;
                    const day = document.getElementById('editDay').value;

                    // Collect exercises data
                    const exercises = [];
                    const names = document.getElementsByName('edit_exercise_name[]');
                    const sets = document.getElementsByName('edit_sets[]');
                    const reps = document.getElementsByName('edit_reps[]');
                    const weights = document.getElementsByName('edit_weight[]');

                    for (let i = 0; i < names.length; i++) {
                        if (names[i].value.trim()) {
                            exercises.push({
                                name: names[i].value.trim(),
                                sets: parseInt(sets[i].value) || 0,
                                reps: parseInt(reps[i].value) || 0,
                                weight: parseFloat(weights[i].value) || 0
                            });
                        }
                    }

                    // Validate data
                    if (!programName.trim()) {
                        throw new Error('Nama program harus diisi');
                    }
                    if (!day) {
                        throw new Error('Hari harus dipilih');
                    }
                    if (exercises.length === 0) {
                        throw new Error('Minimal harus ada satu gerakan');
                    }

                    // Show loading state
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`/albi/workout-schedule/update/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            program_name: programName,
                            day: day,
                            exercises: exercises
                        })
                    });

                    const result = await response.json();
                    if (!result.success) {
                        throw new Error(result.error || 'Gagal memperbarui program workout');
                    }

                    // Show success message
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Program workout berhasil diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Close modal and reload data
                    closeEditModal();
                    await loadWorkouts();

                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Gagal memperbarui program workout'
                    });
                }
            });

            // Function to toggle exercise completion
            async function toggleExerciseCompletion(workoutId, exerciseIndex) {
                try {
                    const response = await fetch(`/albi/workout-schedule/toggle-completion/${workoutId}/${exerciseIndex}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (!result.success) {
                        throw new Error(result.error || 'Failed to update exercise status');
                    }

                    // Reload the workouts to show updated status
                    await loadWorkouts();

                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to update exercise status'
                    });
                }
            }

            // Load workouts when page loads
            document.addEventListener('DOMContentLoaded', loadWorkouts);
        </script>
    </main>
</body>
</html>