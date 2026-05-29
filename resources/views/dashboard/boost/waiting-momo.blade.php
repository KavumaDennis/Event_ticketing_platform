<x-layout>
    <div class="max-w-2xl mx-auto p-10 text-center">
        <h1 class="text-2xl font-bold text-white mb-3">Confirm Payment on Your Phone</h1>
        <p class="text-white/60 mb-6">
            We have sent a Mobile Money prompt. Please approve it to boost
            <span class="text-orange-400">{{ $event->event_name }}</span>.
        </p>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
            <div class="text-white/70 text-sm font-mono">Transaction Ref</div>
            <div class="text-orange-400 font-mono text-sm mt-2">{{ $txRef }}</div>

            <div class="mt-6 text-white/60 text-sm" id="boost-status">
                Waiting for payment confirmation...
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusEl = document.getElementById('boost-status');
            let tries = 0;

            async function poll() {
                tries += 1;
                try {
                    const res = await fetch("{{ route('boost.momo.status', $event->id) }}", {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    if (data.status === 'paid' && data.redirect) {
                        statusEl.textContent = 'Payment confirmed. Redirecting...';
                        window.location.href = data.redirect;
                        return;
                    }

                    if (data.status === 'failed') {
                        statusEl.textContent = 'Payment failed. Please try again.';
                        return;
                    }

                    statusEl.textContent = 'Waiting for payment confirmation...';
                } catch (e) {
                    statusEl.textContent = 'Still waiting for payment confirmation...';
                }

                if (tries < 60) {
                    setTimeout(poll, 4000);
                } else {
                    statusEl.textContent = 'Timed out. Please try again.';
                }
            }

            setTimeout(poll, 2000);
        });
    </script>
</x-layout>
