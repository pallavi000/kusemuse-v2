import axios from 'axios'
import {Link, Redirect} from 'react-router-dom';
import React,{useEffect,useState,useContext} from 'react';
import {AddContext} from './Cartcontext';
import Cookies from 'universal-cookie';
import { ToastProvider, useToasts } from 'react-toast-notifications'



// Payment Error notification

function Notification(props){
    const { addToast } = useToasts()
    useEffect(() => {
        console.log(window.location)
        if(localStorage.getItem('payment_error')){
            addToast(localStorage.getItem('payment_error'), {
                appearance: 'error',
                autoDismiss: true,
              })
            localStorage.removeItem('payment_error')
        }

    }, [])

    return(
        <div></div>
    )
}


function Cartitem() {

    const [item,setItem]=useState([])
    const[total,setTotal]= useState(0)
    const[Stock,setStock] = useState(0)
    const list = []

    const data = useContext(AddContext);
        const{setCart}= data
        const{cart} = data

    useEffect(() => {
        $('#main-overlay').show();
        if(localStorage.getItem('token')){
            const config = {
                headers: {
                Authorization: 'Bearer '+localStorage.getItem('token')
                }
            }
        axios.get('/cartcount',config).then(item=>{
            setItem(item.data)
            resetTotal(item.data)
            $('#main-overlay').hide();
          

        }).catch(err=>{
            console.log(err)
            $('#main-overlay').hide();
        })
    }else{
        const cookies = new Cookies()
        var guest_id = cookies.get('guest_id') 

        axios.get('/guestcount/'+guest_id).then(item=>{
            console.log(item.data)

            setItem(item.data)
            resetTotal(item.data)
            $('#main-overlay').hide();
        })
    }
   
}, [])



function resetTotal(item) {
    var inttotal = 0;
    item.forEach(item=>{
        var value = item.price*item.quantity;
        inttotal += value;
    });
    setTotal(inttotal)
}

function distroy(id,quantity){
    $('#sec-overlay').show()

    if(localStorage.getItem('token')){
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
         
        axios.get('/distroy/'+id,config).then(response=>{
            const index = item.findIndex(item=> item.id == id)
                item.splice(index,1)
                setCart(cart-quantity)
                resetTotal(item)
                $('#sec-overlay').hide()

        })
    }else{
        axios.get('/guestremove/'+id).then(response=>{
            const index = item.findIndex(item=> item.id == id)
                item.splice(index,1)
                setCart(cart-quantity)
                resetTotal(item)
                $('#sec-overlay').hide()

        })
    }
  
}



function Qchange(e,id,quantity,product_id){
    $('#sec-overlay').show()
    const abc = {
        quantity: e.target.value,
    }

if(localStorage.getItem('token')){
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
    axios.post('/cartchange/'+id,abc,config).then(response=>{ 
        setCart((cart-quantity)+parseInt(abc.quantity))
        var newitem = [...item];
        newitem.map(item=>{
            if(item.product.id===product_id) {
        
                item.quantity = parseInt(abc.quantity);
            }
        })            
        setItem(newitem);

        console.log(newitem)

        resetTotal(newitem);
        $('#sec-overlay').hide()
     
    }).catch(error=>{
        console.log(error)
    })
}else{
    axios.post('/cartadd/'+id,abc).then(response=>{ 
        setCart((cart-quantity)+parseInt(abc.quantity))
        var newitem = [...item];
        newitem.map(item=>{
            if(item.product.id===product_id) {
        
                item.quantity = parseInt(abc.quantity);
            }
        })            
        setItem(newitem);

        console.log(newitem)

        resetTotal(newitem);
        $('#sec-overlay').hide()
     
    }).catch(error=>{
        console.log(error)
    })
}


}

function renderStocks(stock, quantity){
    const list = []
    for(var i =1; i<=stock; i++) {
        if(quantity==i) {
            list.push(<option value={i} selected >{i}</option>);
        } else {
            list.push(<option value={i}>{i}</option>);
        }
       
    }
    return list;
}



    return (
 
    <div className="cart_section">
    <ToastProvider>
        
        <Notification/>
        </ToastProvider>
    <div className="container-fluid">
        <div className="row">
            <div className="col-lg-10 offset-lg-1">
                <div className="cart_container">
                    <h2 className="cart_title  text-center text-uppercase">Shopping Cart </h2>
                    <div className="text-center">
                    <p>{item.length} Items</p>
                    </div>
                    <div className="cart_buttons"> 
                        <Link  className="btn-checkshop" to="/">Continue Shopping</Link>
                        <Link className="btn-buynow btncheckout" to="/checkout">Checkout</Link>
                     </div>


                    <div className="kms_product_overview">

                    <div className="kms_product_overview__tbody">
                
                        <div className="kms_product_overview__thead">
                            <div className="kms_product_overview__th">Item</div>
                            <div className="kms_product_overview__th">Color</div>
                            
                            <div className="kms_product_overview__th">Size</div>
                            <div className="kms_product_overview__th">Quantity</div>
                            <div className="price_column kms_product_overview__th">Price</div>
                            <div className="wide kms_product_overview__th">Discount</div>
                            <div className="kms_product_overview__th">Total</div>
                            <div className="kms_product_overview__th">Action</div>
                        </div>

                        {item.map(item=>{
                            return(
                                <div className="kms_product_overview__tr product-overview">
                                <div className="row_container kms_product_overview__td">
                                    <div className="kms_product">
                                        <div className="kms_product__tr">
                                            <div className="kms_product__td cartimggg">
                                                    <img src={item.product.image} className="img-fluid"/>
                                            </div>
                                            <div className="kms_product__td">
                                                <div>
                                                    <p className="kms_product__brand-name">{item.color}</p>
                                                </div>
                                            </div>
                                            {item.sizes ? (
                                            <div className="kms_product__td">{item.sizes.name}</div>
        
                                            )
                                              :
                                          (                            
                                              <div className="kms_product__td"></div>
                                          )
                                            }
                                            <div className="kms_product__td">
                                                <div className="select_container">
                                                    <label className="label">
                                                        <select className="select_container" onChange={(e)=>Qchange(e,item.id,item.quantity,item.product_id)} >

                                                        {item.product_size ? (
                                                            renderStocks(item.product_size.stock, item.quantity)
                                                        ):
                                                      (
                                                          renderStocks(item.product.stock, item.quantity)
                                                          
                                                      )
                                                  }
                                                            
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <div className="price_column kms_product__td">
                                                <span className="">Rs.{(item.price*100/(100-item.product.discount)).toFixed(0)}</span>
                                            </div>
                                            <div className="kms_product__td wide">{item.product.discount}%</div>
                                            <div className="kms_product__td">Rs.{item.price*item.quantity}</div>
                                            <div className="kms_product__td">
                                            <button className="btn btn-danger" onClick={()=>distroy(item.id,item.quantity)}><i className="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            )
                        })}
                    </div>
                    </div>
                    
                    <div className="order_total">
                        <div className="order_total_content text-md-right">
                            <div className="order_total_title font-weight-bold">Order Total:</div>
                            <div className="order_total_amount">Rs.{total}</div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

       
    )
}

export default Cartitem

