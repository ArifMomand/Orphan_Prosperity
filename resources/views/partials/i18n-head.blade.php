@if ($isRtl)
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endif

<style>
    html[dir="rtl"] body {
        font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, sans-serif;
        line-height: 1.75;
    }

    /* Letter-spacing and uppercase break the joined letters of Dari/Pashto */
    html[dir="rtl"] *,
    html[dir="rtl"] *::before,
    html[dir="rtl"] *::after {
        letter-spacing: 0 !important;
        text-transform: none !important;
    }
</style>