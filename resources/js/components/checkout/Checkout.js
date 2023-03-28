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
    const {cartdetail} = data;
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
    const[note,setNote] = useState(null)


    useEffect(() => {
        $('#main-overlay').show();
        setViewEdit("check_main");
        if(localStorage.getItem('token')){
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
    }
    axios.get('/profile',config).then(response=>{
        setUser(response.data)
        setCustomer(response.data.customer)
        $('#main-overlay').hide();
    })
    } else {
    props.history.push('/signin/checkout')
    }
    }, [props])


    useEffect(() => {
        cartdetail.map(cart=>{
            if(cart.quantity > cart.stock){
            localStorage.setItem('payment_error', cart.product.name+" quanity can't be greater than available stock.")
            props.history.push('/cartitem');
            }
        })
        setItem(cartdetail)
        resetTotal(cartdetail);
    }, [cartdetail,props])


    useEffect(() => {
        if(localStorage.getItem('token') && customer && customer.length != 0){
            const config = {
                headers: {
                Authorization: 'Bearer '+localStorage.getItem('token')
                }
            }
        axios.get('/change-shipping',config).then(response=>{
           setFee(response.data.fees)
           setDays(response.data.days);
        }).catch(err=>{
            console.log(err.request.response)
        })

    }
    }, [customer,props])



    function resetTotal(item) {
        var inttotal = 0;
        item.forEach(item=>{
            var value = item.price*item.quantity;
            inttotal += value;
        });
        setTotal(inttotal)
    }

    // add coupon code

    function addCoupon(e){
        $('#sec-overlay').show()

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
                            //item.price = item.price-response.data.value
                            coupon_dis = response.data.value
                        }else{
                            coupon_dis = ((item.price*item.quantity)*response.data.value/100).toFixed(0)
                            //.price = (item.price-(item.price*response.data.value/100)).toFixed(0);
                        }
                        setCoupon_pid(item.product.id)
                        setCoupon_discount(coupon_dis) 
                        setCouponId(response.data.id)
                        setItem(newitem);
                        resetTotal(newitem)
                        $('.invalid').hide()
                        $('.valid').show()
                        $('.coupon-button').prop('disabled','true')
                        $('#sec-overlay').hide()                        
                    }
                
                }
            })  

        }).catch(error=>{
            $('.invalid').show()
            $('.valid').hide()
            console.log(error.request.response)
            setCouponErr('Coupon not found')
            $('#sec-overlay').hide()

        })
    }


    function address(e){
        setViewEdit('edited')
    }

// accept term and condition checkbox
    function privacypolicy(e){
        e.preventDefault()
        props.history.push({pathname: `/payment`, query: {total, coupon_discount, coupon_pid,couponId,fee,days,note,item}})
        
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

    

   
    







    <main>

        <section>
          <div className="container">
            <div className="col-12">
                <div className="row">
                  <div className="col-12 text-center">
               
                  
                   <h2 className="mb-4">Checkout</h2>
               
                
               
                  </div>
                </div>
                <div className="row">







                  <div className="col-12 col-md-7 ">
                  {customer && customer.length !=0?
                  (
                    <div className="mb-5">
                        {viewEdit == "check_main" ?
                        (

                            <div >
                            <h6 className="font-weight-bold">Shipping & Billing Address</h6>

                            <div className="shipping-address-info">
                            <span className="fa fa-check"></span>
                            <div className="name">Name: {user.name}</div>
                            <div className="name">Email: {user.email}</div>
                            <div className="provience">Country: {customer.provience}</div>
                            <div className="district-state">District: {customer.district}</div>
                            <div className="city">City: {customer.city}</div>
                            <div className="local-address">Street: {customer.street}</div>
                            <div className="mobile">Contact No.: {customer.phone}</div>
                            
                        </div>
                            
                            <button className="btn btn-primary" onClick={(e)=>address(e)}>Change Shipping Address</button>
                           
                           
                
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
                    <textarea className="form-control form-control-sm mb-9 mb-md-0 font-size-xs mt-5" defaultValue={note} name="note" onChange={(e)=>setNote(e.target.value)} rows="5"
                    placeholder="Order Notes (optional)"></textarea>
                  </div>


                <div className="col-12 col-md-5 col-lg-4 offset-lg-1">
             
          
                 <h6 className="mb-7">Order Items ({item.length})</h6>
             
          
                 <hr className="my-7"/>
                 <ul className="list-group list-group-lg list-group-flush-y list-group-flush-x mb-7">
                 {item.map(item=>{
                    return(
                  <li className="list-group-item"  key={item.id}>
                   <div className="row align-items-center">

                    <div className="col-4">
             
                 
                    
                      <img src={item.product.image} alt="..." className="img-fluid"/>
                    
             
                    </div>
                    <div className="col">
             
              
                     <p className="mb-4 font-size-sm font-weight-bold">
                      <a className="text-body" href="product.html">{item.product.name}</a> <br/>
                      <span className="text-muted">Rs.{item.price}</span>
                     </p>
             
                   
                     <div className="font-size-sm text-muted">
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
                 <div className="" style={{marginBottom:"41px"}}>
                 <form className="d-flex" onSubmit={(e)=>addCoupon(e)} style={{height:"40px"}}>
                 <input className="form-control coupon-input" type="text" name="coupon" onChange={(e)=>setCoupon(e.target.value)} placeholder="enter coupon code" required style={{height:"40px !important"}}/>
                 <button className="btn btn-info ml-3 coupon-button">Submit</button>
                 </form>
                 <div className="valid-feedback valid font-weight-bold ">
                 <i className="fa fa-check"></i> Coupon Successfully applied
                 </div>
                 
                 <div className="invalid-feedback invalid font-weight-bold">
                 <i className="fa fa-times"></i> {couponErr}
                 </div>
                 </div>
                
             
                
                 <div className="card mb-9 bg-light">
                  <div className="card-body">
                   <ul className="list-group list-group-sm list-group-flush-y list-group-flush-x">
                    <li className="list-group-item d-flex">
                     <span>Subtotal</span> <span className="ml-auto font-size-sm">Rs.{total}</span>
                    </li>
                    <li className="list-group-item d-flex">
                    <span>Coupon Discount</span> <span className="ml-auto font-size-sm">Rs.{coupon_discount}</span>
                   </li>
                    <li className="list-group-item d-flex">
                     <span>Shipping</span> <span className="ml-auto font-size-sm">Rs.{fee}</span>
                    </li>
                    <li className="list-group-item d-flex font-size-lg font-weight-bold">
                     <span>Total</span> <span className="ml-auto">Rs.{total+fee-coupon_discount}</span>
                    </li>
                   </ul>
                  </div>
                 </div>
             
               
                 <p className="mb-7 font-size-xs text-gray-500">
                  Your personal data will be used to process your order, support
                  your experience throughout this website, and for other purposes
                  described in our privacy policy.
                 </p>
                <form onSubmit={(e)=>privacypolicy(e)}>
                 <div className="form-group">
                 
                 <input type="checkbox" required/> Accept terms and condition
                 </div>

              {customer && customer.length !=0?
                  (
                 <button type="submit" className="btn btn-block btn-primary" >Proceed Payment</button>
                  ):(
                      <button className="btn btn-block btn-primary" disabled>Please Fill the Address</button>
                    )}
             </form>
                </div>
               </div>
          </div>
        </div>
        </section>

    </main>







</div>

        )}



       
        </div>
    )
}

export default Checkout
