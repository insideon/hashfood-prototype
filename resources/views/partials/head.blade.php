<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="naver-site-verification" content="c24400f78284d99705ebdcdbf3387d32a4a5020b" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} RSS Feed" href="{{ route('rss') }}" />

<!-- Resource hints for performance -->
<link rel="dns-prefetch" href="//fonts.bunny.net">
<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"></noscript>

<style>
/* Critical CSS for initial render */
body { margin: 0; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
.min-h-screen { min-height: 100vh; }
.bg-white { background-color: #ffffff; }
.dark\:bg-zinc-800:is(.dark, .dark *) { background-color: #27272a; }
.border-e { border-right-width: 1px; }
.border-zinc-200 { border-color: #e4e4e7; }
.bg-zinc-50 { background-color: #fafafa; }
.dark\:border-zinc-700:is(.dark, .dark *) { border-color: #3f3f46; }
.dark\:bg-zinc-900:is(.dark, .dark *) { background-color: #18181b; }
.flex { display: flex; }
.items-center { align-items: center; }
.space-x-2 > :not([hidden]) ~ :not([hidden]) { margin-left: 0.5rem; }
.hidden { display: none; }
@media (min-width: 1024px) { .lg\:hidden { display: none; } }
</style>

@vite(['resources/css/app.css'])
@fluxAppearance
