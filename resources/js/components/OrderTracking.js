import React from 'react'
import { useState } from 'react'
import axios from 'axios'

function OrderTracking() {
const[orderId,setOrderId] =useState('')
const[email,setEmail] = useState('')
const[error,setError] = useState(false)
const[order,setOrder] = useState([])

function orderTrack(e){
    $('#sec-overlay').show()
    e.preventDefault()
    const data={
        orderId,
        email
    }
    axios.post('/order-tracking',data).then(response=>{
        console.log(response.data)
        setOrder(response.data)
        $('#sec-overlay').hide()
    }).catch(err=>{
        setError(true)
        $('#sec-overlay').hide()
    })
}


    return (
        <div className="container py-5 text-center">
        <h2>Track your Order</h2>
        <div className="pt-5">
        {error && (<div className="alert alert-danger">Sorry, the order could not be found. Please contact us if you are having difficulty finding your order details.</div>)}

        {order && order.length!=0 ?(
            <div className="row">
            Order #{order.id} was placed on {order.createdAt} and is currently <strong> {order.payment_status}</strong>.
            <div className="col-md-12 px-0 text-left">
            <h3 className="px-0 font-weight-bold text-left mt-4">Order detail</h3>
            <div className="received-line my-5">
            <div className="red-line">
            </div>
            </div>
            <div className="row">
            <div className="col-md-6 pr-0">
            <hr/>
            <strong>Product</strong>
            <hr/>
             <p className="mx-0">{order.product?.name} x {order.quantity}</p>
            <hr/>
           
            <strong>Subtotal</strong>
            <hr/>
            <strong>Coupon Discount</strong>
            <hr/>
            <strong>Shipping</strong>
            <hr/>
            <strong>Payment Method</strong>
            <hr/>
            <strong>Total</strong>
            <hr/>

            </div>
            <div className="col-md-6 pl-0">
            <hr/>
            <strong>Total</strong>
            <hr/>
                    {order.coupon_discount ?(
                        <p className="mx-0">Rs. {order.total + order.coupon_discount}</p>
                    ):(
                        <p className="mx-0">Rs. {order.total}</p>
                    )}
            <hr/>
            
            <strong className="or">Rs. { order.total- order.shipping_cost+order.coupon_discount}</strong>
            <hr/>
            <strong className="or">Rs. {order.coupon_discount}</strong>
            <hr/>
            <strong className="or">Rs. {order.shipping_cost}</strong>
            <hr/>
            <strong className="or">{order.payment_method}</strong>
            <hr/>
            <strong className="or">Rs. {order.total+ order.shipping_cost}</strong>
            <hr/>
            </div>
            </div>
            </div>
            <div className="col-md-12 container pt-5">
                <div className="row text-left ">
                    <div className="col-md-6">
                        <h3>Billing address</h3>
                        <p>{order.user.name}</p>
                        <p>{order.billing.district}</p>
                        <p>{order.billing.city}</p>
                        <p>{order.billing.street}</p>
                        <p>{order.billing.phone}</p>
                        <p>{order.user.email}</p>
                     </div>
                    <div className="col-md-6">
                        <h3>Shipping Address</h3>
                        <p>{order.user.name}</p>
                        <p>{order.shipping.district}</p>
                        <p>{order.shipping.city}</p>
                        <p>{order.shipping.street}</p>
                        <p>{order.shipping.phone}</p>
                       
                    </div>
            </div>
            </div>
            </div>
        ):(
            <>
            <p>To track your order please enter your Order ID in the box below and press the "Track" button. This was given to you on your receipt and in the confirmation email you should have received.
        </p>
            <form onSubmit={(e)=>orderTrack(e)}>
            <div className="row pt-4">
            <div className="col-md-5">
            <div className="form-group">
            <label>Order Id</label>
            <input type="text" className="form-control" onChange={(e)=>setOrderId(e.target.value)} name="order_id" required placeholder="found in your order confirmation email"/>
            </div>
            </div>
            <div className="col-md-5">
            <div className="form-group">
            <label>User Email</label>
            <input type="email" className="form-control" name="email" onChange={(e)=>setEmail(e.target.value)} required placeholder="your email"/>
            </div>
            </div>
            </div>
            <button className="btn btn-primary">Track</button>
            </form>
            </>
        )}
        </div>
        </div>
    )
}

export default OrderTracking
