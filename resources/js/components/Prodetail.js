import axios from 'axios'
import { map, size } from 'lodash';
import { comment } from 'postcss';
import React,{useEffect,useState,useContext} from 'react'
import {AddContext} from './Cartcontext';

function Product_detail(props) {
  const [size,setSize]= useState(null)
  const[color,setColor] = useState([])
    const[product,setProduct]= useState([])
    const[category,setCategory]= useState([])
    const[brand, setBrand] = useState([])
    const[stock, setStock] = useState(1)
    const[price, setPrice] = useState(null)
    const[order,setOrder] = useState([])
    const[images,setImages] = useState([])
    const[sku,setSku] = useState(null)
    const[specification,setSpecification] =useState([])
    const data = useContext(AddContext)
    const{setCart}= data
    const{cart} = data
    const loopStart = [1, 2, 3, 4, 5];

    const [com, setCom] = useState('')
    const[reviews,setReviews] = useState([])
    const[star,setStar] = useState(0)


    useEffect(() => {
      axios.get('/detail/'+props.match.params.id).then(response=>{
        console.log(response.data)
          setProduct(response.data)
          setBrand(response.data.brand)
          setCategory(response.data.category)
          setSpecification(response.data.specifications)
          setStock(response.data.stock)
          setPrice(response.data.price)
          setColor(response.data.color)
          setSku(response.data.sku)
          if(response.data.feature_images){
            var featureImages = response.data.feature_images.split(',')
            setImages(featureImages)
          }
         
          
      }).catch(erro=>{
        props.history.push('/')
      })
    }, [])

    function cartButton(pid,cid,seller_id){
      if(!localStorage.getItem('token')) {
        props.history.push('/signin')
      }
        setCart(cart+1);
        const data = {
            'product': pid,
            'category': cid,
            'size':size,
            'color':color.name,
            'price':price,
            'sku' :sku,
            'seller_id':seller_id
            
        }
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
        axios.post('/addtocart', data,config).then(response=>{
          props.history.push('/cartitem')
        
        }).catch(error=>{
          console.log(error.request.response)
        })
    }

    function pivotsize(size,sku,price,stock){
      setSize(size);
      setSku(sku);
      setPrice(price);
      setStock(stock); 
    }

    useEffect(() => {
      const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
      if(localStorage.getItem('token')){
        axios.get('/buyers/'+props.match.params.id, config).then(response=>{
          console.log(response.data)
          setOrder(response.data)
        })
      }
          
        
      axios.get('/review/'+props.match.params.id).then(response=>{
        //console.log(response.data)
        setReviews(response.data)
      }).catch(error=>{
        console.log(error.request.response)
      })

    }, [props])


    function comment(e){
      e.preventDefault();
      const data={
        'comment': com,
        'product':props.match.params.id, 
        'rating':star,
      }

      const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
      axios.post('/comment',data,config).then(response=>{
        props.history.push('/product-detail/'+props.match.params.id)
        $('textarea[name="comment"]').val('')
      }).catch(error=>{
        console.log(error.request.response)
      })
    }

    $(document).ready(function(){

      $("input[type='radio']").click(function(){
      var sim = $("input[type='radio']:checked").val();
      setStar(sim)
      // alert(sim);
      if (sim<3) { $('.myratings').css('color','red'); $(".myratings").text(sim); }else{ $('.myratings').css('color','green'); $(".myratings").text(sim); } }); });

   
    return (
        <div className="container">
        <div className= "row">
        <div className="col-md-6 py-5">
        <div className="cart_item_image "><img src={product.image} alt="" className="img-fluid" /></div>
          <div className="images">
          <h3>Feature images</h3>
          {images.map((image, index)=>{
            return(
              <img src = {image} className=" img-fluid w-25" key={index}/>
            )
          })}
          </div>
        
        </div>
        <div className="col-md-6 py-5">
        <div className="cart_item_title">Name:{product.name}</div>
        <div className="cart_item_title">{product.description}</div>
        <div className="cart_item_title">Discount:{product.discount}</div>
        <div className="cart_item_title">Price:Rs.{price}</div>
        <div className="cart_item_title">Stock:{stock}</div>
        <div className="cart_item_title">SKU:{sku}</div>

        {color ?(
          <div className="cart_item_title">Color:{color.name}</div>
        )
      :
    (null)}

        <div className="cart_item_title">Category:{category.name}</div>
        {brand ? (
          <div className="cart_item_title">Brand:{brand.name}</div>

        )
      :
       (null)}
        <br/>
        <br/>
        <br/>
        <div className="card_item_title "><strong>Info & Care</strong></div>
        {specification.map(spec=>{
          return(
            <div key={spec.id}>
            {spec.attribute_name} &nbsp; : {spec.value}
            </div>
          )
        })}
        

        {product.sizes ? (
          <div>
          Available sizes:
          {product.sizes.map(sizes=>{
            return(
                <div key={sizes.id}>
                <ul className="row " >
                <button className="btn btn-outline-secondary" onClick={()=>pivotsize(sizes.id,sizes.pivot.sku, sizes.pivot.price-(sizes.pivot.price*product.discount/100), sizes.pivot.stock)}> {sizes.name} <br/> RS.{sizes.pivot.price-(sizes.pivot.price*product.discount/100)} </button>
                 </ul>
                </div>
            );
        })}

          </div>
        ):
      (null)
      }
        { stock<=0 ?
          (
            <div>
            <button className="btn btn-outline-danger" onClick={() => cartButton(product.id,product.category_id,product.seller_id)} disabled>
            <p>Product Out OF Stock</p>
             </button>
            </div>
          )
          : size ?
        (
          <div>
          <button className="btn btn-primary" onClick={() => cartButton(product.id,product.category_id,product.seller_id)}>
          <p>ADD TO CART</p>
           </button>
          </div>
        )
        : product.sizes && Object.keys(product.sizes).length === 0 ?
        (
          <div>
          <button className="btn btn-primary" onClick={() => cartButton(product.id,product.category_id,product.seller_id)}>
          <p>ADD TO CART</p>
           </button>
          </div>
        )
        :
        ( <button className="btn btn-outline-danger" onClick={() => cartButton(product.id,product.category_id,product.seller_id)} disabled>
        <p>Product Not Available</p>
         </button>)
        }

         <br/>
         
        {order.id ? (
      <form onSubmit ={(e)=>comment(e)} >
          <div className="form-group">
            <div className="rating-star py-4" >
            <fieldset className="rating"> 
            <input type="radio" id="star5" name="rating" value="5" /><label className="full" htmlFor="star5" title="Awesome - 5 stars"></label><input type="radio" id="star4" name="rating" value="4" /><label className="full" htmlFor="star4" title="Pretty good - 4 stars"></label>  <input type="radio" id="star3" name="rating" value="3" /><label className="full" htmlFor="star3" title="Meh - 3 stars"></label> <input type="radio" id="star2" name="rating" value="2" /><label className="full" htmlFor="star2" title="Kinda bad - 2 stars"></label> <input type="radio" id="star1" name="rating" value="1" /><label className="full" htmlFor="star1" title="Sucks big time - 1 star"></label> </fieldset>
          </div>
        </div>
        <br/>
        <div className="form-group">
          <label className="w-100" htmlFor="comment ">Comment:</label>
          <textarea className="form-control" rows="2" name="comment" onChange={(e)=> setCom(e.target.value)} placeholder ="anything about this product"></textarea>
        </div>
          <button className="btn btn-primary" >Submit</button>
        

      </form>
        )
      :
      (null)
    }
    
       </div>
        </div>
        {reviews ? (
          <div className="product-reviews">
          {reviews.map(review=>{
     return(
       <div className="product-review-main" key={review.id}>
     
         <div className="product-review-rating">
         {loopStart.map(loop=>{
           return(
             <span key={loop}>
             {
               review.rating >= loop ?
             (<i key={loop} className='fa fa-star starchecked'></i>)
             :
             (<i  className='fa fa-star'></i>)
             }
           </span>)
         })}
           
         <span className ="review-date">{review.created_at}</span>
         </div>
         <div className="product-review-user">
           By {review.user.name}
         </div>
         <div className="product-review-detail">
             {review.comment}
         </div>
        
         
       </div>
     )
          })}
             
               </div>
        )
      :
    (null)}
       
          
        </div>
    )
}

export default Product_detail

