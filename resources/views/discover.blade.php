<x-layout>
    <div class="p-5">
        <div
            class="sticky top-3 z-20 p-3 bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px]">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <h1 class="text-xl font-medium text-white/80">Discover</h1>
                    <p class="text-orange-400 font-medium text-sm">Find events across every category.</p>
                </div>
                <form id="discover-search-form" method="GET" action="{{ route('discover') }}"
                    class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                    <input id="discover-search-q" type="text" name="q" value="{{ $query }}"
                        placeholder="Search events..."
                        class="p-3 py-2 rounded-lg bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 w-full md:w-72">
                    <input id="discover-search-location" type="text" name="location" value="{{ $location }}"
                        placeholder="Location"
                        class="p-3 py-2 rounded-lg bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 w-full md:w-48">
                    <button type="submit"
                        class="p-0.5 md:ml-2 h-fit flex gap-1 items-center bg-orange-400 text-black/60 font-medium text-sm w-full md:w-fit rounded-4xl">
                        <span
                            class="bg-black/90 border border-green-400/15 size-8 flex items-center justify-center rounded-[50%]">
                            <span class="text-sm text-orange-400/90">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-search-icon lucide-search">
                                    <path d="m21 21-4.34-4.34" />
                                    <circle cx="11" cy="11" r="8" />
                                </svg>
                            </span>
                        </span>

                        <span class="pr-2 text-black/90 font-mono text-xs font-medium">Search event</span>
                    </button>
                </form>
            </div>
        </div>

        <div id="discover-results" class="mt-6 {{ $isSearching ? '' : 'hidden' }}">
            @include('partials.discover-search-results', ['events' => $events])
        </div>

        <div id="discover-categories" class="mt-6 space-y-5 {{ $isSearching ? 'hidden' : '' }}">
            @forelse($categories as $category)
                <section class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white/80 font-medium text-xl ml-1">{{ $category['name'] }}</h2>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                class="h-9 w-9 rounded-full flex items-center justify-center bg-orange-400 border border-white/10 text-black hover:text-white hover:border-white/30 transition"
                                data-carousel-prev="cat-{{ $loop->index }}" aria-label="Previous">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-big-left-dash-icon lucide-arrow-big-left-dash">
                                    <path
                                        d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                                    <path d="M20 9v6" />
                                </svg>
                            </button>
                            <button type="button"
                                class="h-9 w-9 rounded-full flex items-center justify-center bg-orange-400 border border-white/10 text-black hover:text-white hover:border-white/30 transition"
                                data-carousel-next="cat-{{ $loop->index }}" aria-label="Next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-big-right-dash-icon lucide-arrow-big-right-dash">
                                    <path
                                        d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                                    <path d="M4 9v6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @php($slides = $category['events']->chunk(5))

                    <div id="cat-{{ $loop->index }}" class="flex overflow-hidden scroll-smooth snap-x snap-mandatory">
                        @foreach ($slides as $slide)
                            <div class="min-w-full snap-start">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                                    @foreach ($slide as $event)
                                        <a href="{{ route('event.show', $event->id) }}" class="group block">
                                            <div
                                                class="w-full h-[130px] rounded-2xl overflow-hidden border border-white/10">
                                                <img src="{{ $event->event_image ? asset('storage/' . $event->event_image) : asset('default.png') }}"
                                                    class="object-cover w-full h-full group-hover:scale-105 transition"
                                                    alt="">
                                            </div>
                                            <p class="text-white/85 font-medium text-sm line-clamp-2">
                                                {{ $event->event_name }}</p>
                                            <p class='text-green-400/80 text-xs font-mono'>by
                                                {{ $event->organizer?->business_name ?? 'Unknown' }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <p class="text-white/40 text-sm">No categories found.</p>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupCarousel(buttonSelector, direction) {
                document.querySelectorAll(buttonSelector).forEach(function(button) {
                    button.addEventListener('click', function() {
                        var targetId = button.getAttribute(direction === 'prev' ?
                            'data-carousel-prev' : 'data-carousel-next');
                        var track = document.getElementById(targetId);
                        if (!track) return;
                        var offset = track.clientWidth;
                        track.scrollBy({
                            left: direction === 'prev' ? -offset : offset,
                            behavior: 'smooth'
                        });
                    });
                });
            }

            setupCarousel('[data-carousel-prev]', 'prev');
            setupCarousel('[data-carousel-next]', 'next');

            var form = document.getElementById('discover-search-form');
            var qInput = document.getElementById('discover-search-q');
            var locationInput = document.getElementById('discover-search-location');
            var resultsContainer = document.getElementById('discover-results');
            var categoriesContainer = document.getElementById('discover-categories');
            var searchTimer = null;

            function buildSearchUrl() {
                var params = new URLSearchParams();
                if (qInput.value.trim() !== '') params.set('q', qInput.value.trim());
                if (locationInput.value.trim() !== '') params.set('location', locationInput.value.trim());
                var baseUrl = form.getAttribute('action');
                var queryString = params.toString();
                return {
                    url: queryString ? (baseUrl + '?' + queryString) : baseUrl,
                    hasQuery: queryString.length > 0
                };
            }

            function setSearchingState(hasQuery) {
                if (hasQuery) {
                    resultsContainer.classList.remove('hidden');
                    categoriesContainer.classList.add('hidden');
                } else {
                    resultsContainer.classList.add('hidden');
                    categoriesContainer.classList.remove('hidden');
                }
            }

            async function runSearch() {
                var searchInfo = buildSearchUrl();
                setSearchingState(searchInfo.hasQuery);
                if (!searchInfo.hasQuery) {
                    window.history.replaceState({}, '', searchInfo.url);
                    return;
                }

                try {
                    var response = await fetch(searchInfo.url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    var data = await response.json();
                    if (data && typeof data.html === 'string') {
                        resultsContainer.innerHTML = data.html;
                    }
                    window.history.replaceState({}, '', searchInfo.url);
                } catch (error) {
                    console.error('Search failed:', error);
                }
            }

            function debounceSearch() {
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = setTimeout(runSearch, 350);
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                runSearch();
            });

            qInput.addEventListener('input', debounceSearch);
            locationInput.addEventListener('input', debounceSearch);
        });
    </script>
</x-layout>
