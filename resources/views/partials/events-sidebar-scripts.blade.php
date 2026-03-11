<script>
    // Alpine.js Trend Slider Component
    function trendSlider(trends) {
        return {
            trends: trends || [],
            currentIndex: 0,
            get currentTrend() {
                if (!this.trends.length) {
                    return {
                        id: 0,
                        title: 'No trends available',
                        body: '',
                        user_name: '',
                        image: null
                    };
                }
                return this.trends[this.currentIndex];
            },
            next() {
                if (!this.trends.length) return;
                this.currentIndex = (this.currentIndex + 1) % this.trends.length;
            },
            prev() {
                if (!this.trends.length) return;
                this.currentIndex = (this.currentIndex - 1 + this.trends.length) % this.trends.length;
            }
        };
    }

    // Organizer Follow Functionality
    $(document).on('click', '.organizer-follow-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const organizerId = btn.data('organizer');

        fetch(`/organizer/${organizerId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            if (res.status === 401) {
                alert('Please log in to follow organizers.');
                return null;
            }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.status === 'followed') {
                btn.text('Following').removeClass('bg-orange-400/80').addClass('bg-green-400/80');
            } else {
                btn.text('Follow').removeClass('bg-green-400/80').addClass('bg-orange-400/80');
            }
        })
        .catch(err => console.error('Error:', err));
    });
</script>
