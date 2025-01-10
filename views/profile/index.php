<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - HealthTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
        .photo-upload {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        .photo-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-upload .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .photo-upload:hover .overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="gradient-bg">
    <!-- Navbar -->
    <!-- <nav class="bg-white shadow-lg sticky top-0 z-50 backdrop-filter backdrop-blur-lg bg-opacity-90">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/albi/dashboard" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-transparent bg-clip-text hover:scale-105 transition-transform">HealthTracker</a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="/albi/food-journal" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Catatan Makanan
                    </a>
                    <a href="/albi/workout-schedule" class="nav-link text-gray-700 hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">
                        Jadwal Workout
                    </a>
                    <div class="relative">
                        <button class="flex items-center space-x-3 text-gray-700 hover:text-blue-500 focus:outline-none bg-gray-50 rounded-full px-4 py-2 transition-all duration-300 hover:bg-gray-100">
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-blue-500 ring-offset-2" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']); ?>&background=random" alt="Profile">
                            <span class="font-medium"><?php echo htmlspecialchars($_SESSION['user']); ?></span>
                        </button>
                    </div>
                    <a href="/albi/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-full hover:from-red-600 hover:to-red-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Keluar</a>
                </div>
            </div>
        </div>
    </nav> -->
    <?php
$pageTitle = "Catatan Makanan";
require_once __DIR__ . '/../layouts/header.php';
?>

    <main class="max-w-7xl mx-auto px-4 py-8">
    <?php 
        use HealthTracker\Utils\Render;
        ?>
        <?php echo Render::breadcrumb("My Profile");
        ?>
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-gray-500">
                        <i class="fas fa-user-circle mr-2"></i>
                        <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Profile Photo Card -->
            <div class="card p-6 rounded-xl shadow-lg text-center" data-aos="fade-up">
                <div class="photo-upload mx-auto mb-4" onclick="document.getElementById('photo-input').click()">
                    <img id="profile-photo" src="/albi/uploads/profile/default.jpg" alt="Profile Photo">
                    <div class="overlay">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </div>
                </div>
                <input type="file" id="photo-input" class="hidden" accept="image/*" onchange="uploadPhoto(this)">
                <h2 id="profile-name" class="text-xl font-semibold text-gray-800 mb-2">Loading...</h2>
                <p id="profile-email" class="text-gray-600 mb-4">Loading...</p>
                <button onclick="editProfile()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </button>
            </div>

            <!-- Profile Details Card -->
            <div class="card p-6 rounded-xl shadow-lg md:col-span-2" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>Nomor Telepon
                        </label>
                        <p id="profile-phone" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-blue-500 mr-2"></i>Tanggal Lahir
                        </label>
                        <p id="profile-birth-date" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars text-blue-500 mr-2"></i>Jenis Kelamin
                        </label>
                        <p id="profile-gender" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Alamat
                        </label>
                        <p id="profile-address" class="text-gray-800">Loading...</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Kesehatan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-ruler-vertical text-blue-500 mr-2"></i>Tinggi Badan
                        </label>
                        <p id="profile-height" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-weight text-blue-500 mr-2"></i>Berat Badan
                        </label>
                        <p id="profile-weight" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-notes-medical text-blue-500 mr-2"></i>Kondisi Medis
                        </label>
                        <p id="profile-medical-conditions" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-allergies text-blue-500 mr-2"></i>Alergi
                        </label>
                        <p id="profile-allergies" class="text-gray-800">Loading...</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <h2 class="text-xl font-bold text-gray-800 mb-6">Kontak Darurat</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Nama
                        </label>
                        <p id="emergency-contact-name" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>Nomor Telepon
                        </label>
                        <p id="emergency-contact-phone" class="text-gray-800">Loading...</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-friends text-blue-500 mr-2"></i>Hubungan
                        </label>
                        <p id="emergency-contact-relationship" class="text-gray-800">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        AOS.init();

        // Load profile data
        async function loadProfile() {
            try {
                const response = await fetch('/albi/profile/data');
                if (!response.ok) throw new Error('Failed to fetch profile');
                
                const profile = await response.json();
                
                // Update profile photo
                document.getElementById('profile-photo').src = profile.photo_url || '/albi/uploads/profile/default.jpg';
                
                // Update basic info
                document.getElementById('profile-name').textContent = profile.name || 'Belum diisi';
                document.getElementById('profile-email').textContent = profile.email || 'Belum diisi';
                document.getElementById('profile-phone').textContent = profile.phone || 'Belum diisi';
                document.getElementById('profile-birth-date').textContent = profile.birth_date || 'Belum diisi';
                document.getElementById('profile-gender').textContent = profile.gender || 'Belum diisi';
                document.getElementById('profile-address').textContent = profile.address || 'Belum diisi';
                
                // Update health info
                document.getElementById('profile-height').textContent = profile.height ? `${profile.height} cm` : 'Belum diisi';
                document.getElementById('profile-weight').textContent = profile.weight ? `${profile.weight} kg` : 'Belum diisi';
                document.getElementById('profile-medical-conditions').textContent = 
                    profile.medical_conditions?.length > 0 ? profile.medical_conditions.join(', ') : 'Tidak ada';
                document.getElementById('profile-allergies').textContent = 
                    profile.allergies?.length > 0 ? profile.allergies.join(', ') : 'Tidak ada';
                
                // Update emergency contact
                document.getElementById('emergency-contact-name').textContent = 
                    profile.emergency_contact?.name || 'Belum diisi';
                document.getElementById('emergency-contact-phone').textContent = 
                    profile.emergency_contact?.phone || 'Belum diisi';
                document.getElementById('emergency-contact-relationship').textContent = 
                    profile.emergency_contact?.relationship || 'Belum diisi';
                
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal memuat data profil', 'error');
            }
        }

        // Upload photo
        async function uploadPhoto(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('photo', input.files[0]);

                try {
                    const response = await fetch('/albi/profile/upload-photo', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) throw new Error('Failed to upload photo');
                    
                    const data = await response.json();
                    document.getElementById('profile-photo').src = data.photo_url;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Foto profil berhasil diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengupload foto', 'error');
                }
            }
        }

        // Edit profile
        async function editProfile() {
            try {
                const response = await fetch('/albi/profile/data');
                if (!response.ok) throw new Error('Failed to fetch profile');
                
                const profile = await response.json();
                
                const { value: formData } = await Swal.fire({
                    title: 'Edit Profil',
                    html: `
                        <form class="text-left">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" id="edit_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.name || ''}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="edit_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.email || ''}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <input type="tel" id="edit_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.phone || ''}">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <input type="date" id="edit_birth_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.birth_date || ''}">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <select id="edit_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" ${profile.gender === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                                        <option value="Perempuan" ${profile.gender === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea id="edit_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        rows="2">${profile.address || ''}</textarea>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Tinggi Badan (cm)</label>
                                    <input type="number" id="edit_height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.height || ''}" min="0" step="0.1">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
                                    <input type="number" id="edit_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        value="${profile.weight || ''}" min="0" step="0.1">
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">Kontak Darurat</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <input type="text" id="edit_emergency_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        placeholder="Nama" value="${profile.emergency_contact?.name || ''}">
                                    <input type="tel" id="edit_emergency_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        placeholder="Nomor Telepon" value="${profile.emergency_contact?.phone || ''}">
                                    <input type="text" id="edit_emergency_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                        placeholder="Hubungan" value="${profile.emergency_contact?.relationship || ''}">
                                </div>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    width: '800px',
                    preConfirm: () => {
                        return {
                            name: document.getElementById('edit_name').value,
                            email: document.getElementById('edit_email').value,
                            phone: document.getElementById('edit_phone').value,
                            birth_date: document.getElementById('edit_birth_date').value,
                            gender: document.getElementById('edit_gender').value,
                            address: document.getElementById('edit_address').value,
                            height: document.getElementById('edit_height').value,
                            weight: document.getElementById('edit_weight').value,
                            emergency_contact: {
                                name: document.getElementById('edit_emergency_name').value,
                                phone: document.getElementById('edit_emergency_phone').value,
                                relationship: document.getElementById('edit_emergency_relationship').value
                            }
                        };
                    }
                });

                if (formData) {
                    const response = await fetch('/albi/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    if (!response.ok) throw new Error('Failed to update profile');

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Profil berhasil diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    loadProfile();
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal memperbarui profil', 'error');
            }
        }

        // Load profile when page loads
        document.addEventListener('DOMContentLoaded', loadProfile);
    </script>
</body>
</html> 