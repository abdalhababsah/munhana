<div id="search-modal"
    class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-white shadow-xl rounded-xl ring-1 ring-gray-900/5 pointer-events-auto overflow-hidden">
            <div class="relative z-[60] border-b border-gray-100">
                <input type="search" id="global-search-input"
                    class="form-input w-full border-none focus:ring-0 py-4 ps-12 pe-12 text-base bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none"
                    placeholder="{{ __('messages.search') }}..." autocomplete="off">
            </div>

            <!-- Search Results -->
            <div id="global-search-results" class="max-h-[50vh] overflow-y-auto p-2 bg-white scroll-smooth"
                style="display: none;">
                <!-- Results will be injected here -->
            </div>

            <div id="global-search-empty" class="py-10 text-center text-gray-400 hidden">
                <div class="flex flex-col items-center justify-center">
                    <i class="uil uil-search-alt text-4xl mb-2 opacity-50"></i>
                    <p class="text-sm">{{ __('messages.no_results_found') }}</p>
                </div>
            </div>

            <div id="global-search-loading" class="py-10 text-center text-primary hidden">
                <i class="uil uil-spinner-alt animate-spin text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('global-search-input');
        const searchResults = document.getElementById('global-search-results');
        const searchEmpty = document.getElementById('global-search-empty');
        const searchLoading = document.getElementById('global-search-loading');
        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                searchEmpty.classList.add('hidden');
                searchLoading.classList.add('hidden'); // Ensure loading is hidden
                return;
            }

            searchLoading.classList.remove('hidden');
            searchEmpty.classList.add('hidden');
            searchResults.style.display = 'none';

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('backend.global.search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchLoading.classList.add('hidden');
                        searchResults.innerHTML = '';

                        let hasResults = false;

                        // Helper to create category section
                        const createSection = (title, items) => {
                            if (!items || items.length === 0) return '';
                            hasResults = true;
                            let html = `<div class="mb-3"><h5 class="px-3 py-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">${title}</h5><ul class="space-y-0.5">`;
                            items.forEach(item => {
                                html += `
                                    <li>
                                        <a href="${item.url}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-50 hover:text-primary transition-all duration-200 group">
                                            <div class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-colors">
                                                <i class="uil ${item.icon || 'uil-link'} text-base"></i>
                                            </div>
                                            <div class="ms-3 flex-1 overflow-hidden">
                                                <p class="text-sm font-medium text-gray-700 group-hover:text-primary truncate transition-colors">${item.title}</p>
                                                ${item.subtitle ? `<p class="text-xs text-gray-400 truncate">${item.subtitle}</p>` : ''}
                                            </div>
                                            <i class="uil uil-arrow-right text-gray-300 group-hover:text-primary opacity-0 group-hover:opacity-100 transition-all text-sm"></i>
                                        </a>
                                    </li>
                                `;
                            });
                            html += '</ul></div>';
                            return html;
                        };

                        let htmlContent = '';
                        htmlContent += createSection("{{ __('messages.pages') }}", data.pages);
                        htmlContent += createSection("{{ __('messages.projects') }}", data.projects);
                        htmlContent += createSection("{{ __('messages.clients') }}", data.clients);
                        htmlContent += createSection("{{ __('messages.users') }}", data.users);

                        if (hasResults) {
                            searchResults.innerHTML = htmlContent;
                            searchResults.style.display = 'block';
                        } else {
                            searchEmpty.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchLoading.classList.add('hidden');
                    });
            }, 300); // 300ms debounce
        });
    });
</script>