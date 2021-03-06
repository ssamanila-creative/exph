<?php

namespace Experience\Controllers;

use Anam\Phpcart\Cart as CartModel;
use Anam\Phpcart\Facades\Cart;
use Experience\Mail\OrderReceived;
use Experience\Models\Availability;
use Experience\Models\Experience;
use Experience\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Shop\Controllers\ShopController;

class BillingController extends ShopController
{
    /**
     * Show the detail form
     * @param  \Illuminate\Http\Request $request
     * @param  string $code
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $code)
    {
        $resource = Experience::whereCode($code)->firstOrFail();
        $resource = Experience::withoutGlobalScopes()->whereCode($code)->firstOrFail();
        $availability = Availability::findOrFail($request->get('availability_id'));
        $cart = Cart::items();
        $guests = Cart::has($resource->id)
            ? (Cart::get($resource->id)->guests ?? [])
            : @(unserialize(Order::where('experience_id', $resource->id)
                            ->where('customer_id', (is_null(user()) ?: user()->id))
                            ->exists() ? Order::where('experience_id', $resource->id)
                            ->where('customer_id', (is_null(user()) ?: user()->id))
                            ->first()->metadata
                            : [])
            );

        // $order = new Order();
        // $order->customer_id = user()->id;
        // $order->experience_id = $resource->id;
        // $order->total = $resource->price * (Cart::get($resource->id)->quantity ?? 1);
        // $order->price = $resource->price;
        // $order->quantity = (Cart::get($resource->id)->quantity ?? 1);
        // $order->purchased_at = null;
        // $order->metadata = serialize(Cart::get($resource->id)->guests ?? []);
        // $order->availability_id = $availability->id;

        // $order->payment_id = NULL;
        // $order->payer_id = NULL;
        // $order->token = NULL;
        // $order->status = 'pending';
        // $order->save();

        // Send Registration email
        return view("Experience::experiences.detail")->with(
                compact('resource', 'cart', 'guests', 'availability')
            );
    }

    /**
     * Add
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function add(Request $request)
    {
        Cart::clear();
        $cart = new CartModel();
        $cart->add($request->input('items'));

        // $order = Order::firstOrNew([
        //     'customer_id' => $request->input('customer_id'),
        //     'experience_id' => $request->input('experience_id'),
        // ]);
        // $order->customer_id = $request->input('customer_id');
        // $order->experience_id = $request->input('experience_id');
        // $order->total = Cart::getTotal();
        // $order->quantity = $cart->totalQuantity();
        // $order->metadata = serialize($request->input('guests'));
        // $order->status = 'pending';
        // $order->save();

        return redirect()->route('experiences.payment', [$request->input('code')]);
    }

    /**
     * Payment
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function payment(Request $request, $code)
    {
        $items = Cart::items();
        $resource = Experience::whereCode($code)->first();
        $item = $items[$resource->id];
        $availability = Availability::find($item->availability);
        // $order = Order::find($order_id);
        $total = Cart::getTotal();

        if (is_null(user())) {
            return abort(404);
        }

        // Order
        $order = Order::firstOrNew([
            'customer_id' => user()->id,
            'experience_id' => $resource->id,
            'purchased_at' => null,
        ]);
        $order->customer_id = user()->id;
        $order->experience_id = $resource->id;
        $order->total = $resource->price * Cart::get($resource->id)->quantity;
        $order->price = $resource->price;
        $order->quantity = Cart::get($resource->id)->quantity ?? 1;
        $order->purchased_at = null;
        $order->metadata = serialize(Cart::get($resource->id)->guests ?? []);
        $order->availability_id = $availability->id;

        $order->payment_id = NULL;
        $order->payer_id = NULL;
        $order->token = NULL;
        $order->status = 'pending';
        $order->save();

        // Send Registration email
        try {
            Mail::to(user()->email)->send(new OrderReceived($order, user()));
        } catch (\Exception $e) {
            return view("Experience::billing.payment")->with(
                compact('items', 'item', 'total', 'resource', 'availability')
            );
        }

        return view("Experience::billing.payment")->with(
            compact('items', 'item', 'total', 'resource', 'availability')
        );
    }

    public function success(Request $request)
    {
        $order = Order::where('payment_id', $request->get('payment_id'))
                        ->where('payer_id', $request->get('payer_id'))
                        ->where('customer_id', user()->id)
                        ->firstOrFail();

        return view("Theme::shop.success")->with(compact('order'));
    }

    public function failed(Request $request)
    {
        return view("Theme::shop.failed");
    }
}
