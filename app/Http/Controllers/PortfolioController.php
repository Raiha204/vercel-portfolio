<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use Illuminate\Contracts\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'projects' => Portfolio::projects(),
            'certificates' => Portfolio::certificates(),
            'experience' => Portfolio::experience(),
            'skills' => Portfolio::skills(),
        ]);
    }

    public function show(string $slug): View
    {
        $all = Portfolio::projects();
        $index = $all->search(fn ($p) => $p->slug === $slug);

        abort_if($index === false, 404);

        $count = $all->count();

        return view('projects.show', [
            'project' => $all[$index],
            'position' => $index + 1,
            'previous' => $count > 1 ? $all[($index - 1 + $count) % $count] : null,
            'next' => $count > 1 ? $all[($index + 1) % $count] : null,
        ]);
    }
}
