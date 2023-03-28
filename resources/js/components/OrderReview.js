
import React,{useEffect,useState} from 'react'
import axios from 'axios'
import {Link} from 'react-router-dom'

function OrderReview(props) {

    const[orders,setOrders] = useState([])
    useEffect(() => {

        $('#main-overlay').show();
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
       axios.get('/orderreceived/'+props.match.params.id,config).then(response=>{
           
        //    setTransaction(response.data)
           if(response.data.orders){
            setOrders(response.data.orders)
            
           }
           $('#main-overlay').hide();
       }) 
        
    }, [props])


    return (
        <div className="container py-5 my-5 py-100">
       
        {orders.map(order=>{
            return(
                <div className="row">
                <div className="col-md-2">
                <img src={order.product.image} className="img-fluid"/>
                </div>
                <div className="col-md-6">
                <h5>{order.product.name}</h5>
                {order.delivered_date ? (
                    <div>
                    <p>Delivered By {order.delivered_date}</p>
                    <p className="mt-5">Sold By: {order.fulfillment}</p>
                    </div>
                ):(<p>Not Yet Delivered.</p>)}
               
                </div>
                <div className="col-md-2">
                <h3>Rs. {order.total}</h3>
                <h7>{order.quantity}</h7>

                </div>
                <div className="col-md-2">
                {order.delivered_date?(
                    <Link className="btn btn-outline-info" to={`/review/${order.product.id}`}>Submit Review</Link>
                ):(<button className="btn btn-danger" disabled>Submit Review</button>)}
                </div>
              
                </div>
            )
        })}
       
            </div>
    
    )
}

export default OrderReview
