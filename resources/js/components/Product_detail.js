import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {AddContext} from './Cartcontext';
import UserImg from '../../../public/assets/images/user.png'
import Cookies from 'universal-cookie';
import {Link} from 'react-router-dom'
import {format} from 'timeago.js'
import { ceil, floor, indexOf } from 'lodash';


function Product_detail(props) {
  const [size,setSize]= useState(null)
  const[color,setColor] = useState([])
    const[product,setProduct]= useState([])
    const[category,setCategory]= useState([])
    const[brand, setBrand] = useState([])
    const[stock, setStock] = useState(1)
    const[price, setPrice] = useState(null)
    const[designer,setDesigner] = useState([])
    const[wishlist,setWishlist] = useState([])
    const[changewish,setChangewish] = useState(0)
    const[averageRating,setAverageRating] = useState(0)
    const[totalRating,setTotalRating] = useState(0)
    const[breadcrumb,setBreadcrumb] = useState([])
 

    const[images,setImages] = useState([])


    const[sku,setSku] = useState(null)
    const[specification,setSpecification] =useState([])


    const data = useContext(AddContext)

    const{setCart}= data
    const{cart} = data
    const{cartdetail} = data


   
    const[reviews,setReviews] = useState([])
    const loopStart = [1, 2, 3, 4, 5];

    const[originalPrice,setOriginalPrice] = useState(0)
    const[relatedproduct,setRelatedproduct] = useState([])
    const[branded,setBranded] = useState([])
    const[designers,setDesigners] = useState([])
    const[quantity,setQuantity] = useState(1)
    const [isOpen, setIsOpen] = useState(false);
    const[stockcart,setStockcart] = useState(0)
 

    const togglePopup = () => {
      setIsOpen(!isOpen);
      setQuantity(1);
    }


    useEffect(() => {
      var cartstock = 0
      cartdetail.map(cd=>{
        if(cd.product_id ==props.match.params.id){
          
          cartstock = cd.quantity
          setStockcart(cd.quantity)
        }
      })
      setStock(stock-cartstock)
    }, [props, cartdetail])

        

    useEffect(() => {
      $('#main-overlay').show();
      axios.get('/detail/'+props.match.params.id).then(response=>{
          setProduct(response.data.detail)
          setBrand(response.data.detail.brand)
          setDesigner(response.data.detail.designer)
          setCategory(response.data.detail.category)
          setSpecification(response.data.detail.specifications)
          setBreadcrumb(response.data.breadcrumb.reverse())
          setStock(response.data.detail.stock)
          setPrice(  (response.data.detail.price-(response.data.detail.price*response.data.detail.discount/100)).toFixed(0))
          setOriginalPrice(response.data.detail.price)
          setColor(response.data.detail.color)
          setSku(response.data.detail.sku)
          setRelatedproduct(response.data.related.slice(0,12))
          setBranded(response.data.branded.slice(0,6))
          setDesigners(response.data.designers)
          setImages([])
          if(response.data.detail.feature_images){
            var featureImages = response.data.detail.feature_images.split(',')
            setImages(featureImages)
          }
          $('#main-overlay').hide();
      }).catch(erro=>{
        console.log(erro.request.response)
        $('#main-overlay').hide();
      })


      axios.get('/review/'+props.match.params.id).then(response=>{
       
        setReviews(response.data)
        var total=0
        response.data.map(rating=>{
         total+= rating.rating 
        })
        setTotalRating(total)
        setAverageRating((total/response.data.length).toFixed(1))
      }).catch(error=>{
        console.log(error.request.response)
      })

    }, [props])

    function cartButton(pid,cid,seller_id){
      $('#sec-overlay').show()

      if(stock==0) {
        $('#sec-overlay').hide()
        alert("Product is out of stock.");
        return false;
      }


      if(!localStorage.getItem('token')) {
        //props.history.push('/signin')

        const cookies = new Cookies()
        var guest_id = cookies.get('guest_id')
        const data = {
            'product': pid,
            'category': cid,
            'size':size,
            'color':color.name,
            'price':price,
            'sku' :sku,
            'seller_id':seller_id,
            'guest_id': guest_id,
            'quantity': quantity,
        }
        axios.post('/guestaddtocart', data).then(response=>{
          $('#sec-overlay').hide()

          props.history.push('/cartitem')
        
        }).catch(error=>{
          $('#sec-overlay').hide()

          console.log(error.request.response)
        })



      } else {
        const data = {
          'product': pid,
          'category': cid,
          'size':size,
          'color':color.name,
          'price':price,
          'sku' :sku,
          'seller_id':seller_id,
          'quantity': quantity,
          
      }
      const config = {
          headers: {
            Authorization: 'Bearer'+localStorage.getItem('token')
          }
        }
      axios.post('/addtocart', data,config).then(response=>{
        $('#sec-overlay').hide()

        props.history.push('/cartitem')
      
      }).catch(error=>{
        $('#sec-overlay').hide()

        console.log(error.request.response)
      })

      }
        setCart(cart+1);
 
    }

    function buynow(pid,cid,seller_id){
      $('#sec-overlay').show()

      if(stock==0) {
        $('#sec-overlay').hide()
        alert("Product is out of stock.");
        return false;
      }


      if(!localStorage.getItem('token')) {
        //props.history.push('/signin')

        const cookies = new Cookies()
        var guest_id = cookies.get('guest_id')
        const data = {
            'product': pid,
            'category': cid,
            'size':size,
            'color':color.name,
            'price':price,
            'sku' :sku,
            'seller_id':seller_id,
            'guest_id': guest_id,
            'quantity': quantity,
        }
        axios.post('/guestaddtocart', data).then(response=>{
          $('#sec-overlay').hide()

          props.history.push('/signin/checkout')
        
        }).catch(error=>{
          $('#sec-overlay').hide()

          console.log(error.request.response)
        })



      } else {
        const data = {
          'product': pid,
          'category': cid,
          'size':size,
          'color':color.name,
          'price':price,
          'sku' :sku,
          'seller_id':seller_id,
          'quantity': quantity
      }
      const config = {
          headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
          }
        }
      axios.post('/addtocart', data,config).then(response=>{
        $('#sec-overlay').hide()

        props.history.push('/checkout')
      
      }).catch(error=>{
        $('#sec-overlay').hide()

        console.log(error.request.response)
      })

      }
        setCart(cart+1);
    }

    function pivotsize(e){
      $('#sec-overlay').show()

      if(e.target.value) {
        var index = e.target.selectedIndex;
        var optionElement = e.target.childNodes[index]
        var size =  optionElement.getAttribute('size_id');
        var sku =  optionElement.getAttribute('sku');
        var price =  optionElement.getAttribute('price');

        price = parseFloat(price).toFixed(0)
        var stock =  optionElement.getAttribute('stock');
        var orgprice = optionElement.getAttribute('orgprice');
        setSize(size);
        setSku(sku);
        setPrice(price);
        setStock(stock-stockcart); 
        setOriginalPrice(orgprice);
      }
      $('#sec-overlay').hide()
 
    }



