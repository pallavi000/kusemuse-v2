import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {Link, Redirect} from 'react-router-dom';
import UserSidebar from '../../global/UserSidebar';
import CartPopup from  '../CartPopup';


function Item(props) {
    const[product,setProduct]=useState([])
    const[wishitem,setWishitem]= useState([])
    const[total,setTotal]= useState(0)
    const [isOpen, setIsOpen] = useState(false);
    const[product_id,setProduct_id]= useState(null)
    const[changewish,setChangewish] = useState(0)
    const[user,setUser] = useState([])
   

    useEffect(() => {
        $('#sec-overlay').show();
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
      axios.get('/wishlist',config).then(response=>{
          console.log(response.data)
          setWishitem(response.data)
          $('#sec-overlay').hide()  

      })

    }, [changewish])


    



    // useEffect(() => {
    //     if(localStorage.getItem('token')){
    //         const config = {
    //             headers: {
    //               Authorization: 'Bearer '+localStorage.getItem('token')
    //             }
    //           }
    //         axios.get('/wishcount',config).then(item=>{
    //             console.log(item.data)
    //             setWishitem(item.data)
    //             setTotal(item.data)
    //             $('#sec-overlay').hide()  
              
    
    //         }).catch(err=>{
    //             console.log(err.request.item)
    //         })
    //     }else{
    //         const cookies = new Cookies()
    //         var guest_id = cookies.get('guest_id') 
    
    //         axios.get('/guestcount/'+guest_id).then(item=>{
    //             setWishitem(item.data)
    //             setTotal(item.data)
    //             $('#sec-overlay').hide()  
              
    //         }).catch(err=>{
    //             console.log(err.request.item)
    //         })
    //     }
       
    // }, [])

    function togglePopup(pid){
        setIsOpen(!isOpen);
        setProduct_id(pid)
    }

    function wishButton(pid,cid,action){
        $('#sec-overlay').show()  
        const data = {
            'product': pid,
            'category': cid,
            'action': action,
        }
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      } 
    
      if(localStorage.getItem('token')){
        axios.post('/addtowish',data,config).then(response=>{
            setChangewish(Math.random)
        })
      }else{
          props.history.push('/signin')
      } 
    }


    return (

        <main>
      <section class="dashboard-ui position-relative">
          <div class="container">
              <div class="row">
                  <div class="col-md-4 col-lg-3">
                      <UserSidebar/>
                  </div>
                  <div class="col-md-8 col-lg-9">
                      <div class="dashboard-panel">
                          <div class="panel-widget p-5">
                              <div class="font-weight-bold mb-3 text-uppercase">Wishlist</div>                              
                              <div class="orderlistcards wishlist-card">

                              {wishitem.map(item=>{
                                      return(
                                          item.product?(

                                         
                                  <div class="wishlist-card-block pb-4 mb-4 border-bottom">
                                      <div class="row">
                                          <div class="col-3 col-md-3">
                                          <div className="wishlist">
                       
                                
                                                                      <button className="wishlist-button" onClick={()=>wishButton(item.product_id,item.category_id,'remove')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                                                     <svg className="wishlist-icon active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                  <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                      </svg>
                                                      </button>
                                                    
                                                                 
                                                     
                                                   </div>
                                              <div class="product-image">
                                              <Link to={`/product-detail/${item.product_id}`}>
                                             <img src={item.product.image} className="img-fluid"/>
                                             </Link>
                                              </div>
                                          </div>
                                          <div class="col-9 col-md-9">
                                              <div class="order-product-info">
                                                  <strong>Goldstar</strong>
                                                  <p>{item.product.name}</p>
            
                                                  <div class="p-price">Rs. {item.product.price}</div>
                                                  <Link to={`/product-detail/${item.product.id}`}className="btn btn-primary" >View Product</Link>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  ):(null)
                                   )
                                  })}
                                  </div>
                          </div>

                      </div>
                  </div>
              </div>
        </div>
      </section>
    </main>

    )
        {
    //     <div>
    //     {isOpen ?
    //         (
    //             <div className="popup-box">
    //                 <div className="box cart">
    //                     <span className="close-icon" onClick={()=>togglePopup(null)}><i className="fa fa-times"></i></span>
    //                     <div className="popup-detail">
    //                     {product_id ?(
    //                         <CartPopup product_id={product_id} toggle={togglePopup}/>
    //                     ):(null)}
                           
    //                     </div>
    //                 </div>
    //             </div>
    //         )
    //         :
    //         (null)
    //         }

    //     <div className="cart_section">
    //     <div className="container-fluid">
    //         <div className="row">
    //             <div className="col-lg-10 offset-lg-1">
    //                 <div className="cart_container">

    //                 <div className="wishlist-header" id=""><span className="wishlist-title">Wishlist</span><span className="wishlist-count">({wishitem.length} items)</span></div>

    // <div className="home-products-list">
    //             <div className="row">
    //             {wishitem.map(item=>{
    //                 return(
    //                 <div className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2" key={item.id}>
    //                 <div className="product-widget">
    //                     <div className="product-thumb position-relative">




    //                     <div className="wishlist">
                       
                                
    //                             <button className="wishlist-button" onClick={()=>wishButton(item.product_id,item.category_id,'remove')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
    //                             <svg className="wishlist-icon active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    //                         <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
    //             </svg>
    //             </button>
              
                           
               
    //             </div>





    //                       <Link to={`/product-detail/${item.product_id}`}>
    //                         <img src={item.product.image}/>
    //                       </Link>
                         
    //                     </div>
    //                     <div className="product-info my-3">
    //                     <Link to={`/product-detail/${item.product_id}`} className="d-block product-name  font-weight-bold">{item.product.name}</Link>
                      
    //                         <span className="d-block product-price">
    //                         <span className="reduction">Rs. {(item.product.price-(item.product.price*item.product.discount/100)).toFixed(0)}</span>
    //                         &nbsp;&nbsp;&nbsp;
    //                         <span className="pre_reduction">Rs.{item.product.price}</span>
                           
    //                         <span className="reduction_tag">{item.product.discount}% OFF</span>
                        
    //                                 </span>
    //                             </div>
    //                         </div>
    //                     </div>
    //                     )
    //                 })}
                    
    //                 </div>
    //             </div>
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    // </div>
    //     </div>
                }
    
}

export default Item
