<x-layout>
    <div class="grid grid-cols-2 gap-5 p-10">
        <!-- Left Column: Purchase Form -->
        <div class="bg-green-400/10 border border-green-400/10 col-span-1 w-full p-6 shadow-lg">
            <h1 class="text-2xl text-white/70 mb-6">Complete Your Ticket Purchase</h1>

            <!-- Event Summary -->
            @php
                $convertedBaseTotal = round($baseTotal * $fxRate, 2);
                $convertedServiceFee = round($serviceFee * $fxRate, 2);
            @endphp
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
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Subtotal</span>
                    <span id="subtotal-base" class="pl-3 text-white/60 font-mono font-light">{{ $baseCurrency }} {{ number_format($baseTotal, 2) }}</span>
                    <span id="subtotal-converted" class="pl-3 text-white/40 font-mono text-xs {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                        {{ $currency }} {{ number_format($convertedBaseTotal, 2) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Service Fee</span>
                    <span id="fee-base" class="pl-3 text-white/60 font-mono font-light">{{ $baseCurrency }} {{ number_format($serviceFee, 2) }}</span>
                    <span id="fee-converted" class="pl-3 text-white/40 font-mono text-xs {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                        {{ $currency }} {{ number_format($convertedServiceFee, 2) }}
                    </span>
                </div>
                <p class="bg-green-400/10 border border-green-400/10 flex items-center w-fit p-0.5 px-2 rounded-2xl">
                    <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Total:</span>
                    <span id="total-amount" class="pl-3 text-white/60 font-mono font-light">{{ $currency }} {{ number_format($total, 2) }}</span>
                </p>
                <div id="fx-row" class="text-[10px] text-white/40 font-mono {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                    Rate: 1 {{ $baseCurrency }} = <span id="fx-rate">{{ number_format($fxRate, 4) }}</span> {{ $currency }} ({{ $fxProvider }})
                </div>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" class="mt-6">
                @csrf
                <input type="hidden" id="event_id" value="{{ $event->id }}">
                <input type="hidden" id="ticket_type" value="{{ $ticketType }}">
                <input type="hidden" id="quantity" value="{{ $quantity }}">
                <input type="hidden" id="total" value="{{ $total }}">
                <input type="hidden" id="base_total" value="{{ $totalBase }}">
                <input type="hidden" id="currency" value="{{ $currency }}">

                <label class="block text-gray-300 font-semibold mb-1">Full Name</label>
                <input type="text" id="name" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" placeholder="Full Name" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30 placeholder-white/40 backdrop-blur-4xl mb-4">

                <label class="block text-gray-300 font-semibold mb-1">Email Address</label>
                <input type="email" id="email" value="{{ auth()->user()->email }}" placeholder="Email Address" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30 placeholder-white/40 backdrop-blur-4xl mb-4">

                <label class="block text-gray-300 font-semibold mb-1">Phone Number (MoMo)</label>
                <input type="text" id="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="25677xxxxxxx" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30 placeholder-white/40 backdrop-blur-4xl mb-4">

                <label class="block text-gray-300 font-semibold mb-1">Payment Method</label>
                <select id="payment_method" class="p-3 w-full text-orange-400/70 font-mono rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30">
                    <option value="momo" selected>Mobile Money (MTN / Airtel)</option>
                    <option value="flutterwave">Flutterwave (Card, MoMo, Bank)</option>
                </select>

                <label class="block text-gray-300 font-semibold mb-1 mt-4">Paying Currency</label>
                <select id="currency_select" class="p-3 w-full text-orange-400/70 font-mono rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/30">
                    @foreach($supportedCurrencies as $cur)
                        <option value="{{ $cur }}" {{ $cur === $currency ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
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
    const paymentMethod = document.getElementById('payment_method').value;
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const name = document.getElementById('name').value.trim();
    const eventId = document.getElementById('event_id').value;
    const ticketType = document.getElementById('ticket_type').value;
    const quantity = document.getElementById('quantity').value;
    const total = document.getElementById('total').value;
    const currency = document.getElementById('currency').value;

    if (paymentMethod === 'momo' && !phone) { alert('Please enter your phone number.'); return; }
    if (paymentMethod === 'flutterwave' && (!email || !name)) { alert('Please enter your name and email.'); return; }

    const url = paymentMethod === 'momo' ? '/momo/pay' : '/flutterwave/pay';
    const payload = { 
        event_id: eventId, 
        ticket_type: ticketType, 
        quantity, 
        total,
        currency,
        phone,
        email,
        name
    };

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (paymentMethod === 'flutterwave' && data.status === 'success') {
            window.location.href = data.link;
            return;
        }

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
                        window.location.href = result.redirect;
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

const baseCurrency = "{{ $baseCurrency }}";
const currencySelect = document.getElementById('currency_select');
const paymentMethodSelect = document.getElementById('payment_method');
const totalBaseInput = document.getElementById('base_total');
const totalInput = document.getElementById('total');
const currencyInput = document.getElementById('currency');

async function refreshFx() {
    const selected = currencySelect.value;
    const baseTotal = parseFloat(totalBaseInput.value);

    if (selected === baseCurrency) {
        document.getElementById('fx-row').classList.add('hidden');
        document.getElementById('subtotal-converted').classList.add('hidden');
        document.getElementById('fee-converted').classList.add('hidden');
        document.getElementById('total-amount').textContent = `${baseCurrency} ${baseTotal.toFixed(2)}`;
        totalInput.value = baseTotal.toFixed(2);
        currencyInput.value = baseCurrency;
        return;
    }

    const res = await fetch(`/payment/fx-quote?amount=${baseTotal}&from=${baseCurrency}&to=${selected}`);
    const data = await res.json();

    document.getElementById('fx-row').classList.remove('hidden');
    document.getElementById('fx-rate').textContent = Number(data.rate).toFixed(4);
    document.getElementById('subtotal-converted').classList.remove('hidden');
    document.getElementById('fee-converted').classList.remove('hidden');

    const subtotalBase = {{ $baseTotal }};
    const feeBase = {{ $serviceFee }};
    const subtotalConverted = (subtotalBase * data.rate).toFixed(2);
    const feeConverted = (feeBase * data.rate).toFixed(2);

    document.getElementById('subtotal-converted').textContent = `${selected} ${subtotalConverted}`;
    document.getElementById('fee-converted').textContent = `${selected} ${feeConverted}`;
    document.getElementById('total-amount').textContent = `${selected} ${Number(data.converted).toFixed(2)}`;

    totalInput.value = Number(data.converted).toFixed(2);
    currencyInput.value = selected;
}

currencySelect.addEventListener('change', async () => {
    if (paymentMethodSelect.value === 'momo' && currencySelect.value !== baseCurrency) {
        currencySelect.value = baseCurrency;
    }
    await refreshFx();
});

paymentMethodSelect.addEventListener('change', async () => {
    if (paymentMethodSelect.value === 'momo' && currencySelect.value !== baseCurrency) {
        currencySelect.value = baseCurrency;
        await refreshFx();
    }
});
</script>
