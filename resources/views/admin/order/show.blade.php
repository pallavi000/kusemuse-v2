@extends('layouts.admin')

@section('content')
<script src="//unpkg.com/timeago.js"></script>

@php
$ip = NULL;
$order_status = NULL;
$total = 0;
$coupon_discount = 0;

if($transaction->seller_orders && auth()->user()->is_admin!=1) {
    $orders = $transaction->seller_orders;
$looporders = $transaction->seller_orders;

} else {
    $orders = $transaction->orders;
$looporders = $transaction->orders;
}
$ship_date = "Not Shipped Yet";
$delivered_date = "Not Delivered";
$fulfillment = NULL;
foreach($looporders as $order){
    if($order->ship_date){
        $ship_date = $order->ship_date;
    }
    if($order->delivered_date){
        $delivered_date = $order->delivered_date;
    }
    $fulfillment = $order->fulfillment;
    $ip = $order->ipaddress;
    $order_status = $order->order_status;
    $total += $order->total;
    $coupon_discount = $order->coupon_discount;
    
}
@endphp

<div class="content py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
            <div class="card">
                  <div class="card-body pb-5 px-5">
                    <h3>Order #{{$transaction->id}} details</h3>
                    <p>Payment via {{$transaction->payment_method}} ({{$transaction->transaction_id}}). Paid on {{$transaction->created_at->diffForHumans()}} </p>
                     <p> Customer IP: {{$ip}}</p>
                     <div class="row pt-5">
                         <div class="col-md-4">
                         <h3>General</h3>
                             <p>Date Created:{{$transaction->created_at}}</p>
                             <p>Ship Date: {{$ship_date}}</p>
                             <p>Delivered Date: {{$delivered_date}}</p>
                             @if($fulfillment)
                             <p>Fulfillment: {{$fulfillment}}</p>
                             @endif
                             <p>Status:
                             @if(auth()->user()->is_admin==1)
                                    <form class="ODform{{ $transaction->id }}" action="{{ route('admin.order.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                        @else
                                        <form class="ODform{{ $transaction->id }}" action="{{ route('order.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                        @endif
                                    @csrf
                                    @method('PUT')
                                    <select class="ODstatus" name ="order_status" oid="{{ $transaction->id }}">
                                        <option value="processing"  @if($order_status=="processing")selected @endif>Processing</option>
                                        <option value ="shipped"  @if($order_status=="shipped")selected @endif> Shipped</option>
                                        <option value="completed" @if($order_status=="completed")selected @endif>Completed</option>
                                        <option value="cancelled" @if($order_status=="cancelled")selected @endif>Cancelled</option>

                                    </select>
                                    </form>
                             </p>
                        </div>
                        <div class="col-md-4">
                            <h3> Billing</h3>
                            <p class="my-0 py-0 text-secondary text-uppercase">{{$transaction->user->name}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->billing->city}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->billing->street}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->billing->district}}</p>
                          <p class="my-0 py-0 text-secondary">{{$transaction->billing->provience}}</p>
                          @if(auth()->user()->is_admin==1)
                        <p class="my-0 py-0 text-secondary"><strong>Email: {{$transaction->user->email}}</strong></p>
                        <p class="my-0 py-0 text-secondary"><strong>Phone: {{$transaction->billing->phone}}</strong></p>
                        @endif
                        </div>
                        <div class="col-md-4">
                        <h3>Shipping</h3>
                        <p class="my-0 py-0 text-secondary text-uppercase">{{$transaction->user->name}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->shipping->city}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->shipping->street}}</p>
                           <p class="my-0 py-0 text-secondary">{{$transaction->shipping->district}}</p>
                          <p class="my-0 py-0 text-secondary">{{$transaction->shipping->provience}}</p>
                          @if(auth()->user()->is_admin==1)
                          <p class="my-0 py-0 text-secondary"><strong>Email: {{$transaction->user->email}}</strong></p>
                        <p class="my-0 py-0 text-secondary"><strong>Phone: {{$transaction->shipping->phone}}</strong></p>
                        @endif
                        </div>
                        </div>
                 </div>
                    </div>
                    <div class="card">
                  <div class="card-body pb-5 ">
    <div id="woocommerce-order-items" class="postbox ">
    <div class="postbox-header"><h2 class="hndle ui-sortable-handle">Items</h2>
    <div class="inside">
    <div class="woocommerce_order_items_wrapper wc-order-items-editable">
        <table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
            <thead>
                <tr>
                    <th class="item sortable" colspan="2" data-sort="string-ins">Item</th>
                                    <th class="item_cost sortable" data-sort="float">Cost</th>
                    <th class="quantity sortable" data-sort="int">Qty</th>
                    <th class="line_cost sortable" data-sort="float">Total</th>
                                    <th class="wc-order-edit-line-item" width="1%">&nbsp;</th>
                </tr>
            </thead>
            <tbody id="order_line_items">

            @foreach($orders as $order)
                <tr class="item " data-order_item_id="2">
        <td class="thumb">
            
            <div class="wc-order-item-thumbnail"><img width="150" height="150" src="{{$order->product->image}}" class="attachment-thumbnail size-thumbnail" alt="" loading="lazy" title=""></div> </td>
        <td class="name" data-sort-value="test product">
            <p class="wc-order-item-name">{{$order->product->name}}</p>      
    
         
                </td>
    
        
        <td class="item_cost" width="1%" >
            <div class="view">
                <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$order->price}}</bdi></span>     </div>
        </td>
        <td class="quantity" width="1%">
            <div class="view">
                <small class="times">×</small> {{$order->quantity}}    </div>
           
            
        </td>
        <td class="line_cost" width="1%" >
            <div class="view">
                <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$order->total + $order->coupon_discount}}</bdi></span>     </div>
            
            
        </td>
    
            <td class="wc-order-edit-line-item" width="1%">
            <div class="wc-order-edit-line-item-actions">
                        </div>
        </td>
    </tr>
    @endforeach
            </tbody>
            <tbody id="order_fee_line_items">
                        </tbody>
            <tbody id="order_shipping_line_items">
                        </tbody>
            <tbody id="order_refunds">
                        </tbody>
        </table>
    </div>
    <div class="wc-order-data-row wc-order-totals-items wc-order-items-editable">
            <table class="wc-order-totals">
                <tbody>
                    <tr>
                    <td class="label">Items Subtotal:</td>
                    <td width="1%"></td>
                    <td class="total">
                        <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$total  + $coupon_discount}}</bdi></span>             </td>
                </tr>
                @if(auth()->user()->is_admin==1)
                <tr>
                    <td class="label">Shipping Cost:</td>
                    <td width="1%"></td>
                    <td class="total">
                        <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$transaction->shipping_cost}}</bdi></span>             </td>
                </tr>
                @endif
                <tr>
                    <td class="label">Coupon:</td>
                    <td width="1%"></td>
                    <td class="total">
                        <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$coupon_discount}}</bdi></span>             </td>
                </tr>
               
            
            <tr>
                <td class="label">Order Total:</td>
                <td width="1%"></td>
                <td class="total">
                    <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span>&nbsp;{{$total + $transaction->shipping_cost}}</bdi></span>         </td>
            </tr>
    
        </tbody></table>
    
      
    </div>
    
    
    
    </div>
    </div>
    </div>










                    </div>
                    </div>


                     </div>
                <div class="col-md-4">
                <div class="card">
                <div class="card-body pb-5 px-5">
                <h3>Payment Gateway</h3>
                <hr>
                <table>
                    <tr>
                        <td>State:</td>
                        <td>CAPTURED</td>
                    </tr>
                    <tr>
                        <td>Payment-ID:</td>
                        <td>{{$transaction->transaction_id}}</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>Rs.{{$total +$transaction->shipping_cost}}</td>
                    </tr>
</table>
               

                    
                    </div>
                     </div>
                     <div class="card">
                    <div class="card-body pb-5 px-5">
                        <h3>Invoice PDF</h3>
                        <hr>
                        @if(auth()->user()->is_admin==1)
                            <a href="{{ route('admin.order.invoice', [$transaction->id]) }}" class="btn btn-sm btn-success mr-1"> Create PDF </a>
                            @else
                            <a href="{{ route('seller.order.invoice', [$transaction->id]) }}" class="btn btn-sm btn-success mr-1"> Create PDF </a>
                        @endif

</div>
</div>
        </div>

    </div>
</div>

<script>

$('.ODstatus').on('change',function(e){
   var a = $(this).attr('oid');
   $('.ODform'+a).submit();
})
$('.PSstatus').on('change',function(e){
   var a = $(this).attr('oid');
   $('.PSform'+a).submit();
})

</script>

@endsection