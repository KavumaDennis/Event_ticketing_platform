<script>
(function() {
    const apiUrl = @json(route('locations.autocomplete'));
    const debounce = (fn, ms) => {
        let t;
        return (...a) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...a), ms);
        };
    };

    function hideList(ul) {
        ul.classList.add('hidden');
        ul.innerHTML = '';
    }

    function ensureList(input) {
        const wrap = input.closest('.geo-ac-wrap');
        if (!wrap) return null;
        let ul = wrap.querySelector('.geo-ac-list');
        if (!ul) {
            ul = document.createElement('ul');
            ul.className = 'geo-ac-list absolute left-0 right-0 top-full z-[60] mt-1 max-h-48 overflow-y-auto rounded-xl border border-white/10 bg-black/95 text-xs text-white/80 shadow-xl hidden';
            wrap.appendChild(ul);
        }
        return ul;
    }

    const onInput = debounce(async (e) => {
        const input = e.target;
        if (!input.matches('[data-geocode-autocomplete]')) return;
        const q = input.value.trim();
        const ul = ensureList(input);
        if (!ul) return;
        if (q.length < 2) {
            hideList(ul);
            return;
        }
        try {
            const res = await fetch(apiUrl + '?q=' + encodeURIComponent(q), {
                headers: {
                    Accept: 'application/json'
                }
            });
            const data = await res.json();
            const suggestions = data.suggestions || [];
            ul.innerHTML = '';
            if (!suggestions.length) {
                hideList(ul);
                return;
            }
            suggestions.forEach((s) => {
                const li = document.createElement('li');
                li.className = 'cursor-pointer px-3 py-2 hover:bg-orange-400/20 border-b border-white/5 last:border-0';
                li.textContent = s.label;
                li.addEventListener('mousedown', (ev) => {
                    ev.preventDefault();
                    input.value = s.label;
                    hideList(ul);
                });
                ul.appendChild(li);
            });
            ul.classList.remove('hidden');
        } catch (err) {
            hideList(ul);
        }
    }, 280);

    document.addEventListener('input', onInput);

    document.addEventListener('click', (ev) => {
        document.querySelectorAll('.geo-ac-list').forEach((ul) => {
            const wrap = ul.closest('.geo-ac-wrap');
            if (wrap && !wrap.contains(ev.target)) hideList(ul);
        });
    });
})();
</script>
