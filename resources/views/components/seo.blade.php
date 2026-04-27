@php
    use Illuminate\Support\Str;
    $seoTitle    = $title    ?: 'ATSA Tucumán';
    $seoDesc     = Str::limit(strip_tags($description ?: 'Representación gremial de los trabajadores de la sanidad en Tucumán. Afiliación, beneficios, formación y asesoramiento gremial.'), 160);
    $seoImage    = $image    ?: asset('images/logo-atsa.png');
    $seoUrl      = $url      ?: url()->current();
    $seoType     = $type     ?: 'website';
    $seoKeywords = $keywords ?: 'ATSA, sindicato, sanidad, Tucumán, afiliados, gremial, trabajadores salud';
@endphp
<meta name="description" content="{{ $seoDesc }}" />
<meta name="keywords" content="{{ $seoKeywords }}" />
<meta name="robots" content="index, follow" />
<meta name="author" content="ATSA Tucumán" />
<meta property="og:type" content="{{ $seoType }}" />
<meta property="og:url" content="{{ $seoUrl }}" />
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:description" content="{{ $seoDesc }}" />
<meta property="og:image" content="{{ $seoImage }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:site_name" content="ATSA Tucumán" />
<meta property="og:locale" content="es_AR" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $seoTitle }}" />
<meta name="twitter:description" content="{{ $seoDesc }}" />
<meta name="twitter:image" content="{{ $seoImage }}" />
<link rel="canonical" href="{{ $seoUrl }}" />
