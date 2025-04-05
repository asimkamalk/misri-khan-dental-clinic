<?php
// app/Http/Controllers/HomeController.php
// This replaces the original HomeController that was created in Phase 1

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Branch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('display_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $branches = Branch::where('is_active', true)->get();

        return view('home', compact('services', 'testimonials', 'branches'));
    }

    /**
     * Show all services.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function services()
    {
        $services = Service::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('services', compact('services'));
    }

    /**
     * Show service details.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function serviceDetail($id)
    {
        $service = Service::findOrFail($id);
        $otherServices = Service::where('id', '!=', $id)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->take(3)
            ->get();

        return view('service-detail', compact('service', 'otherServices'));
    }

    /**
     * Show about page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about()
    {
        return view('about');
    }
}