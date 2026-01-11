<x-app-layout>

    {{-- CUSTOM CSS --}}
    @push('styles')
        <style>
            /* ===== LAYOUT ===== */
            .container {
                max-width: 1200px;
                margin: auto;
                padding: 7px 8px;
            }

            /* ===== HEADER ===== */
            .header-grid {
                display: grid;
                grid-template-columns: 1fr 3fr;
                gap: 24px;
                align-items: center;
            }

            .header-title h2 {
                font-size: 24px;
                font-weight: bold;
                color: #1f2937;
            }

            .header-title p {
                font-size: 14px;
                color: #6b7280;
                margin-top: 4px;
            }

            /* ===== SEARCH BAR ===== */
            .search-box {
                background: #fff;
                padding: 16px;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            }

            .search-grid {
                display: grid;
                grid-template-columns: 3fr 0.5fr 0.5fr;
                gap: 12px;
            }

            input,
            select,
            button {
                padding: 10px 12px;
                border-radius: 8px;
                border: 1px solid #d1d5db;
                font-size: 14px;
            }

            input:focus,
            select:focus {
                outline: none;
                border-color: #2563eb;
            }

            button {
                background: #2563eb;
                color: white;
                cursor: pointer;
                border: none;
            }

            button:hover {
                background: #1e40af;
            }

            /* ===== INFO ===== */
            .info-text {
                text-align: center;
                color: #6b7280;
                margin-bottom: 16px;
            }

            /* ===== RESULTS GRID ===== */
            .results-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 20px;
            }

            /* ===== CARD ===== */
            .card {
                background: #fff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                transition: transform .2s;
            }

            .card:hover {
                transform: translateY(-4px);
            }

            .card img,
            .card video {
                width: 100%;
                height: 220px;
                object-fit: cover;
            }

            .card-body {
                padding: 12px;
                font-size: 14px;
                color: #4b5563;
            }

            /* ===== LOAD MORE ===== */
            .load-more {
                margin-top: 30px;
                text-align: center;
            }

            .load-more button {
                padding: 12px 24px;
                font-size: 14px;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 768px) {
                .header-grid {
                    grid-template-columns: 1fr;
                }

                .search-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="container">
            <div class="header-grid">
                <div class="header-title">
                    <h2>Pixabay Search</h2>
                    <p>Search free images and videos from Pixabay</p>
                </div>

                <div class="search-box">
                    <div class="search-grid">
                        <input type="text" id="search" placeholder="Search images or videos..." />
                        <select id="type">
                            <option value="image">Images</option>
                            <option value="video">Videos</option>
                        </select>
                        <button onclick="searchPixabay()">Search</button>
                    </div>
                    <div style="margin-top: 10px; font-size: 14px; color: #6b7280; format: italic;">
                        Popular Categories: Technology, People, Animals, Travel, Food, Sports, Architecture
                    </div>

                </div>

            </div>
        </div>
    </x-slot>

    {{-- MAIN --}}
    <div class="container">
        <div class="info-text">Results will appear below 👇 </div>

        <div id="results" class="results-grid"></div>

        <div class="load-more" id="loadMoreBox" style="display:none;">
            <button onclick="loadMore()">Load More</button>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        let currentPage = 1;
        let currentQuery = 'nature';
        let currentType = 'image';

        function searchPixabay(reset = true) {
            if (reset) {
                currentPage = 1;
                document.getElementById('results').innerHTML = '';
            }

            const query = document.getElementById('search').value || currentQuery;
            const type = document.getElementById('type').value;

            currentQuery = query;
            currentType = type;

            fetch(`/pixabay/search?query=${query}&type=${type}&page=${currentPage}`)
                .then(res => res.json())
                .then(data => {
                    const results = document.getElementById('results');

                    if (!data.hits || data.hits.length === 0) {
                        results.innerHTML = `<div>No results found 😕</div>`;
                        return;
                    }

                    data.hits.forEach(item => {
                        if (type === 'image') {
                            results.innerHTML += `
                                <div class="card">
                                    <img src="${item.webformatURL}" alt="Image">
                                    <div class="card-body">
                                        👍 ${item.likes} • 👁 ${item.views}
                                        • ⬇ ${item.downloads}
                                        <a href="${item.largeImageURL}" download style="float:right; margin-right: 5px;">⬇</a>
                                        <a href="${item.largeImageURL}" target="_blank" style="float:right; margin-right: 5px;">🔍 View</a>
                                    </div>
                                </div>
                            `;
                        } else {
                            results.innerHTML += `
                                <div class="card">
                                    <video controls>
                                        <source src="${item.videos.small.url}" type="video/mp4">
                                    </video>
                                    <div class="card-body">
                                        ▶ ${item.views} views
                                        • 👍 ${item.likes}
                                        <a href="${item.videos.large.url}" download target="_blank" style="float:right;">⬇</a>
                                    </div>
                                </div>
                            `;
                        }
                    });

                    document.getElementById('loadMoreBox').style.display =
                        data.hits.length >= 10 ? 'block' : 'none';
                });
        }
        function loadMore() {
            currentPage++;
            searchPixabay(false);
        }

        /* ===== DEFAULT SEARCH ===== */
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('search').value = 'nature';
            searchPixabay();
        });
    </script>

</x-app-layout>
