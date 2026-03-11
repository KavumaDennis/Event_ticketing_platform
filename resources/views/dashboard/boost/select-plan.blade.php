<x-layout>
    <div class="max-w-4xl mx-auto p-10">
        <h1 class="text-3xl font-bold text-white mb-8 text-center">Boost Your Event: {{ $event->event_name }}</h1>
        <p class="text-white/60 text-center mb-10">Increase your event visibility and reach more buyers by boosting it to the top of search results and trends.</p>

        <form action="{{ route('boost.init', $event->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach($plans as $plan => $price)
                <label class="relative block bg-white/5 border border-white/10 p-6 rounded-2xl cursor-pointer hover:border-orange-400 transition group">
                    <input type="radio" name="plan" value="{{ $plan }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                    <div class="peer-checked:text-orange-400">
                        <h3 class="text-xl font-bold text-white group-hover:text-orange-400">{{ str_replace('_', ' ', ucfirst($plan)) }}</h3>
                        <p class="text-2xl font-mono text-white mt-4">UGX {{ number_format($price) }}</p>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="bg-white/5 border border-white/10 p-8 rounded-2xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-6">Payment Method</h3>
                
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 text-white cursor-pointer">
                        <input type="radio" name="payment_method" value="momo" checked class="form-radio text-orange-400 focus:ring-orange-400">
                        <span>Mobile Money (MTN / Airtel)</span>
                    </label>

                    <input type="text" name="phone" placeholder="25677xxxxxxx" class="w-full p-4 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-orange-400 mb-4" id="momoPhone">

                    <label class="flex items-center space-x-3 text-white cursor-pointer">
                        <input type="radio" name="payment_method" value="flutterwave" class="form-radio text-orange-400 focus:ring-orange-400">
                        <span>Flutterwave (Card / Bank)</span>
                    </label>
                </div>

                <button type="submit" class="mt-8 w-full py-4 bg-orange-400 hover:bg-orange-500 text-black font-bold text-lg rounded-xl transition shadow-lg">
                    Confirm & Pay
                </button>
            </div>
        </form>
    </div>
</x-layout>
