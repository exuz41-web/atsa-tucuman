<?php

namespace App\Http\Controllers;

use App\Models\Efemeride;
use App\Models\EscalaSalarial;
use App\Models\Filial;
use App\Models\Post;
use App\Models\SitePage;
use App\Models\Testimonio;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $featuredPosts = Post::query()
            ->where('destacado', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $filiales = Filial::query()
            ->where('active', true)
            ->orderBy('name')
            ->take(6)
            ->get();

        $efemerides = Efemeride::query()
            ->where('activo', true)
            ->where('mes', now()->month)
            ->orderBy('dia')
            ->get();

        $efemeridesTitle = 'Efemérides del sector salud - ' . $this->monthName(now()->month);

        if ($efemerides->isEmpty()) {
            $todayMonth = now()->month;
            $todayDay = now()->day;

            $efemerides = Efemeride::query()
                ->where('activo', true)
                ->get()
                ->sortBy(function (Efemeride $efemeride) use ($todayMonth, $todayDay) {
                    $hasPassed = $efemeride->mes < $todayMonth
                        || ($efemeride->mes === $todayMonth && $efemeride->dia < $todayDay);

                    return ($hasPassed ? 10000 : 0) + ($efemeride->mes * 100) + $efemeride->dia;
                })
                ->take(4)
                ->values();

            $efemeridesTitle = 'Próximas efemérides del sector salud';
        }

        $testimonios = Testimonio::query()
            ->with('afiliado')
            ->where('activo', true)
            ->where('estado', 'aprobado')
            ->orderBy('orden')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $escalaVigente = EscalaSalarial::query()
            ->where('activo', true)
            ->orderByDesc('vigente_desde')
            ->first();

        try {
            $sitePage = SitePage::forPageOrEmpty('home');
        } catch (\Exception $e) {
            $sitePage = null; // tabla aún no migrada
        }

        return view('home', compact('posts', 'featuredPosts', 'filiales', 'efemerides', 'efemeridesTitle', 'testimonios', 'escalaVigente', 'sitePage'));
    }

    public function sitemap()
    {
        $posts = Post::query()
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('posts'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function monthName(int $month): string
    {
        return [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ][$month] ?? '';
    }
}

