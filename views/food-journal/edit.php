<?php
$pageTitle = "Edit Catatan Makanan";
require_once __DIR__ . '/../layouts/header.php';

// Daftar makanan umum untuk sugesti
$commonFoods = [
    ['name' => 'Nasi Putih', 'calories' => 130, 'portion' => '100 gram'],
    ['name' => 'Ayam Goreng', 'calories' => 260, 'portion' => '100 gram'],
    ['name' => 'Telur Goreng', 'calories' => 155, 'portion' => '1 butir'],
    ['name' => 'Tempe Goreng', 'calories' => 180, 'portion' => '100 gram'],
    ['name' => 'Tahu Goreng', 'calories' => 115, 'portion' => '100 gram'],
];
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header dengan Breadcrumb -->
        <div class="mb-8">
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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="/albi/food-journal" class="ml-1 text-gray-700 hover:text-blue-500 md:ml-2">Catatan Makanan</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Edit Catatan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Catatan Makanan</h1>
            <p class="text-gray-600">Ubah detail catatan makanan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Form Edit -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <form id="editFoodJournalForm" class="space-y-6">
                        <!-- Pencarian Makanan -->
                        <div class="relative">
                            <label for="food_search" class="block text-sm font-medium text-gray-700 mb-2">
                                Cari Makanan
                            </label>
                            <div class="relative">
                                <input type="text" id="food_search" 
                                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Ketik untuk mencari makanan...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <!-- Hasil Pencarian -->
                            <div id="searchResults" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg hidden">
                                <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                    <!-- Hasil pencarian akan ditampilkan di sini -->
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label for="food_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Makanan</label>
                            <input type="text" id="food_name" name="food_name" required
                                value="<?php echo htmlspecialchars($journal->food_name); ?>"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="portion" class="block text-sm font-medium text-gray-700 mb-2">Porsi</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" step="0.5" min="0.5" id="portion" name="portion" required
                                        value="<?php echo $journal->portion; ?>"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">porsi</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">Kalori</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" min="1" id="calories" name="calories" required
                                        value="<?php echo $journal->calories; ?>"
                                        data-base-calories="<?php echo $journal->calories; ?>"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">kkal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="meal_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Makan</label>
                            <select id="meal_time" name="meal_time" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Pilih waktu makan</option>
                                <option value="Sarapan" <?php echo $journal->meal_time === 'Sarapan' ? 'selected' : ''; ?>>Sarapan</option>
                                <option value="Makan Siang" <?php echo $journal->meal_time === 'Makan Siang' ? 'selected' : ''; ?>>Makan Siang</option>
                                <option value="Makan Malam" <?php echo $journal->meal_time === 'Makan Malam' ? 'selected' : ''; ?>>Makan Malam</option>
                                <option value="Camilan" <?php echo $journal->meal_time === 'Camilan' ? 'selected' : ''; ?>>Camilan</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Tambahkan catatan tentang makanan ini..."><?php echo htmlspecialchars($journal->notes ?? ''); ?></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="/albi/food-journal" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Makanan Umum dan Tips -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Makanan Umum</h2>
                    <div class="space-y-4">
                        <?php foreach ($commonFoods as $food): ?>
                            <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-200"
                                onclick="selectCommonFood('<?php echo $food['name']; ?>', <?php echo $food['calories']; ?>)">
                                <h3 class="font-medium text-gray-800"><?php echo $food['name']; ?></h3>
                                <div class="mt-1 flex justify-between text-sm text-gray-500">
                                    <span><?php echo $food['portion']; ?></span>
                                    <span><?php echo $food['calories']; ?> kkal</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 mt-6 text-white">
                    <h2 class="text-lg font-semibold mb-4">Tips Pencatatan</h2>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Catat makanan segera setelah makan untuk hasil yang akurat</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Gunakan timbangan atau ukuran porsi yang tepat</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Tambahkan catatan untuk detail tambahan seperti cara memasak</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk pencarian makanan
function setupFoodSearch() {
    const searchInput = document.getElementById('food_search');
    const searchResults = document.getElementById('searchResults');
    const foodNameInput = document.getElementById('food_name');
    const caloriesInput = document.getElementById('calories');

    // Data makanan umum untuk pencarian
    const commonFoods = <?php echo json_encode($commonFoods); ?>;

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        if (searchTerm.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        const filteredFoods = commonFoods.filter(food => 
            food.name.toLowerCase().includes(searchTerm)
        );

        if (filteredFoods.length > 0) {
            searchResults.innerHTML = filteredFoods.map(food => `
                <li class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50"
                    onclick="selectFood('${food.name}', ${food.calories})">
                    <div class="flex items-center">
                        <span class="font-medium text-gray-900">${food.name}</span>
                        <span class="text-gray-500 ml-2">${food.calories} kkal</span>
                    </div>
                </li>
            `).join('');
            searchResults.classList.remove('hidden');
        } else {
            searchResults.classList.add('hidden');
        }
    });

    // Sembunyikan hasil pencarian saat klik di luar
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.add('hidden');
        }
    });
}

// Fungsi untuk memilih makanan dari hasil pencarian
function selectFood(name, calories) {
    document.getElementById('food_name').value = name;
    document.getElementById('calories').value = calories;
    document.getElementById('calories').dataset.baseCalories = calories;
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('food_search').value = '';
}

// Fungsi untuk memilih makanan umum
function selectCommonFood(name, calories) {
    document.getElementById('food_name').value = name;
    document.getElementById('calories').value = calories;
    document.getElementById('calories').dataset.baseCalories = calories;
    document.getElementById('portion').value = '1';
}

// Event listener untuk form submission
document.getElementById('editFoodJournalForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    try {
        const response = await fetch('/albi/food-journal/update/<?php echo $journal->_id; ?>', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (response.ok) {
            alert('Catatan makanan berhasil diperbarui');
            window.location.href = '/albi/food-journal';
        } else {
            alert(result.error || 'Terjadi kesalahan saat memperbarui catatan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui catatan');
    }
});

// Kalkulasi kalori otomatis saat porsi berubah
document.getElementById('portion').addEventListener('input', function(e) {
    const baseCalories = document.getElementById('calories').dataset.baseCalories;
    if (baseCalories) {
        const newCalories = Math.round(baseCalories * e.target.value);
        document.getElementById('calories').value = newCalories;
    }
});

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    setupFoodSearch();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 