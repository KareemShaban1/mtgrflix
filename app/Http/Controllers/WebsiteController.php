<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    public function home()
    {

        $testimonials = \App\Models\Testimonial::where('is_active', 1)->take(50)->get();
        $main_categories = \App\Models\Category::with([
            'parent',
            'children.products' => function ($query) {
                $query->where('is_active', 1);
            }
        ])
        ->where('is_active', 1)
        ->whereNull('parent_id')
        ->get();

        return view('website.home', compact('main_categories', 'testimonials'));
    }

    public function categories()
    {
        return view('website.categories');
    }

    public function products()
    {
        return view('website.products');
    }







    public function page($slug)
    {

        $page = \App\Models\Page::where('slug', $slug)->first();
        return view('website.pages.site_page', compact('page'));
    }


    public function index(Request $request)
    {
        $query = $request->get('query', '');
        $results = Product::where('name->ar', 'LIKE', "%{$query}%")
            ->orWhere('name->en', 'LIKE', "%{$query}%")
            ->orWhere('sub_title', 'LIKE', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'slug', 'identifier']) // Optional: select only needed columns
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'identifier' => $item->identifier,
                    'url' => route('product', ['productSlug' => $item->slug, 'productId' => $item->identifier]),
                ];
            });

        return response()->json($results);
    }

    public function searchProduct(Request $request){
        $query = $request->get('query', '');
        $results = Product::where('name->ar', 'LIKE', "%{$query}%")
            ->orWhere('name->en', 'LIKE', "%{$query}%")
            ->orWhere('sub_title', 'LIKE', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'slug', 'identifier']) // Optional: select only needed columns
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'identifier' => $item->identifier,
                    'url' => route('product', ['productSlug' => $item->slug, 'productId' => $item->identifier]),
                ];
            });

        return response()->json($results);
    }

}
