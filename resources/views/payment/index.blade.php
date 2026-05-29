<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @stack('meta')
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .auth-slide {
            opacity: 0;
            transform: scale(1.06) translateY(8px);
            transition: opacity 1200ms cubic-bezier(0.22, 1, 0.36, 1), transform 2400ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .auth-slide.is-active {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .auth-dots {
            position: absolute;
            bottom: 18px;
            right: 18px;
            display: flex;
            gap: 8px;
            z-index: 20;
        }

        .auth-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.35);
            transition: width 350ms ease, background 350ms ease, box-shadow 350ms ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auth-dot.is-active {
            width: 24px;
            background: oklch(75% 0.183 55.934);
            /* box-shadow: 0 0 12px rgba(251, 146, 60, 0.35); */
        }

        .pw-toggle-wrap {
            position: relative;
        }

        .pw-toggle-btn {
            position: absolute;
            right: 0.3rem;
            top: 70%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: rgba(251, 146, 60, 0.8);
            border: 1px solid rgba(34, 197, 94, 0.15);
            transition: background 200ms ease, color 200ms ease, transform 200ms ease;
        }

        .pw-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            color: rgba(251, 146, 60, 1);
            transform: translateY(-50%) scale(1.05);
        }

        .pw-toggle-input {
            padding-right: 3rem !important;
        }
    </style>
</head>

