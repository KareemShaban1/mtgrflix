<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryController extends Controller
{
    //

    public function show($slug = null, $identifier = null)
    {

        // return $identifier.$slug;
        $category = \App\Models\Category::where('identifier', $identifier)->firstOrFail();
        return view('website.pages.category_show', compact('category'));
    }

    public function showProduct($slug = null, $identifier = null, Request $request)
    {
        $product = \App\Models\Product::where('identifier', $identifier)->firstOrFail();
        $reviews = $product->reviews()->where('approved', true)->orderByDesc('created_at')->paginate(5);

        // Initialize default values
        $baseReviews = 2000;
        $basePurchases = 5000;

        // Check product ID and set appropriate base values
        if ($product->id == 12) {
            // Netflix 1 Month
            $baseReviews = 20000;
            $basePurchases = 50000;
        } elseif ($product->id == 11) {
            // Netflix 2 Month
            $baseReviews = 10000;
            $basePurchases = 20000;
        }

        // Calculate totals
        $approvedReviewsCount = $product->reviews()->where('approved', true)->count();
        $reviewsCount = $baseReviews + $approvedReviewsCount;
        $averageRating = $product->reviews()->where('approved', true)->avg('rating');
        $actualPurchases = $product->orderItems()->count();
        $totalPurchases = $basePurchases + $actualPurchases;

        if ($request->ajax()) {
            $view = view('website.partials.all_reviews', compact('reviews'))->render();
            return response()->json(['html' => $view]);
        }

        return view('website.pages.product_show', compact(
            'product',
            'reviews',
            'reviewsCount',
            'averageRating',
            'totalPurchases'
        ));
    }


    public function getByTag($tag, $id = null)
    {
        $category = Category::where('name->ar', 'LIKE', "%{$tag}%")
            ->orWhere('name->en', 'LIKE', "%{$tag}%")
            ->first();


        // If tag did not match any category
        if (!$category && $id) {
            $product = \App\Models\Product::find($id);

            if ($product && $product->category) {
                $category = $product->category;
            }
        }

        if (!$category) {
            return redirect()->back();
        }

        return redirect()->route('category', [
            'categorySlug' => $category->slug,
            'categoryId' => $category->identifier
        ]);
    }

    public function flixMonth()
    {
        $product = (object)[
            'slug' => 'حساب-نتفلكس-رسمي-شهر',
            'identifier' => "p1886408876"
        ];

        $url = LaravelLocalization::localizeUrl(
            route('product', [
                'productSlug' => $product->slug,
                'productId' => $product->identifier
            ])
        );

        return redirect($url);
    }

    public function flix2Month()
    {
        $product = (object)[
            'slug' => 'حساب-نتفلكس-رسمي-شهرين',
            'identifier' => "p312022680"
        ];

        $url = LaravelLocalization::localizeUrl(
            route('product', [
                'productSlug' => $product->slug,
                'productId' => $product->identifier
            ])
        );

        return redirect($url);
    }
}
