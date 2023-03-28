import React,{useEffect,useState} from 'react'
import axios from 'axios'
import {format} from 'timeago.js'
import { ToastProvider, useToasts } from 'react-toast-notifications'




function Notification(props){
    const { addToast } = useToasts()
    useEffect(() => {
        console.log(window.location)
        if(localStorage.getItem('notification')){
            addToast('your order has been successfully placed!!', {
                appearance: 'success',
                autoDismiss: true,
              })
            localStorage.removeItem('notification')
        }
        
       


    }, [])

    return(
        <div></div>
    )
}




function OrderReceived(props) {
    const[transaction,setTransaction] = useState([])
    const[orders,setOrders] = useState([])
    const[coupon_discount,setCoupon_discount]=useState(0)
    
useEffect(() => {
    $('#main-overlay').show();
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
   axios.get('/orderreceived/'+props.match.params.id,config).then(response=>{
       if(response.data.length == 0){
           props.history.push('/orderlist')
       }
       setTransaction(response.data)
       if(response.data.orders){
        setOrders(response.data.orders)
        var total = 0
        response.data.orders.map(order=>{
            total += order.coupon_discount
        })
        setCoupon_discount(total)


       }
       $('#main-overlay').hide();
   }) 
    
}, [props])



    return (
        <div className="container  received-container py-5" style={{maxWidth:"1140px"}}>
        <ToastProvider>
        
            <Notification/>
        </ToastProvider>
            <h2 className="text-center font-weignt-bold pt-5 pb-5">Order received</h2>
            <p>Thank You! Your Order has been received</p>

            <div>
            
            <ul>
            <li>Transaction number: <strong>{transaction.id}</strong></li>
            <li>Date:<strong> {format(transaction.created_at)}</strong></li>
            <li>Total:<strong> Rs.{transaction.amount}</strong></li>
            <li>Payment Method: <strong>{transaction.payment_method}</strong></li>
            </ul>
            {transaction.payment_method == "COD" ? (
                <p className="py-4">Pay with cash upon delivery</p>

            ):(
                <p className="py-4">Paid with {transaction.payment_method}</p>

            )}
            </div>

            <div className="row">
            <div className="col-md-12 px-0">
            <h3 className="px-0 font-weight-bold">Order detail</h3>
            <div className="received-line my-5">
            <div className="red-line">

            </div>
            </div>
            <div className="row">
            <div className="col-md-6 pr-0">
            <hr/>
            <strong>Product</strong>
            <hr/>
            {orders.map(order=>{
                return(
                    <p className="mx-0">{order.product.name} x {order.quantity}</p>

                )
            })}
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
            {orders.map(order=>{
                return(
                    order.coupon_discount ?(
                        <p className="mx-0">Rs. {order.total + order.coupon_discount}</p>

                    ):(
                        <p className="mx-0">Rs. {order.total}</p>

                    )

                )
            })}
            <hr/>
            
            <strong className="or">Rs. {transaction.amount- transaction.shipping_cost+coupon_discount}</strong>
            <hr/>
            <strong className="or">Rs. {coupon_discount}</strong>
            <hr/>
            <strong className="or">Rs. {transaction.shipping_cost}</strong>
            <hr/>
            <strong className="or">{transaction.payment_method}</strong>
            <hr/>
            <strong className="or">Rs. {transaction.amount}</strong>
            <hr/>
            </div>
            </div>
            </div>
           
            </div>

        </div>
    )
}

export default OrderReceived
