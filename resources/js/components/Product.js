import React,{useEffect,useState} from 'react'
import axios from 'axios';
import {Link,Redirect} from 'react-router-dom'




function Product() {
    const[product,setProduct]=useState([])
    const[category,setCategory]= useState([])
  
  

    useEffect(() => {
       axios.get('/product').then(response=>{
           console.log(response.data.products)
           setProduct(response.data.products)

       })
    }, [])

    function wishButton(pid,cid){
       
        const data = {
            'product': pid,
            'category': cid,
    
        }
        console.log(data)

        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
        axios.post('/addtowish',data,config).then(response=>{
            console.log(response.data)
           
        })
    }



    return (
        <div>
        
            {product.map(product=>{
                return(
                    <div key={product.id}>
                    <div className="col-sm">
                        <div className="card card-cascade card-ecommerce wider shadow mb-5 ">
                         
                            <div className="view view-cascade overlay text-center"> <img className="card-img-top" src={product.image} style={{width:'150px'}} alt=""/> <a>
                                    <div className="mask rgba-white-slight"></div>
                                </a> </div>
                       
                            <div className="card-body card-body-cascade text-center">
                              
                                <h4 className="card-title"><strong><Link to={`/product-detail/${product.id}`}>{product.name}</Link></strong></h4>
                              
                                <p className="card-text">{product.detail} </p>
                                <p className="price">{product.price}</p> 

                                <ul className="row rating">
                                    <li><i className="fa fa-star"></i></li>
                                    <li><i className="fa fa-star"></i></li>
                                    <li><i className="fa fa-star"></i></li>
                                    <li><i className="fa fa-star"></i></li>
                                    <li><i className="fa fa-star"></i></li>
                                </ul>
                               
                                    <button className="btn btn-light" onClick={()=>wishButton(product.id,product.category_id)}>add to wishlist</button>

                                
                             
                                
                            </div>
                        </div>
                    </div>

                    {product.name}
                    {product.category.name}
                 </div>
                )
                
            })}
        </div>
    )
}

export default Product

