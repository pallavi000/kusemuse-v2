import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {Link} from 'react-router-dom';
import {AddContext} from '../Cartcontext';
import Address from './Address'
import Editaddress from './Editaddress';
import Cookies from 'universal-cookie';

function Checkout(props) {
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
    const[viewEdit,setViewEdit] = useState(null)
    const[fee,setFee] = useState(0)
    const[days,setDays] = useState(null)


    useEffect(() => {
        setViewEdit("check_main");
if(localStorage.getItem('token')){
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
        setUser(response.data)
        setCustomer(response.data.customer)
    })
 
} else {
    props.history.push('/signin/checkout')
}
    
    }, [props])


    useEffect(() => {
        if(localStorage.getItem('token') && customer && customer.length != 0){
            const config = {
                headers: {
                  Authorization: 'Bearer '+localStorage.getItem('token')
                }
            }
        axios.get('/shipping',config).then(response=>{
            response.data.map(address=>{
                if(address.location.toLowerCase() == customer.district.toLowerCase()){
                    setFee(address.amount)
                    setDays(address.days)
                }
            })
        })  
    }
    }, [customer])



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


    function address(e){
        setViewEdit('edited')
    }

    return (
        <div className="container pb-5">

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
  <div className="col-md-7">
  <div className="card">
  <div className="card-body">
  <h3 className="font-weight-bold">Shipping & Billing Address</h3>
  {customer && customer.length !=0?
  (
    <div>
        {viewEdit == "check_main" ?
        (
            <div>
            <div className="shipping-address-info">
			<span className="fa fa-check"></span>
			<div className="name">Name: {user.name}</div>
            <div className="name">Email: {user.email}</div>
            <div className="provience">Provience: {customer.provience}</div>
            <div className="district-state">District: {customer.district}</div>
            <div className="city">City: {customer.city}</div>
			<div className="local-address">Street: {customer.street}</div>
			<div className="mobile">Contact No.: {customer.phone}</div>
			
		</div>
            
            <button className="btn btn-warning" onClick={(e)=>address(e)}>Change Shipping Address</button> &nbsp; &nbsp;
            <Link className="btn btn-primary"  to={{pathname: `/payment`, query: {total, coupon_discount, coupon_pid,couponId,fee,days}}}>Proceed Payment</Link>

            </div>

        )
        :
        (
            <Editaddress/>
        )
        } 
       
    </div>
    )
    :
    (
    <Address/>
    )
    }

</div>
</div>
</div>

<div className="col-md-5">

<div className="card-body">
<h6 class="mb-7">Order Items ({item.length})</h6>
             
              
                 <hr class="my-7"/>
             
             
                 <ul class="list-group list-group-lg list-group-flush-y list-group-flush-x mb-7">
                 {item.map(item=>{
                    return(
                  <li class="list-group-item"  key={item.id}>
                   <div class="row align-items-center">

                    <div class="col-4">
             
                 
                     <a href="product.html">
                      <img src={item.product.image} alt="..." class="img-fluid"/>
                     </a>
             
                    </div>
                    <div class="col">
             
              
                     <p class="mb-4 font-size-sm font-weight-bold">
                      <a class="text-body" href="product.html">{item.product.name}</a> <br/>
                      <span class="text-muted">Rs.{item.price}</span>
                     </p>
             
                   
                     <div class="font-size-sm text-muted">
                     {item.sizes ? (
                        <span>Size: {item.sizes.name}<br/></span>
                    )
                      :
                  (null)}
                     
                      Color: {item.color}
                     </div>
             
                    </div>
                   </div>
                  </li>
                    )
                    })}
                 </ul>





<div className="">
<form className="d-flex" onSubmit={(e)=>addCoupon(e)}>
<input className="form-control" type="text" name="coupon" onChange={(e)=>setCoupon(e.target.value)} placeholder="enter coupon code" required/>
<button className="btn btn-info ml-3 coupon-button">Submit</button>
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
  <div className="order_total_title">Sub Total</div>
  <div className="order_total_amount">{total}</div><br/>
  <div className="order_total_title">Shipping fee</div>
  <div className="order_total_amount">{fee}</div>
</div>

</div>

<div className="order_total">
<div className="order_total_content text-md-right">
  <div className="order_total_title">Order Total:</div>
  <div className="order_total_amount">{total+fee}</div>
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
