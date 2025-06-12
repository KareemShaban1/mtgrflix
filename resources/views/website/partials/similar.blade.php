     <!--start similar products-->
     <section class="py-4 mt-5">
         <div class="container">
             <div class="separator pb-4 ">
                 <h6 class="mb-0 fw-bold separator-title"> {{ __('site.similar_products') }} </h6>
             </div>
             <div class="product-grid ">
                 <div class="similar-products owl-carousel owl-theme position-relative">


                     @php
                         $parentId = $product->category->parent_id;

                         $similarProducts = App\Models\Product::where('id', '!=', $product->id)
                             ->whereHas('category', function ($query) use ($parentId) {
                                 $query->where('parent_id', $parentId)->where('is_active', true);
                             })
                             ->where('is_active', true)
                             ->limit(10)
                             ->get();
                         $rating = $product->rating;
                     @endphp

                     @foreach ($similarProducts as $product)
                         <div class="item">
                             <div class="card">
                                 <div class="position-relative overflow-hidden">

                                     <a
                                         href="{{ route('product', ['productSlug' => $product->slug, 'productId' => $product->identifier]) }}">
                                         <img src="{{ asset('storage/' . $product->images) }}" class="img-fluid"
                                             alt="...">
                                     </a>
                                 </div>

                                 <div class="card-body px-0 text-center">
                                     <h6 class="mb-0 fw-bold product-short-title"> {{ $product->name }}
                                     </h6>
                                     <div class="product-price d-flex align-items-center justify-content-center gap-2 mt-2">
                                        @php
                                            $rate = session('rate', 1);
                                        @endphp
                                    
                                        @if ($product->promotional_price && $product->promotional_price < $product->price)
                                            <!-- Original price with strikethrough -->
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="h6 pt-1 text-secondary text-decoration-line-through small-price">
                                                    {{ number_format($product->price * $rate, 2) }}
                                                </div>
                                                @include('website.partials.product_currency')
                                                {{-- <img src="{{ asset('frontend/assets/image/ryal.png') }}" class="images-like-small" alt="SAR" style="width: 16px; height: 16px; margin-left: 4px;"> --}}
                                            </div>
                                    
                                            <!-- Promotional price -->
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="h6 fw-bold text-danger">
                                                    {{ number_format($product->promotional_price * $rate, 2) }}
                                                </div>
                                                @include('website.partials.product_currency')
                                                {{-- <img src="{{ asset('frontend/assets/image/ryal1.png') }}" class="images-like" alt="SAR" style="width: 18px; height: 18px; margin-left: 4px;"> --}}
                                            </div>
                                        @else
                                            <!-- Regular price -->
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="h6 fw-bold text-dark">
                                                    {{ number_format($product->price * $rate, 2) }}
                                                </div>
                                                @include('website.partials.product_currency')
                                            </div>
                                        @endif
                                    </div>
                                    


                                     <div class="rates cursor-pointer font-13">
                                         @for ($i = 1; $i <= 5; $i++)
                                             @if ($rating >= $i)
                                                 <i class="bx bxs-star text-warning"></i> {{-- full star --}}
                                             @elseif ($rating >= $i - 0.5)
                                                 <i class="bx bxs-star-half text-warning"></i> {{-- half star if needed --}}
                                             @else
                                                 <i class="bx bx-star text-warning"></i> {{-- empty star --}}
                                             @endif
                                         @endfor
                                     </div> {{-- Add to cart --}}
                                     <a href="{{ route('add_to_cart', $product->id) }}" class="text-white">
                                         <div class="d-flex justify-content-center">
                                             <button class="animated-btn">
                                                 {{ __('site.add_to_cart') }} <i
                                                     class='bx bx-cart-add fs-5 text-white me-2'></i>
                                             </button>
                                         </div>
                                     </a>
                                 </div>
                             </div>
                         </div>
                     @endforeach



                 </div>

             </div>
         </div>
         </div>
     </section>
     <!--end similar products-->
