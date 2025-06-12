@foreach ($orders as $order)
<tr>
    <td class="pt-4">
        <a href="{{ route('order.details', $order->number) }}">
            <p class="text-dark">#{{ $order->number }}</p>
        </a>
    </td>
    <td class="pt-4">{{ $order->grand_total }}
        @include('website.partials.currency')</td>
    <td class="pt-4">{{ $order->created_at }}</td>
    <td>
        <div class="d-flex">
            <div
                class="d-flex border rounded-pill my-1 ps-3 pe-3 border-success m-2">
                <i
                    class='bx bx-check fs-5 custom-check-icon text-success my-2'></i>
                <div class="my-2 text-success">
                    {{ __('site.orders.done') }}</div>
            </div>

            <div
                class="d-flex border rounded-pill my-1 ps-3 pe-3 border-warning">
                <a href="#" data-bs-toggle="modal"
                    data-bs-target="#modal-rate-product"
                    data-id="{{ $order->id }}">
                    <p class="my-2 text-warning">
                        {{ __('site.orders.rate') }}</p>
                </a>
            </div>
        </div>
    </td>
</tr>
@endforeach
