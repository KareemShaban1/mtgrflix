@foreach ($notifications as $notification)
    @php
        $order = isset($notification->data['order_id'])
            ? App\Models\Order::find($notification->data['order_id'])
            : null;
    @endphp
    <div class="card-body border mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="d-flex order">
                <div class="border alerts ms-2">
                    <i class='bx bx-bell fa-2x'></i>
                </div>
                <div>
                    @if (isset($notification->data['order_id']))
                        <a href="{{ route('order.details', isset($order) ? $order->number : '') }}">
                            <p class="text-dark fw-bold">
                                {{ __('site.orders') }} #{{ isset($order) ? $order->number : '' }}
                            </p>
                        </a>
                    @endif

                    <p>{{ $notification->data['message'] ?? '' }}</p>

                    <div class="time d-block d-md-none mt-2">
                        <i class='bx bx-time'></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="time d-none d-md-block">
                <i class='bx bx-time'></i>
                {{ $notification->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
@endforeach
