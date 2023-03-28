<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script>
body {
    background-color: #000
}

.padding {
    padding: 2rem !important
}

.card {
    margin-bottom: 30px;
    border: none;
    -webkit-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
    -moz-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
    box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22)
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid #e6e6f2
}

h3 {
    font-size: 20px
}

h5 {
    font-size: 15px;
    line-height: 26px;
    color: #3d405c;
    margin: 0px 0px 15px 0px;
    font-family: 'Circular Std Medium'
}

.text-dark {
    color: #3d405c !important
}
     </script>
</head>
<body>
@php
$subtotal= 0;
$shipping_cost= 0;
$total = 0;
$discount = 0;
$abc = $order;
$id = 0;
$coupoun_discount = 0;

foreach($order as $order){
    $subtotal += $order->total;
    $coupoun_discount += $order->coupon_discount;
    if($order->transaction){
        $shipping_cost = $order->transaction->shipping_cost;
        $id = $order->transaction_id;
    }
    $discount = $order->discount;
    $total +=  $order->total;
    
   
}


@endphp





<div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
     <div class="card">
         <div class="card-header p-4">
             <a class="pt-2 d-inline-block" href="index.html" data-abc="true">KUSEMUSE</a>
             <div class="float-right">
                 <h5 class="mb-0">Invoice No.{{$id}}</h5>
                 Date: {{$order->created_at}}
             </div>
         </div>
         <div class="card-body  pt-5">
             <table class="pt-5 pb-5" style="width: 100%">
                 <tr>
                     <td>
                     <p >From:</p>
                     <p class="text-dark mb-1"><strong>Kusemuse.com</strong></p>
                     <div>11, Bharatpur</div>
                     <div>Chitwan</div>
                     <div>Email: kusemuse123@gmail.com</div>
                     <div>Phone: +91 9897 989 989</div>
</td>

                     <td>
                         <div style="float:right">
                     <p>To:</p>
                     <p class="text-dark mb-1"><strong>{{$customer->user->name}}</strong></p>
                     <div>{{$customer->street}}</div>
                     <div>{{$customer->city}},{{$customer->district}}</div>
                     @if(auth()->user()->is_admin==1)
                     <div>Email: {{$customer->user->email}}</div>

                     <div>Phone: {{$customer->phone}}</div>
                     @endif
</div>
</td>
</tr>

                
</table>
             <div class="table-responsive-sm">
                 <table class="table table-striped small">
                     <thead>
                         <tr>
                             <th class="center">#</th>
                             <th>Item</th>
                          
                             <th class="right">Price</th>
                             <th class="center">Qty</th>
                             <th class="center">Discount</th>

                             <th class="right">Total</th>
                         </tr>
                     </thead>
                     <tbody>
                        
                         @foreach($abc as $orderr)
                            <tr class="content">
                                <td>{{$orderr->id}}</td>
                                <td>{{$orderr->product->name}}</td>
                               <td>Rs.{{ round($orderr->price*100/(100-$orderr->discount),0)}}</td>
                                <td>{{$orderr->quantity}}</td>
                                <td>{{$orderr->discount}} %</td>
                                <td class="text-center">Rs.{{$orderr->total +$orderr->coupon_discount}}</td>
                            </tr>
                            @endforeach
                        
                     </tbody>
                 </table>
             </div>
             <div class="row">
                
                 <div class="col-lg-4 col-sm-5 ml-auto ">
                     <table class="table table-clear small">
                         <tbody>
                             <tr>
                                 <td class="left">
                                     <strong class="text-dark">Subtotal</strong>
                                 </td>
                                 <td class="right"style="text-align:center;">Rs.{{$subtotal + $coupoun_discount}}</td>
                             </tr>
                             <tr>
                                 <td class="left">
                                     <strong class="text-dark">Coupoun Discount</strong>
                                 </td>
                                 <td class="right" style="text-align:center;"> - Rs.{{$coupoun_discount}}</td>
                             </tr>
                             
                             <tr>
                                 <td class="left">
                                     <strong class="text-dark">Shipping</strong>
                                 </td>
                                 <td class="right" style="text-align:center;">Rs.{{$shipping_cost}}</td>
                             </tr>
                             <tr>
                                 <td class="left">
                                     <strong class="text-dark">Total</strong> </td>
                                 <td class="right" style="text-align:center;">
                                     <strong class="text-dark" >Rs. {{$total+ $shipping_cost}}</strong>
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         
     </div>
 </div>
</body>
</html>