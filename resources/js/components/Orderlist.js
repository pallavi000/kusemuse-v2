import axios from 'axios'
import React,{useEffect,useState} from 'react'
import {format} from 'timeago.js'
import {Link} from 'react-router-dom'
import UserSidebar from '../global/UserSidebar'
import Reviews from './Reviews'
import { ToastProvider, useToasts } from 'react-toast-notifications'



function Notification(props){
    const { addToast } = useToasts()
   
    useEffect(() => {

        if(localStorage.getItem('notification')){
            addToast('Thankyou For Your Review', {
                appearance: 'success',
                autoDismiss: true,
              })
            localStorage.removeItem('notification')
        }
    }, [props])

    return(
        <div></div>
    )
}




function Orderlist(props) {
    console.log(props)
    const[item,setItem]=useState([])
    const[var2,setVar2] = useState(0)
    const[showReview,setShowReview] = useState(false)

    useEffect(() => {
      setShowReview(false)
    }, [props])

    useEffect(() => {

        $('#sec-overlay').show();
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
       axios.get('/order',config).then(response=>{
           console.log(response.data)
           setItem(response.data)
           $('#sec-overlay').hide()
       }).catch(error=>{
           console.log(error.request.response)
       })
       
      
    }, [var2])


function ordercancel(id){
var remove = confirm('Are you sure you want to cancel your order??')
if(!remove){
    return false;
}

    $('#sec-overlay').show()
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      } 
axios.get('/ordercancel/'+id,config).then(response=>{
    setVar2(Math.random)
    console.log(response.data)

})
}

return(
    <main>
    <section class="dashboard-ui position-relative">
        <div class="container">


        <ToastProvider>
        
        <Notification/>
        </ToastProvider>

        {showReview ?(
            <div className="popup-box">
                    <div className="box cart">
                        <span className="close-icon" onClick={()=>setShowReview(false)}><i className="fa fa-times"></i></span>
                        <div className="popup-detail">
                            <Reviews pid={showReview}/>
                        </div>
                        </div>
                        </div>
        ):(null)}


            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <UserSidebar/>
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="dashboard-panel">
                    {item.map(transaction=>{
                        return(
                        <div class="panel-widget p-5">
                            <div class="font-weight-bold mb-3 text-uppercase">Orders</div> 
                            
                                
                            <div class="orderlistcards">
                            
                                <div class="orderlistcard__title clearfix">
                                    <p class="float-left">Transaction No : {transaction.id} |{format(transaction.created_at)} </p>
                                    <a href="#" class="btn-sm btn-border float-right">Print Order</a>
                                </div>
                                
                                <div class="shipping-information-block">
                                  <div class="row">
                                    <div class="col-md-4"> 
                                        <strong class="order-info-text-block__title">Shipping Information:</strong>
                                        <p>{transaction.shipping.street},{transaction.shipping.city},{transaction.shipping.district}</p>
                                         <p>{transaction.user.name}| {transaction.shipping.phone} </p>
                                       
                                    </div>
                                    <div class="col-md-4">
                                        <strong class="order-info-text-block__title">Billing Information:</strong>
                                        <p>{transaction.billing.street}, {transaction.billing.city},{transaction.billing.district}</p>
                                          <p>{transaction.user.name}| {transaction.billing.phone} </p>
                                    </div>
                                    <div class="col-md-4">                                      
                                        <strong class="order-info-text-block__title">Payment Information:</strong>
                                        <p>{transaction.payment_method} (+Rs.{transaction.shipping_cost})</p>
                                        <strong>Grand total: Rs.{transaction.amount}</strong>
                                      
                                    </div>
                                  </div>  
                                </div>
                                <div class="order-info-block">
                                {transaction.orders.map(order=>{
                                    return(
                                    <div class="order-info">
                                    {order.product ?(
                                        <div class="row">
                                            <div class="col-3 col-md-3">
                                                <div class="product-image">
                                                <Link to={`product-detail/${order.product.id}`}>
                                                    <a href=""><img src={order.product.image} class="img-fluid" /></a>
                                                    </Link>
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-9">
                                                <div class="order-product-info">
                                                {order.product.brand ?(
                                                    <strong>{order.product.brand.name}</strong>
                                
                                                ):(null)}
                                               
                                                <p>{order.product.name}</p>
                                                    <div class="p-price">Rs.{order.price}</div>
                                                    <div class="p-data">
                                                        <span><strong>Color</strong> : {order.color}</span>
                                                        <span><strong>Quantity</strong> : {order.quantity}</span>
                                                        <span><strong>Order ID</strong> : {order.id}</span>
                                                        <span><strong>Size</strong> : 
                                                         {order.sizes ?(
                                                            <> {order.sizes.name}</>
                                                        ):(<> OS</>)}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ):(null)}


                                        {order.product ?(

                                            <div class="order-status border-top  mt-2 clearfix pt-4">
                                            {order.order_status=="completed" ? (
                                                <button onClick={()=>setShowReview(order.product_id)} className="btn-sm btn-border review-btn float-left "><strong>Write Review</strong></button>
                                            ):(null)}
                                           
                                            
                    <p>{order.order_status == 'pending'? (
                        <button className="btn btn-outline-danger float-right" onClick={()=>ordercancel(order.id)}>Cancel</button>

                    ):order.order_status=='cancelled' ?
                    
                    (
                        <button className="btn btn-danger float-right">{order.order_status}</button>
                    )
                    :( 
                      <button className="btn btn-info float-right">{order.order_status}</button>
                    )}</p>
              
                                              
                                               
                                            </div>
                                            
                                        ):(null)}
                                        
                                    </div>
                                   
                                    )
                            })}
                                </div>
                            
                            </div>
                         
                        </div>
                        )
                    })}
                    </div>
                
                </div>
            </div>
        </div>
    </section>
</main>

)
}
export default Orderlist