<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply relative"
    x-data="{ mobileMenuOpen: false }">
    <div class="grid grid-cols-2 gap-1 p-0.5 h-screen overflow-y-auto">
        <!-- Left Column: Purchase Form -->
        <div
            class="bg-green-400/10 border border-green-400/10 col-span-1 h-screen overflow-y-scroll w-full p-6 shadow-lg">
            <h1 class="text-2xl text-white/70 mb-6">Complete Your Ticket Purchase</h1>

            <!-- Event Summary -->
            @php
                $convertedBaseTotal = round($baseTotal * $fxRate, 2);
                $convertedServiceFee = round($serviceFee * $fxRate, 2);
            @endphp
            <div class="space-y-3 text-white">
                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Event</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $event->event_name }}</span>
                </div>
                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Organizer</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $event->organizer->business_name }}</span>
                </div>
                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Ticket
                        Type</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ ucfirst($ticketType) }}</span>
                </div>
                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Quantity</span>
                    <span class="pl-3 text-white/60 font-mono font-light">{{ $quantity }}</span>
                </div>
                @if (!empty($promoError))
                    <div class="p-3 rounded-xl bg-red-400/10 border border-red-400/40 text-red-400 text-sm">
                        {{ $promoError }}
                    </div>
                @endif

                @if (!empty($discountAmount) && (float) $discountAmount > 0)
                    <div class="flex items-center">
                        <span
                            class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Before
                            discount</span>
                        <span class="pl-3 text-white/50 font-mono font-light">{{ $baseCurrency }}
                            {{ number_format($grossBaseTotal, 2) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span
                            class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-green-400/80 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Promo</span>
                        <span class="pl-3 text-green-400/80 font-mono font-light">− {{ $baseCurrency }}
                            {{ number_format($discountAmount, 2) }}</span>
                    </div>
                @endif

                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Subtotal</span>
                    <span id="subtotal-base" class="pl-3 text-white/60 font-mono font-light">{{ $baseCurrency }}
                        {{ number_format($baseTotal, 2) }}</span>
                    <span id="subtotal-converted"
                        class="pl-3 text-white/40 font-mono text-xs {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                        {{ $currency }} {{ number_format($convertedBaseTotal, 2) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Service
                        Fee</span>
                    <span id="fee-base" class="pl-3 text-white/60 font-mono font-light">{{ $baseCurrency }}
                        {{ number_format($serviceFee, 2) }}</span>
                    <span id="fee-converted"
                        class="pl-3 text-white/40 font-mono text-xs {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                        {{ $currency }} {{ number_format($convertedServiceFee, 2) }}
                    </span>
                </div>
                <p class="bg-green-400/10 border border-green-400/10 flex items-center w-fit p-0.5 px-2 rounded-2xl">
                    <span
                        class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">Total:</span>
                    <span id="total-amount" class="pl-3 text-white/60 font-mono font-light">{{ $currency }}
                        {{ number_format($total, 2) }}</span>
                </p>
                <div id="fx-row"
                    class="text-[10px] text-white/40 font-mono {{ $currency === $baseCurrency ? 'hidden' : '' }}">
                    Rate: 1 {{ $baseCurrency }} = <span id="fx-rate">{{ number_format($fxRate, 4) }}</span>
                    {{ $currency }} ({{ $fxProvider }})
                </div>
            </div>

            <!-- Apply promo (reloads totals server-side; checkout always recalculates on pay) -->
            <form method="get" action="{{ route('payment.page', $event) }}" class="mt-5 flex flex-col gap-2">
                <input type="hidden" name="ticket_type" value="{{ $ticketType }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">
                <input type="hidden" name="currency" value="{{ $currency }}">
                <label class="block text-white/60 font-medium ml-1 text-sm">Promo code (optional)</label>
                <div class="flex gap-2">
                    <input type="text" name="promo_code" value="{{ $promoInput }}" placeholder="Enter code"
                        class="flex-1 p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 uppercase">
                    <button type="submit"
                        class="px-4 py-3 rounded-xl bg-green-400/80 text-black text-xs font-bold uppercase font-mono hover:bg-green-400">
                        Apply
                    </button>
                </div>
            </form>

            <!-- Payment Form -->
            <form id="paymentForm" class="mt-6">
                @csrf
                <input type="hidden" id="event_id" value="{{ $event->id }}">
                <input type="hidden" id="ticket_type" value="{{ $ticketType }}">
                <input type="hidden" id="quantity" value="{{ $quantity }}">
                <input type="hidden" id="promo_code" value="{{ $promoInput }}">
                <input type="hidden" id="total" value="{{ $total }}">
                <input type="hidden" id="base_total" value="{{ $totalBase }}">
                <input type="hidden" id="currency" value="{{ $currency }}">

                <label class="block text-white/60 font-medium ml-1 text-sm mb-1">Full Name</label>
                <input type="text" id="name"
                    value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" placeholder="Full Name"
                    class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 mb-4">

                <label class="block text-white/60 font-medium ml-1 text-sm mb-1">Email Address</label>
                <input type="email" id="email" value="{{ auth()->user()->email }}"
                    placeholder="Email Address"
                    class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 mb-4">

                <div class="grid grid-cols-3 gap-3">
                    <div class="">
                        <label class="block text-white/60 font-medium ml-1 text-sm mb-1">Phone Number (MoMo)</label>
                        <input type="text" id="phone" value="{{ auth()->user()->phone ?? '' }}"
                            placeholder="25677xxxxxxx"
                            class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 mb-4">
                    </div>

                    <div class="">
                        <label class="block text-white/60 font-medium ml-1 text-sm mb-1">Payment Method</label>
                        <select id="payment_method"
                            class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            <option class="text-black/70 font-medium" value="momo" selected>Choose method
                            </option>
                            <option class="text-black/70 font-medium" value="flutterwave">Flutterwave (Card, MoMo,
                                Bank)
                            </option>
                        </select>
                    </div>

                    <div class="">
                        <label class="block text-white/60 font-medium ml-1 text-sm mb-1">Paying Currency</label>
                        <select id="currency_select"
                            class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @foreach ($supportedCurrencies as $cur)
                                <option class="text-black/70 font-medium" value="{{ $cur }}"
                                    {{ $cur === $currency ? 'selected' : '' }}>
                                    {{ $cur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="button" id="payNowBtn"
                    class="mt-6 w-full py-3 bg-orange-400 font-mono hover:bg-orange-600 transition rounded-3xl font-medium text-black/90 shadow-lg">
                    Pay Now
                </button>
            </form>
        </div>

        <!-- Right Column: Slider -->
        <div class="col-span-1 w-full h-full relative p-0.5 border border-green-400/10">
            <div class="w-full h-full relative overflow-hidden border border-green-400/10">
                <div class="absolute inset-0" data-auth-slider>
                    <img src="{{ asset('img1.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 1">
                    <img src="{{ asset('img2.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 2">
                    <img src="{{ asset('img4.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 3">
                    <img src="{{ asset('img5.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 4">
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/80"></div>
                <div class="auth-dots" aria-hidden="true"></div>

                <div class="relative z-10 w-full h-full pt-3">
                    <div class="flex justify-between items-center px-3">
                        <div class="flex overflow-hidden gap-3 text-sm text-black font-medium">
                            <a href="{{ route('home') }}"
                                class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Home</a>
                            <a href="{{ route('events') }}"
                                class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Events</a>
                            <a href="{{ route('contact') }}"
                                class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Contact</a>
                            <a href="{{ route('organizers') }}"
                                class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Organizer</a>
                            <a href="{{ route('trends') }}"
                                class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Trends</a>
                        </div>

                        <div class="flex  gap-3 bg-orange-400 p-1 rounded-3xl">
                            <span
                                class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-big-left-dash-icon lucide-arrow-big-left-dash">
                                    <path
                                        d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                                    <path d="M20 9v6" />
                                </svg>
                            </span>
                            <span
                                class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-big-right-dash-icon lucide-arrow-big-right-dash">
                                    <path
                                        d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                                    <path d="M4 9v6" />
                                </svg>
                            </span>
                        </div>

                    </div>
                    <div class="absolute bottom-3 left-3 flex flex-col gap-3 z-10">

                        <div class="text-white font-bold tracking-tighter text-2xl uppercase mb-5">
                            Discover Events.
                            Anywhere, Anytime.
                        </div>

                        <div class="flex items-center p-1 w-fit gap-1 rounded-3xl">
                            <h1 class="uppercase text-lg font-semibold text-orange-400">AKAVAAKO</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
            const promoCodeEl = document.getElementById('promo_code');
            const promoCode = promoCodeEl ? promoCodeEl.value.trim() : '';
            const currency = document.getElementById('currency').value;

            if (paymentMethod === 'momo' && !phone) {
                alert('Please enter your phone number.');
                return;
            }
            if (paymentMethod === 'flutterwave' && (!email || !name)) {
                alert('Please enter your name and email.');
                return;
            }

            const url = paymentMethod === 'momo' ? '/momo/pay' : '/flutterwave/pay';
            const payload = {
                event_id: eventId,
                ticket_type: ticketType,
                quantity,
                total,
                promo_code: promoCode || null,
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

                if (!res.ok) {
                    alert(data.message || 'Something went wrong. Please try again.');
                    console.error(data);
                    return;
                }

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


         document.querySelectorAll('[data-auth-slider]').forEach((slider) => {
            const slides = Array.from(slider.querySelectorAll('.auth-slide'));
            if (!slides.length) return;

            const dotsWrap = slider.parentElement.querySelector('.auth-dots');
            let dots = [];
            if (dotsWrap) {
                dotsWrap.innerHTML = '';
                dots = slides.map((_, i) => {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'auth-dot';
                    dot.addEventListener('click', () => {
                        index = i;
                        show(index, true);
                    });
                    dotsWrap.appendChild(dot);
                    return dot;
                });
            }

            let index = 0;
            let timer = null;

            const show = (i, manual = false) => {
                slides.forEach((img, idx) => img.classList.toggle('is-active', idx === i));
                dots.forEach((dot, idx) => dot.classList.toggle('is-active', idx === i));
                if (manual) {
                    clearInterval(timer);
                    timer = setInterval(next, 4800);
                }
            };

            const next = () => {
                index = (index + 1) % slides.length;
                show(index);
            };

            show(index);
            timer = setInterval(next, 4800);
        });
    </script>
</body>


</html>
