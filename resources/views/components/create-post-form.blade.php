<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex items-start space-x-4 mb-4">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-11 h-11 rounded-full border border-gray-200 object-cover shadow-sm">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" class="w-11 h-11 rounded-full border border-gray-200 shadow-sm">
            @endif
            <div class="flex-1">
                <textarea name="content" rows="3" placeholder="Bạn đang nghĩ gì về chuyến đi này?" required
                          class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-1 focus:ring-indigo-400 transition resize-none text-gray-700"></textarea>

                <div id="image-preview-container" class="hidden mt-4 grid grid-cols-2 gap-2 relative">
                    </div>

                <div id="selected-location" class="hidden mt-2 flex items-center text-sm text-indigo-600 font-medium bg-indigo-50 p-2 rounded-lg w-fit">
                    <span id="location-text"></span>
                    <button type="button" onclick="removeLocation()" class="ml-2 text-gray-400 hover:text-red-500">×</button>
                    <input type="hidden" name="location_name" id="location-input">
                </div>
            </div>
        </div>

        <div id="location-search-box" class="hidden mb-4 relative px-12">
            <input type="text" id="search-input" placeholder="Tìm địa điểm..." class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-400">
            <div id="search-results" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 shadow-lg max-h-48 overflow-y-auto hidden"></div>
        </div>

        <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
            <div class="flex space-x-2">
                <label class="flex items-center space-x-2 text-gray-500 hover:text-green-600 hover:bg-green-50 px-3 py-2 rounded-xl transition cursor-pointer">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path></svg>
                    <span class="text-sm font-medium hidden sm:block">Ảnh/Video</span>
                    <input type="file" name="media_files[]" id="media-input" multiple accept="image/*" class="hidden">
                </label>

                <button type="button" onclick="toggleLocationSearch()" class="flex items-center space-x-2 text-gray-500 hover:text-red-500 hover:bg-red-50 px-3 py-2 rounded-xl transition">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path></svg>
                    <span class="text-sm font-medium hidden sm:block">Check-in</span>
                </button>
            </div>
            <button type="submit" class="bg-[#5A67D8] hover:bg-indigo-600 text-white font-semibold py-2 px-6 rounded-full shadow-md transition">Đăng bài</button>
        </div>
    </form>
</div>

<script>
// XỬ LÝ XEM TRƯỚC ẢNH
document.getElementById('media-input').addEventListener('change', function(event) {
    const container = document.getElementById('image-preview-container');
    container.innerHTML = ''; // Xóa ảnh cũ nếu có
    const files = event.target.files;

    if (files.length > 0) {
        container.classList.remove('hidden');
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-40 object-cover rounded-xl border border-gray-100">
                        <button type="button" onclick="clearImages()" class="absolute top-2 right-2 bg-black/50 text-white rounded-full p-1 hover:bg-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg>
                        </button>
                    `;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        });
    } else {
        container.classList.add('hidden');
    }
});

function clearImages() {
    document.getElementById('media-input').value = "";
    document.getElementById('image-preview-container').innerHTML = "";
    document.getElementById('image-preview-container').classList.add('hidden');
}

// GIỮ NGUYÊN CÁC HÀM CŨ (Location Search)
let searchTimeout;
function toggleLocationSearch() {
    document.getElementById('location-search-box').classList.toggle('hidden');
    document.getElementById('search-input').focus();
}

document.getElementById('search-input').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const query = e.target.value;
    if (query.length < 3) return;
    searchTimeout = setTimeout(() => {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=5&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                const resultsBox = document.getElementById('search-results');
                resultsBox.innerHTML = '';
                resultsBox.classList.remove('hidden');
                data.forEach(place => {
                    const div = document.createElement('div');
                    div.className = 'p-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50';
                    div.innerText = place.display_name;
                    div.onclick = () => selectLocation(place.display_name);
                    resultsBox.appendChild(div);
                });
            });
    }, 500);
});

function selectLocation(name) {
    const shortName = name.split(',')[0];
    document.getElementById('location-input').value = shortName;
    document.getElementById('location-text').innerText = '📍 ' + shortName;
    document.getElementById('selected-location').classList.remove('hidden');
    document.getElementById('location-search-box').classList.add('hidden');
}

function removeLocation() {
    document.getElementById('location-input').value = '';
    document.getElementById('selected-location').classList.add('hidden');
}
</script>
