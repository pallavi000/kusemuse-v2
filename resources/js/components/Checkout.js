import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {Link} from 'react-router-dom';
import {AddContext} from './Cartcontext';

function Checkout() {
    const [item,setItem]=useState([])
    const[total,setTotal]= useState(0)
    const data = useContext(AddContext);
    const{setCart}= data
    const{cart} = data
    const[customer, setCustomer] = useState([])
    const[user,setUser] = useState([])
    const[coupon,setCoupon]= useState(null)
    const[coupon_pid,setCoupon_pid]= useState(0)
    const[coupon_discount,setCoupon_discount]= useState(0)
    const[couponErr, setCouponErr] = useState(null)
    const[couponId,setCouponId] = useState(0)


    useEffect(() => {
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
        }
        axios.get('/cartcount',config).then(response=>{
        console.log(response.data)
        setItem(response.data)
        resetTotal(response.data)
    })
    axios.get('/profile',config).then(response=>{
        console.log(response.data)
        setUser(response.data)
        setCustomer(response.data.customer)
    })
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
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
        axios.get('/distroy/'+id,config).then(response=>{
            
            const index = item.indexOf(item=> item.product.id === id)
            item.splice(index,1)
            setCart(cart-quantity)
            resetTotal(item)

        }).catch(error=>{
            console.log(error.request.response)
        })
    }

    function Qchange(e,id,quantity,product_id){
        const abc = {
            quantity: e.target.value,

        }
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
            resetTotal(newitem);
        }).catch(error=>{
            console.log(error)
        })
    }

    function addCoupon(e){
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
        e.preventDefault();
        const data = {
            coupon:coupon,
        }
        console.log(item);
       
        axios.post('/coupon',data,config).then(response=>{
            console.log(response.data)



            var newitem = [...item];
            newitem.map(item=>{
                if(item.product.id===response.data.product_id) {

                    if(response.data.minimumSpend && (item.price*item.quantity)< response.data.minimumSpend)
                    {
                        setCouponErr('Total is less than minimum Spend')
                        $('.invalid').show()
                        $('.valid').hide()
                    }else if(response.data.maximumSpend && (item.price*item.quantity)> response.data.maximumSpend){
                        setCouponErr('Total is more than maximum Spend')
                        $('.invalid').show()
                        $('.valid').hide()
                    }else if(response.data.usagesLimit && response.data.usagesLimit==0){
                        setCouponErr('Coupon usages limit exceeded')
                        $('.invalid').show()
                        $('.valid').hide()
                    
                    }else{
                        var coupon_dis;
                        if(response.data.type==='cash'){
                            item.price = item.price-response.data.value
                            coupon_dis = response.data.value
                        }else{
                            coupon_dis = (item.price*response.data.value/100).toFixed(0)
                            item.price = (item.price-(item.price*response.data.value/100)).toFixed(0);
                            console.log(coupon_dis)
                        }
                        


                        setCoupon_pid(item.product.id)
                        setCoupon_discount(coupon_dis) 
                        setCouponId(response.data.id)

                        setItem(newitem);
                        resetTotal(newitem)
                        $('.invalid').hide()
                        $('.valid').show()
                        $('.coupon-button').prop('disabled','true')
                        
                    }
                
                }
            })  

        }).catch(error=>{
            $('.invalid').show()
            $('.valid').hide()
            console.log(error.request.response)
            setCouponErr('Coupon not found')
        })
    }

    return (
        <div className="container">

        {Object.keys(item).length === 0 ? 
        (
            <div className="py-5 text-center">
            <Link className="btn btn-outline-secondary btn-lg" to ="/">Continue Shopping</Link>
            </div>
        )
        :
        (

<div>
<div className="pb-5 text-center">
<h2>Cart Bag</h2>
</div>
<div className="row">
  <div className="col-md-6">
  <div className="card">
  <div className="card-body">
  {customer?
  (
   <div>
   {user.name}<br/>
  {user.email}<br/>
  {user.phone}<br/>
  {customer.city}
  {customer.street}
  <br/>
  <br/>
  <Link className="btn btn-warning" to={`/editaddress/${customer.id}`}>Edit Address</Link> &nbsp; &nbsp;
  <Link className="btn btn-primary"  to={{pathname: `/payment`, query: {total, coupon_discount, coupon_pid,couponId}}}>Proceed Payment</Link>
  </div>
)
:
(
<div>
<Link className="btn btn-warning" to="/address">Add Billing Address</Link>

</div>
)
}

</div>
</div>
</div>
<div className="col-md-6">
<div className="card">
<div className="card-body">
{item.map(item=>{
return(
<div key={item.id}>
<div className="cart_items">
  <ul className="cart_list">
      <li className="cart_item clearfix">
          <div className="cart_item_image "><img src={item.product.image} alt="" className="img-fluid" /></div>
          <div className="cart_item_info d-flex flex-md-row flex-column justify-content-between">
              <div className="cart_item_name cart_info_col">
                  <div className="cart_item_title">Name</div>
                  <div className="cart_item_text">{item.product.name}</div>
              </div>
              <div className="cart_item_color cart_info_col">
                  <div className="cart_item_title">Color</div>
                  <div className="cart_item_text"><span style={{backgroundColor:"#999999"}}></span>{item.color}</div>
              </div>
              <div className="cart_item_color cart_info_col">
              <div className="cart_item_title">Size</div>
              <div className="cart_item_text"><span style={{backgroundColor:"#999999"}}></span>{item.size}</div>
          </div>
              <div className="cart_item_quantity cart_info_col">
                  <div className="cart_item_title">Quantity</div>
                  <input type="number" style={{border:"1px solid black"}} min="1" onChange={(e)=>Qchange(e,item.id,item.quantity,item.product.id )} defaultValue={item.quantity}/>
              </div>
              <div className="cart_item_price cart_info_col">
                  <div className="cart_item_title">Price</div>
                  <div className="cart_item_text">{item.price}</div>
              </div>
              <div className="cart_item_price cart_info_col">
              <div className="cart_item_title">Discount</div>
              <div className="cart_item_text">{item.product.discount}%</div>
          </div>
              <div className="cart_item_total cart_info_col">
                  <div className="cart_item_title">Total</div>
                  <div className="cart_item_text">{item.price*item.quantity}</div>
              </div>
              <button className="btn btn-danger" onClick={()=>distroy(item.id,item.quantity)}><i className="fa fa-trash"></i></button>
          </div>
      </li>
  </ul>
</div>
</div>
)

})}

<div className="">
<form className="d-flex" onSubmit={(e)=>addCoupon(e)}>
<input className="form-control" type="text" name="coupon" onChange={(e)=>setCoupon(e.target.value)} placeholder="enter coupon code" required/>
<button className="btn btn-primary ml-3 coupon-button">Submit</button>
</form>
<div className="valid-feedback valid font-weight-bold ">
<i className="fa fa-check"></i> Coupon Successfully applied
</div>

<div className="invalid-feedback invalid font-weight-bold">
<i className="fa fa-times"></i> {couponErr}
</div>
</div>
<div className="order_total">
<div className="order_total_content text-md-right">
  <div className="order_total_title">Order Total:</div>
  <div className="order_total_amount">{total}</div>
</div>
</div>
</div>
</div>
</div>
</div>






</div>

        )}



       
        </div>
    )
}

export default Checkout
