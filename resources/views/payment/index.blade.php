<x-layout>
    <div class="grid grid-cols-2 gap-5 p-10">
        <!-- Left Column: Purchase Form -->
        <div class="bg-green-400/10 border border-green-400/10 col-span-1 w-full p-6 shadow-lg">
            <h1 class="text-2xl text-white/70 mb-6">Complete Your Ticket Purchase</h1>

            <!-- Event Summary -->
            <div class="space-y-3 text-white">
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Event</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $event->event_name }}</span>
                </div>
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Organizer</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $event->organizer->business_name }}</span>
                </div>
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Ticket Type</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ ucfirst($ticketType) }}</span>
                </div>
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Quantity</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $quantity }}</span>
                </div>
                <p class="bg-green-400/10 border border-green-400/10 flex items-center w-fit p-0.5 px-2 rounded-2xl">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Total:</span>
                    <span class="pl-3 text-white/60 font-mono font-light">UGX {{ number_format($total) }}</span>
                </p>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" class="mt-6">
                @csrf
                <input type="hidden" id="event_id" value="{{ $event->id }}">
                <input type="hidden" id="ticket_type" value="{{ $ticketType }}">
                <input type="hidden" id="quantity" value="{{ $quantity }}">
                <input type="hidden" id="total" value="{{ $total }}">

                <label class="block text-gray-300 font-semibold mb-1">Phone Number</label>
                <input type="text" id="phone" placeholder="25677xxxxxxx" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30 placeholder-white/40 backdrop-blur-4xl mb-4">

                <label class="block text-gray-300 font-semibold mb-1">Payment Method</label>
                <select id="payment_method" class="p-3 w-full text-orange-400/70 font-mono rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30" disabled>
                    <option value="momo" selected>Mobile Money (MTN / Airtel)</option>
                </select>

                <button type="button" id="payNowBtn" class="mt-6 w-full py-3 bg-orange-400/80 font-mono hover:bg-orange-600 transition rounded-3xl font-medium text-black/90 shadow-lg">
                    Pay Now
                </button>
            </form>
        </div>

        <!-- Right Column: Slider -->
        <div class="col-span-1 w-full relative overflow-hidden h-full">
            <div id="sliderWrapper" class="absolute inset-0">
                <img src="{{ asset('img1.jpg') }}" class="slider-img opacity-0 transition-opacity duration-[1500ms] ease-in-out absolute inset-0 w-full h-full object-cover">
                <img src="{{ asset('img2.jpg') }}" class="slider-img opacity-0 transition-opacity duration-[1500ms] ease-in-out absolute inset-0 w-full h-full object-cover">
                <img src="{{ asset('img3.jpg') }}" class="slider-img opacity-0 transition-opacity duration-[1500ms] ease-in-out absolute inset-0 w-full h-full object-cover">
            </div>
        </div>
    </div>
</x-layout>

<script>
document.getElementById('payNowBtn').addEventListener('click', async () => {
    const phone = document.getElementById('phone').value.trim();
    const eventId = document.getElementById('event_id').value;
    const ticketType = document.getElementById('ticket_type').value;
    const quantity = document.getElementById('quantity').value;
    const total = document.getElementById('total').value;

    if (!phone) { alert('Please enter your phone number.'); return; }

    try {
        const res = await fetch('/momo/pay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ event_id: eventId, ticket_type: ticketType, quantity, total, phone })
        });

        const data = await res.json();

        if (data.referenceId && data.referenceId.startsWith('error:')) {
            alert('Failed to request payment: ' + data.referenceId);
            console.error('MTN Error:', data.referenceId);
            return;
        }

        if (data.status === 'success') {
            // Show overlay
            const overlay = document.createElement('div');
            overlay.id = 'paymentOverlay';
            overlay.style = `
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.8);
                color: white;
                font-size: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            `;
            overlay.textContent = 'Waiting for payment confirmation...';
            document.body.appendChild(overlay);

            // Polling function
            const checkPayment = async () => {
                try {
                    const res = await fetch(`/momo/check/${data.purchase_id}`);
                    const result = await res.json();

                    if (result.status === 'paid') {
                        document.getElementById('paymentOverlay')?.remove();
                        window.location.href = `/ticket/${result.ticket_code}`;
                    } else if (result.status === 'failed') {
                        alert('Payment failed. Please try again.');
                        document.getElementById('paymentOverlay')?.remove();
                    } else {
                        setTimeout(checkPayment, 2000); // Pending, retry in 2s
                    }
                } catch (err) {
                    console.error('Payment check failed:', err);
                    setTimeout(checkPayment, 5000); // Retry in 5s
                }
            };

            checkPayment();
        } else {
            alert('Unexpected response: ' + JSON.stringify(data));
            console.log('Response:', data);
        }
    } catch (err) {
        alert('Request failed: ' + err.message);
        console.error(err);
    }
});

// AUTO IMAGE SLIDER
const slides = document.querySelectorAll(".slider-img");
let index = 0;
function showSlide(i) {
    slides.forEach((img, idx) => {
        if (idx === i) { img.classList.remove("opacity-0"); img.classList.add("opacity-80"); }
        else { img.classList.remove("opacity-80"); img.classList.add("opacity-0"); }
    });
}
showSlide(index);
setInterval(() => { index = (index + 1) % slides.length; showSlide(index); }, 4000);
</script>
