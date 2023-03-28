import React, { useEffect, useState,useContext} from 'react'
import axios from 'axios'
import {Link} from 'react-router-dom'
import CartPopup from '../CartPopup'


function CategoryPage(props) {
const[catproduct,SetCatproduct] = useState([])
const[newcategory,setNewcategory] = useState([])
const[category_brand,setCategory_brand] = useState([])
const[categories,setCategories] = useState([])
const[categoryblock,setCategoryblock] = useState([])
const[banner,setBanner]  = useState([])
const[featurepost,setFeaturepost] = useState([])
const[wishlist,setWishlist] = useState([])
const[changewish,setChangewish] = useState(0)



const [isOpen, setIsOpen] = useState(false);
const[product_id,setProduct_id]= useState(null)
 

    function togglePopup(pid){
        setIsOpen(!isOpen);
        setProduct_id(pid)
    }



    useEffect(() => {
        $('#main-overlay').show();
    axios.get('/categorypage/'+props.match.params.slug).then(response=>{
        setCategoryblock(response.data.categoryblock)

        var newarrivalproduct  = response.data.category_product.sort((a, b) => a.created_at > b.created_at ? -1 : 1);
        setNewcategory(newarrivalproduct.slice(0,6))

        var newfeature = response.data.feature.sort((a, b) => a.created_at > b.created_at ? -1 : 1);
        setFeaturepost(newfeature.slice(0,6))


        setCategories(response.data.categories.slice(0,4))

        setCategory_brand(response.data.category_brand)
        setBanner(response.data.category_banner)

        $('#main-overlay').hide();

    }).catch(err=>{
        props.history.push("/error/404")
        $('#main-overlay').hide();
    })
    }, [props])

//   add to wishlist

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
        <section className="home-category py-3">
        <div className="container">
        {banner ? (
            <section className="hero-banner">
            <img src={banner.image}/>
            </section>
        ):
      (null)}
       
      <section class="home-category py-100 clearfix">
      <div class="container">
          <div class="section-title uppercase">
              <h2 class="mt-0 mb-20">browse by category</h2>
          </div>
          <div class="category-list">
              <div class="category-list-wrapper">
              {categoryblock.map(category=>{
                  return(                  
                  <div class="cat-img" key={category._id}>
                  <a href={`${category.link}`} class="cat-widget text-center">
                          <div class="cat-thumb">
                            <img src={category.image}/>
                          </div>
                          <div class="cat-name uppercase">
                              <h4>{category.title}</h4>
                          </div>
                      </a>
                  </div>
                  )
              })}
              </div>
          </div>
      </div>
  </section>

            
        </div>
    </section>
    <section className="home-products mb-100">
    <div className="container">
        <div className="section-title uppercase">
            <h2 className="mt-0 mb-20">New Arrival</h2>
        </div>
        <div className="home-products-list">
            <div className="row">
            {newcategory.map(newproduct=>{
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
                        <div className="product-info my-3">
                        {newproduct.brand ? (
                            <div className="d-block product-brand">{newproduct.brand.name}</div>

                        )
                    :
                    (null)}
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
            <h2 className="mt-0 mb-20">Feature Products</h2>
        </div>
        <div className="home-products-list">
            <div className="row">
            {featurepost.map(newproduct=>{
                return(
                    <div className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2" key={newproduct.id}>
                    <div className="product-widget">
                        <div className="product-thumb ">
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
                        <div className="product-info my-3">
                        {newproduct.brand ? (
                            <div className="d-block product-brand ">{newproduct.brand.name}</div>
                        )
                    :
                    (null)}
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

    <section className="home-brands mb-100">
    <div className="container">
        <div className="section-title uppercase">
            <h2 className="mt-0 mb-20">Shop by brands</h2>
        </div>
        <div className="home-brands-list">
            <ul className="clearfix">
            {category_brand.map(brand=>{
                return(
                    <li key={brand.id}>
                    <div className="brand-widget">
                        <Link to={`/brand/${brand.slug}`} className="d-block brand-logo">
                            <img className="img-fluid" src={brand.image}/>
                        </Link>
                    </div>
                </li>
            )
            })}
               
            </ul>
        </div>
    </div>
</section>


        </main>
    )
}

export default CategoryPage
