import axios from 'axios'
import React,{useEffect,useState} from 'react'

function Orderlist() {
    const[item,setItem]=useState([])
    useEffect(() => {
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
       axios.get('/order',config).then(response=>{
           console.log(response.data)
           setItem(response.data)
       })
    }, [])
    return (
        <div>
          {item.map(product=>{
              return(
                  <div key={product.id}>
                   <div className="cart_items">
                        <ul className="cart_list">
                            <li className="cart_item clearfix">
                                <div className="cart_item_image img-fluid"><img src={product.product.image} alt="" className="img-fluid" /></div>
                                <div className="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                    <div className="cart_item_name cart_info_col">
                                        <div className="cart_item_title">Name</div>
                                        <div className="cart_item_text">{product.product.name}</div>
                                    </div>
                                    <div className="cart_item_color cart_info_col">
                                        <div className="cart_item_title">Color</div>
                                        <div className="cart_item_text"><span style={{backgroundColor:"#999999"}}></span>Silver</div>
                                    </div>
                                    <div className="cart_item_quantity cart_info_col">
                                        <div className="cart_item_title">Quantity</div>
                                        <div className="cart_item_text">{product.product.quantity}</div>
                                    </div>
                               

                                    <div className="cart_item_price cart_info_col">
                                        <div className="cart_item_title">Price</div>
                                        <div className="cart_item_text">{product.product.price}</div>
                                    </div>
                                    <div className="cart_item_price cart_info_col">
                                    <div className="cart_item_title">Discount</div>
                                    <div className="cart_item_text">{product.product.discount}%</div>
                                </div>

                                <div className="order_total">
                                <div className="order_total_content text-md-right">
                                    <div className="order_total_title">Order status</div>
                                    <div className="order_total_amount">{product.order_status}</div>
                                </div>
                            </div>

                            <div className="order_total">
                                <div className="order_total_content text-md-right">
                                    <div className="order_total_title">Total</div>
                                    <div className="order_total_amount">{product.total}</div>
                                </div>
                            </div>

                                   
                                </div>
                            </li>
                        </ul>
                    </div>
                 </div>
              )
          })}
        
        </div>
    )
}

export default Orderlist
