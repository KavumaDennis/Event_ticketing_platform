@props(['organizer'])

@unless($organizer)
@else
    @php
        $ga = trim((string) ($organizer->google_analytics_id ?? ''));
        $pixel = trim((string) ($organizer->facebook_pixel_id ?? ''));
    @endphp

    @if($ga !== '' && preg_match('/^(G-[A-Z0-9]+|UA-\\d+-\\d+)$/i', $ga))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($ga) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json($ga));
        </script>
    @endif

    @if($pixel !== '' && preg_match('/^\\d+$/', $pixel))
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', @json($pixel));
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ e($pixel) }}&ev=PageView&noscript=1" alt=""
        /></noscript>
    @endif
@endunless
