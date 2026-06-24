<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->baseData();
        $data['products'] = $this->filteredProducts($request)->paginate(12)->withQueryString();
        $data['featuredProducts'] = Product::with(['category', 'images'])
            ->active()
            ->where('quantity', '>', 0)
            ->latest()
            ->limit(8)
            ->get();
        $data['filters'] = $request->only(['q', 'min_price', 'max_price', 'sort']);
        $data['category'] = null;

        return view('frontend.home', compact('data'));
    }

    public function listing(Request $request, string $slug): View
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        $data = $this->baseData();
        $data['category'] = $category;
        $data['products'] = $this->filteredProducts($request, $category)->paginate(12)->withQueryString();
        $data['filters'] = $request->only(['q', 'min_price', 'max_price', 'sort']);

        return view('frontend.listing', compact('data'));
    }

    public function details(string $slug): View
    {
        $product = Product::with([
            'category',
            'images',
            'attributes',
            'approvedReviews.customer',
        ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $this->baseData();
        $data['product'] = $product;
        $data['relatedProducts'] = Product::with(['category', 'images'])
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('frontend.details', compact('data'));
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(): array
    {
        return [
            'categories' => Category::withCount(['products' => fn (Builder $query) => $query->active()])
                ->active()
                ->orderBy('rank')
                ->get(),
        ];
    }

    private function filteredProducts(Request $request, ?Category $category = null): Builder
    {
        return Product::query()
            ->with(['category', 'images'])
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->active()
            ->when($category, fn (Builder $query) => $query->whereBelongsTo($category))
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $search = trim((string) $request->query('q'));

                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('min_price'), fn (Builder $query) => $query->where('price', '>=', (float) $request->query('min_price')))
            ->when($request->filled('max_price'), fn (Builder $query) => $query->where('price', '<=', (float) $request->query('max_price')))
            ->when($request->query('sort') === 'price_asc', fn (Builder $query) => $query->orderBy('price'))
            ->when($request->query('sort') === 'price_desc', fn (Builder $query) => $query->orderByDesc('price'))
            ->when($request->query('sort') === 'newest', fn (Builder $query) => $query->latest())
            ->when(! in_array($request->query('sort'), ['price_asc', 'price_desc', 'newest'], true), fn (Builder $query) => $query->latest());
    }
}
