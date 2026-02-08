<x-layout>
    <div class="p-5 ">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-3 mb-6">
            <h1 class='text-3xl font-medium text-white/80'>{{ ucfirst($category) }} Events</h1>
            <div class="flex gap-5 items-center">
                <form id="search-form" method="GET" action="{{ route('categories.show', $category) }}" class="relative">
                    <div class="h-full relative flex items-center ">
                        <input type="search" name="search" value="{{ request('search') }}" class="p-2  bg-orange-400/70 border border-green-400/15 w-80 rounded-3xl pr-10 outline-0 text-black/80 font-medium text-sm placeholder:text-black/80" placeholder="Search in {{ $category }}..." />
                        <button type="submit" class="absolute right-1 size-8 flex items-center justify-center bg-black/95 text-orange-400/80 p-2 rounded-[50%] border border-green-400/15 hover:bg-black transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" /></svg>
                        </button>
                    </div>
                </form>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer hover:bg-orange-400/80 transition">
                        <div class="flex justify-center items-center size-8 bg-black/95 text-orange-400/80 p-2 rounded-2xl border border-green-400/15">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-filter-icon lucide-list-filter">
                                <path d="M2 5h20" />
                                <path d="M6 12h12" />
                                <path d="M9 19h6" /></svg>
                        </div>
                        <span class='text-xs font-medium mr-1 text-black/90 '>Categories</span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" @click.away="open = false" class="absolute top-full right-0 mt-2 w-64 bg-[url(/public/bg-img.png)] bg-blend-darken bg-black/90 border border-purple-400/20 rounded-3xl shadow-lg p-4 z-50 backdrop-blur-[1px]">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-orange-400/80 font-semibold text-sm mb-1 border-b border-purple-400/10 pb-2">All Categories</h3>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('events') }}" class="px-3 py-1 bg-black/60 text-white/70 hover:bg-orange-400/50 border border-green-400/15 rounded-3xl text-sm font-medium transition">
                                    All Events
                                </a>
                                @foreach($categories as $cat)
                                <a href="{{ route('categories.show', $cat) }}" class="px-4 py-1 {{ $category === $cat ? 'bg-orange-400/70 text-black/90' : 'bg-black/60 text-white/70 hover:bg-orange-400/50' }} border border-green-400/15 rounded-3xl text-sm font-medium transition">
                                    {{ ucfirst($cat) }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="events-container">
            @include('partials.events-grid', ['events' => $events])
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            const container = $('#events-container');
            const searchInput = $('input[name="search"]');
            const searchForm = $('#search-form');

            function updateEvents(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        container.html(data);
                        window.history.pushState({}, '', url);
                    }
                });
            }

            searchForm.on('submit', function(e) {
                e.preventDefault();
                const url = new URL(this.action);
                url.searchParams.set('search', searchInput.val());
                updateEvents(url.toString());
            });

            $(document).on('click', '#pagination-links a', function(e) {
                e.preventDefault();
                updateEvents($(this).attr('href'));
            });
        });
    </script>
    @endpush
</x-layout>