function renderStocks(stock){
  const list = []
  for(var i =1; i<=stock; i++) {
      list.push(<option value={i}>{i}</option>);
  }
  return list;
}


function renderStars(rating){

  const list = []
  var y = rating.toString()
  var x = y.split('.')
  for(var i =1; i<=x[0]; i++) {
      list.push(<span><i class='fa fa-star'></i></span>)
  }

  

  if(x.length>1 && x[1]!=0){
  
    list.push(<span> <i class='fa fa-star-half'></i></span>)
  }




  return list;
}


useEffect(() => {

  if(localStorage.getItem('token')){
      const config = {
          headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
          }
        }
    axios.get('/wishlist',config).then(response=>{
        setWishlist(response.data)
         $('#sec-overlay').hide()

    })
  }
  
}, [changewish])

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

      <section className="product-detail-wrap">
          <div className="container">



          <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><Link to="/">Home</Link></li>
              {breadcrumb.map(category=>{
                  return(
                      category.parent_id && category.parent_id!=0?(
                        category.slug.includes('boutique')?(
                          <li class="breadcrumb-item text-capitalize" key={category.id}><Link to={`/boutique/${category.slug}`}>{category.name}</Link></li>
                        ):(
                          <li class="breadcrumb-item text-capitalize" key={category.id}><Link to={`/category/${category.slug}`}>{category.name}</Link></li>
                        )
                        
                      ):(
                          <li class="breadcrumb-item text-capitalize" key={category.id}><Link to={`/${category.slug}`}>{category.name}</Link></li>
                      )
                     
                  )
              })}
          </ol>
          </nav>


              <div className="row">
                  <div className="col-md-7">
                      <div id="detailSlider" className="carousel slide" data-ride="carousel">
                        <div className="row">
                            <div className="col-3 col-md-2">
                                <ol className="carousel-indicators">
                                <li data-target="#detailSlider" data-slide-to="0" className="active">
                                <img src={product.image}/>
                              </li>
                                {images.map((image,index)=>{
                                  return(
                                    <li data-target="#detailSlider" data-slide-to={index+1} className="active" key={index}>
                                      <img src={image}/>
                                    </li>
                                  )
                                })}
                                
                                 
                                </ol>
                            </div>
                            <div className="col-9 col-md-10 pl-xs-0">
                                <div className="carousel-inner">
                                <div className="carousel-item active">
                                <img src={product.image} className="d-block w-100" alt="..."/>
                              </div>
                                {images.map((image, index)=>{
                                  return(
                                    <div className="carousel-item">
                                    <img src={image} className="d-block w-100" alt="..." key={index}/>
                                  </div>
                                    
                                  )
                                })}
                                  
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
                  <div className="col-md-5">
                    <div className="product-details pt-3">
                    {brand ?(
                      <Link to={`/brand/${brand.slug}`}>
                      <h2 className="product-title text-uppercase pro">{brand.name}</h2>
                      </Link>
                      ):
                      ( 
                        <Link to={`/designer/${designer.slug}`}>
                      <h2 className="product-title text-uppercase pro">{designer.name}</h2>
                      </Link>
                      )}

                      <span className="product-shrt-desc">{product.name}</span>
                      

                      <h3 className="product-price proprice">Rs.{price} <span>VAT Included</span></h3>
                      {product.discount ? (
                        <div className="discount">
                        <del>Rs.{originalPrice}</del>
                        {product.discount ===0 ?(null):(
                          <span className="d-inline pl-2 discount-price">{product.discount}% off</span>
                          )}
                    </div>
                      )
                    :
                  (null)}
                     

                      {product.sizes && Object.keys(product.sizes).length !== 0 ?(
                      <div className="select-size">
                          <form>
                              <div className="form-group mt-4">
                                  <div className="row">
                                      <div className="col-sm-3">
                                        <label htmlFor="" className="pt-2 font-bold">SIZE</label>
                                      </div>
                                        <div className="col-sm-9">                                
                                        <select className="form-control" id="" onChange={(e)=>pivotsize(e)}>
                                        <option value="">Select size</option>
                                          {product.sizes.map(sizes=>{
                                            return(
                                            <option key={sizes.id} size_id={sizes.id} orgprice={sizes.pivot.price} sku={sizes.pivot.sku} price={ sizes.pivot.price-(sizes.pivot.price*product.discount/100)} stock={sizes.pivot.stock} value={sizes.id}>{sizes.name}</option>
                                              )
                                            })}
                                         
                                        </select>
                                      </div>                  
                                 </div>
                              </div>
                          </form>
                      </div>)
                      :(null)}

                  {stock <=0 ?(
                    <div className="add-to-cart-link mt-4 mb-4">
                      <div className="btn btn-primary w-100" disabled >product out of stock</div>
                    </div>
                    )
                      : size ?
                    (
                        <div className="add-to-cart-link mt-4 mb-4">
                          <div className="btn btn-primary w-100" onClick={()=>cartButton(product.id,product.category_id,product.seller_id)}>Add to Cart</div>
                          <div className=" btn-buynow w-100 mt-3" onClick= {()=>buynow(product.id,product.category_id,product.seller_id)}>BUY NOW</div>
                        </div>
                    )
                      : product.sizes && Object.keys(product.sizes).length === 0 ?
                    (
                      <div className="add-to-cart-link mt-4 mb-4">
                      <div className="btn btn-primary w-100" onClick={()=>cartButton(product.id,product.category_id,product.seller_id)}>Add to cart</div>
                      <div className="btn-buynow w-100 mt-3" onClick= {()=>buynow(product.id,product.category_id,product.seller_id)}>BUY NOW</div>
                      </div>
                    ):
                    (
                      <div className="add-to-cart-link mt-4 mb-4">
                      <div className="btn btn-primary w-100"  disabled>Select Size</div>
                      </div>
                    )
                   }


                      <div className="product-collapse">
                          <div id="accordion" className="accordion">
                              <div className="card mb-0 border-0 rounded-0">
                                  <div className="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                                      <a className="card-title">
                                          Product Highlights
                                      </a>
                                  </div>
                                  <div id="collapseOne" className="card-body collapse " data-parent="#accordion" >
                                  <ul>
                                  {product.highlight ?(
                                   <p className="post__content" dangerouslySetInnerHTML={{__html: product.highlight}}></p>

                                  ):
                                (null)}
                                </ul>
                                </div>
                                  
                                  
                                 
                                  <div className="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                      <a className="card-title">
                                        Details
                                      </a>
                                  </div>
                                  {product.specifications ?(
                                    <div id="collapseTwo" className="card-body collapse" data-parent="#accordion" >
                                    <ul>

                                  {product.description ?(
                                   <p className="post__content" dangerouslySetInnerHTML={{__html: product.description}}></p>

                                  ):
                                (null)}

                                    {
                                      // {product.specifications.map(specification=>{
                                      //   return(
                                      //     <li key={specification.id}>{specification.attribute_name}: {specification.value}</li>
  
                                      //   )
                                      // })}
                                    }
                                    

                                        <li>Color: {color.name}</li>
                                    </ul>
                                </div>
                                  ):
                                (null)}
                                 

                                  {/* <div className="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                      <a className="card-title">
                                        Delivery & Returns
                                      </a>
                                  </div>


                                  <div id="collapseThree" className="collapse" data-parent="#accordion" >
                                      <div className="card-body">
                                          <p>You have 14 days to place a FREE returns request if you change your mind. Learn more about our returns process here.</p>
                                      </div>
                                  </div> */}
                              </div>
                          </div>
                      </div>

                      

                    </div>
                  </div>
                  
              </div>
              <section className="home-products mb-100 mt-100">
              <div className="container">
                  <div className="section-title uppercase">
                  <div className="headerpd header-titlepd">Similar Products</div>
                  </div>
                  <div className="home-products-list">
                      <div className="row">
                      {relatedproduct.map(newproduct=>{
                          return(
                              <div className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2" key={newproduct.id}>
                              <div className="product-widget">
                                  <div className="product-thumb">
                                  <div className="wishlist">
                                           
                                  {wishlist.find(wish=> wish.product_id === newproduct.id) ?
                                      (
                                       
                                          <button className="wishlist-button" onClick={()=>wishButton(newproduct.id,newproduct.category_id,'remove')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                          
                                          <svg className="wishlist-icon active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                      <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                              </svg>
                              </button>)
                                      :
                                      
                                      ( 
                                          
                                          <button className="wishlist-button" onClick={()=>wishButton(newproduct.id,newproduct.category_id,'add')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                          <svg className="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                      <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                          </svg>
                          </button>
                          )
                                      }
                                     
                         
                          </div>


                                    <Link to={`/product-detail/${newproduct.id}`}>
                                      <img src={newproduct.image}/>
                                    </Link>
                                  </div>
                                  <div className="product-info my-30">
                                  {newproduct.brand ? (
                                      <div className="d-block product-brand">{newproduct.brand.name}</div>
          
                                  )
                              :
                              ( <div className="d-block product-brand">{newproduct.designer.name}</div>)}
                                      <Link to={`/product-detail/${newproduct.id}`}  className="d-block product-name">{newproduct.name}</Link>
                                      <span className="d-block product-price">
                                      <span className="reduction">Rs. {(newproduct.price-(newproduct.price*newproduct.discount/100)).toFixed(0)}</span>
                                       &nbsp;&nbsp;&nbsp;
                                      {newproduct.discount ===0 ?(null):(

                                          <span className="pre_reduction">Rs.{newproduct.price}</span>
                                          )}
                                                     
                                                      {newproduct.discount ===0 ?(null):(
                                                          <span className="reduction_tag">{newproduct.discount}% OFF</span>
                                                      )}                                            
                                                 </span>
                                  </div>
                              </div>
                              </div>
          
                          )
                      })}
          
                         
                      </div>
                  </div>
              </div>
              </section>
              
              
              <section className="home-products mb-100">
              <div className="container">
                  <div className="section-title uppercase">
                 { designers && Object.keys(designers).length !== 0 ? (
                  <div>
                  {designers.slice(0,1).map(brand=>{
                    return(
                      <div key={brand.id}>
                      {brand.designer ?(
                        <div className="headerpd header-titlepd">More From {brand.designer.name} </div>
                      ):(null)}
                        
                  </div>
                  
                    )
                  })}
                   </div>
                 ):(
                  <div>
                  {branded.slice(0,1).map(brand=>{
                   return(
                     <div key={brand.id}>
                     {brand.brand ?(
                       <div className="headerpd header-titlepd">More From {brand.brand.name} </div>
                     ):(null)}
                       
                 </div>
                    )
                  })}
                  </div>
                 )
                   
                 }
                  
                  </div>
                 
                  { branded && Object.keys(branded).length === 0? (null):
                    
                 (
                    <div className="home-products-list">
                    <div className="row">
                    
                    {branded.map(brand=>{
                        return(
                            <div className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2" key={brand.id}>
                            <div className="product-widget">
                                <div className="product-thumb">
                                <div className="wishlist">
                                           
                                {wishlist.find(wish=> wish.product_id === brand.id) ?
                                    (
                                     
                                        <button className="wishlist-button" onClick={()=>wishButton(brand.id,brand.category_id,'remove')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                        
                                        <svg className="wishlist-icon active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                            </svg>
                            </button>)
                                    :
                                    
                                    ( 
                                        
                                        <button className="wishlist-button" onClick={()=>wishButton(brand.id,brand.category_id,'add')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                        <svg className="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                        </svg>
                        </button>
                        )
                                    }
                                   
                       
                        </div>

                                  <Link to={`/product-detail/${brand.id}`}>
                                    <img src={brand.image}/>
                                  </Link>
                                </div>
                                <div className="product-info my-30">
                                {brand.brand ? (
                                  <div className="d-block product-brand">{brand.brand.name}</div>        
                                )
                            :
                            (null)}
                            {brand.designer? (
                              <div className="d-block product-brand">{brand.designer.name}</div>        
                            )
                        :
                        (null)}
                            


                                    <Link to={`/product-detail/${brand.id}`}  className="d-block product-name">{brand.name}</Link>
                                    <span className="d-block product-price">
                                    <span className="reduction">Rs. {(brand.price-(brand.price*brand.discount/100)).toFixed(0)}</span>
                                     &nbsp;&nbsp;&nbsp;
                                    {brand.discount ===0 ?(null):(
                                        <span className="pre_reduction">Rs.{brand.price}</span>
                                        )}
                                                    
                                                    {brand.discount ===0 ?(null):(
                                                        <span className="reduction_tag">{brand.discount}% OFF</span>
                                                    )}                                            
                                               </span>
                                </div>
                            </div>
                            </div>
        
                        )
                    })}
        
                       
                    </div>
                </div>
                  )}
                  
              </div>
              </section>


              <section class="reviews-section mb-100">
        <div class="container">
          <div class="reviews-wrapper">
            
            <div class="section-title uppercase">

                <h2 class="mt-0 mb-20">Ratings & Reviews of Product Name</h2>
            </div>
             { reviews && Object.keys(reviews).length === 0 ?(
              <div className="productRatting">
                              <svg width="86" height="86" viewBox="0 0 86 86" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="43" cy="43" r="43" fill="#F1F1F1"></circle><path d="M26 33C26 31.3431 27.3431 30 29 30H57C58.6569 30 60 31.3431 60 33V50.7975C60 52.4544 58.6569 53.7975 57 53.7975H40.3249C40.1133 53.7975 39.9072 53.8646 39.7362 53.9892L34.2292 58V54.7975C34.2292 54.2452 33.7815 53.7975 33.2292 53.7975H29C27.3431 53.7975 26 52.4544 26 50.7975V33Z" stroke="#282D31" strokeLinecap="round" strokeLinejoin="round"></path><path d="M46.1913 39.9568L51.96 39.957" stroke="#D0D0D0" strokeLinecap="round"></path><path d="M46.1913 43.2885L49.0757 43.2885" stroke="#D0D0D0" strokeLinecap="round"></path><path d="M36.7852 43.9999L41.5399 39.2455" stroke="#282D31" strokeLinecap="round"></path><path d="M41.5404 43.9999L36.7857 39.2455" stroke="#282D31" strokeLinecap="round"></path></svg>
                                                    <b>NO REVIEWS YET</b>
                                </div>
             ):(


              <div class="row">
              <div class="col-md-3">
                  <div class="summary">
                      <div class="score">
                          <span class="score-average">{averageRating}</span><span class="score-max">/5</span>
                      </div>
                      <div class="p-star">
                          {renderStars(averageRating)}
                      </div>
                      <div class="count mt-4">{averageRating} Ratings</div>
                  </div>
              </div>
              <div class="col-md-9">
                <div class="rating-feedback">
                    <h3 class="pb-2 mb-5">All Reviews</h3>
                    {reviews.map(review=>{
                      return(
                    <div class="reviews-box" key={review.id}>
                        <div class="user pb-2 mb-4">
                            <span class="user-img">
                            
                                <img src={UserImg}/>
                            </span>
                            <h5 class="user-name mt-0 text-capitalize">{review.user.name}</h5>
                            <div class="review-star">
                            {loopStart.map(loop=>{
                                                 return(
                                                   <span key={loop}>
                                                   {
                                                     review.rating >= loop ?
                                                   (<i key={loop} className='fa fa-star starchecked'></i>)
                                                   :
                                                   (null)
                                                   }
                                                 </span>)
                                               })}
                              {review.rating==0 ?(null):(
                                <span>{review.rating}</span>
                              )}
                             
                            </div>
                            <span class="review-date">{format(review.created_at)}</span>
                        </div>
                        <div class="user-comment">
                          <p>
                            {review.comment}
                          </p>
                        </div>
                        {review.image && review.image!=' '?(
                          <div class="review-img">
                          {JSON.parse(review.image).map(image=>{
                            return(
                              <img src={image}/>
                            )
                          })}
                 
                      </div>
                        ):(null)}
                       
                    </div>
                    )
                  })}
                  </div>
                
                </div>
              </div>




             )}

            
                </div>
                </div>
              </section>














{
  // <div className="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefour">
  //                   <a className="card-title">
  //                   RATINGS & REVIEWS
  //                   </a>
  //               </div>
  //               <div id="collapsefour" className="collapse" data-parent="#accordion" >
  //                   <div className="card-body">


                    

  //                   { reviews && Object.keys(reviews).length === 0 ?(
  //                     <div className="productRatting">
  //                     <svg width="86" height="86" viewBox="0 0 86 86" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="43" cy="43" r="43" fill="#F1F1F1"></circle><path d="M26 33C26 31.3431 27.3431 30 29 30H57C58.6569 30 60 31.3431 60 33V50.7975C60 52.4544 58.6569 53.7975 57 53.7975H40.3249C40.1133 53.7975 39.9072 53.8646 39.7362 53.9892L34.2292 58V54.7975C34.2292 54.2452 33.7815 53.7975 33.2292 53.7975H29C27.3431 53.7975 26 52.4544 26 50.7975V33Z" stroke="#282D31" strokeLinecap="round" strokeLinejoin="round"></path><path d="M46.1913 39.9568L51.96 39.957" stroke="#D0D0D0" strokeLinecap="round"></path><path d="M46.1913 43.2885L49.0757 43.2885" stroke="#D0D0D0" strokeLinecap="round"></path><path d="M36.7852 43.9999L41.5399 39.2455" stroke="#282D31" strokeLinecap="round"></path><path d="M41.5404 43.9999L36.7857 39.2455" stroke="#282D31" strokeLinecap="round"></path></svg>
  //                                         <b>NO REVIEWS YET</b>
  //                     </div>

  //                   ):

  //                 (
  //                   <div className="product-reviews">


  //                   {reviews.map(review=>{
  //              return(
  //                <div className="product-review-main" key={review.id}>
               
  //                  <div className="product-review-rating">
  //                  {loopStart.map(loop=>{
  //                    return(
  //                      <span key={loop}>
  //                      {
  //                        review.rating >= loop ?
  //                      (<i key={loop} className='fa fa-star starchecked'></i>)
  //                      :
  //                      (<i  className='fa fa-star'></i>)
  //                      }
  //                    </span>)
  //                  })}
                     
  //                  <span className ="review-date">{format(review.created_at)}</span>
  //                  </div>
  //                  <div className="product-review-user">
  //                    By {review.user.name}
  //                  </div>
  //                  <div className="product-review-detail">
  //                      {review.comment}
  //                  </div>
                  
                   
  //                </div>
  //              )
  //                   })}
                       
  //                  </div>

  //                 )}
                  

                    



  //                   </div>
  //               </div>


}
              
     </div>     
      </section>
    </main>
    )
}

export default Product_detail

