import React,{useState,useEffect} from 'react'
import axios from 'axios'
import {Link,Redirect} from 'react-router-dom'
import $ from 'jquery'
import Pagination from "react-js-pagination";


function Search(props) {
    const [cat, setCat]= useState([])
    const[branded, setBranded] = useState([])
    const[colors,setColors] = useState([])
    const[pro, setPro] = useState([])
    const [var1, setVar1] = useState(null)

    const[cate, setCate] = useState([])
    const [color,setColor] = useState([])
    const[brand,setBrand] = useState([])
    const[minprice,setMinprice] = useState([])
    const[maxprice,setMaxprice] = useState([])
    const[wishlist,setWishlist] = useState([])
    const[changewish,setChangewish] = useState(0)
    const[query,setQuery]= useState('')

    const[start,setStart] = useState(0)
    const[end,setEnd] = useState(9)
    const[itemPerPage,setItemPerPage] = useState(9)
    const[pageNo,setPageNo] = useState(1)

   
    var q = props.location.search
    q = q.replace('%20',' ')
    if(!q){
        props.history.push('/')
        return false
    }
    q = q.replace('?q=','')


    useEffect(() => {
        $('#main-overlay').show();
      axios.get('/catdetail/'+q).then(response=>{
          console.log(response.data)
          setCat(response.data.categories)
          setBranded(response.data.brands)
          setColors(response.data.colors)
          $('#main-overlay').hide();
      }).catch(error=>{
          console.log(error.request.response)
      })

 

    }, [props, var1])


    useEffect(() => {

        if(localStorage.getItem('token')){
            const config = {
                headers: {
                  Authorization: 'Bearer '+localStorage.getItem('token')
                }
              }
          axios.get('/wishlist',config).then(response=>{
              console.log(response.data)
              setWishlist(response.data)
               $('#sec-overlay').hide()
    
          })
        }
        
    }, [changewish])


    // search result filtering

    useEffect(() => {
        setQuery(q)
        $('#sec-overlay').show()
        scrollTop()
        const data ={
            cate:cate,
            color:color,
            brand:brand,
            minprice:minprice,
            maxprice:maxprice,
            searchquery: q,
        }
      

        axios.post('/searchfilter',data).then(response=>{
            console.log(response.data)
            setPro(response.data)
            $('#sec-overlay').hide()
        }).catch(err=>{
            console.log(err.request.response)
        })

    }, [props, brand, color, cate, minprice])
    

    function branddetail(e){
        const newbrand = [...brand];
        const index = newbrand.indexOf(e.target.value);
       
        if(index == -1) {
            newbrand.push(e.target.value)
            setBrand(newbrand)
        } else {
            newbrand.splice(index, 1);
            setBrand(newbrand);
        }

    }

    
   
      function colordetail(e){
          const newcolor =[...color]
          const index = newcolor.indexOf(e.target.value)

          if(index== -1){
              newcolor.push(e.target.value)
              setColor(newcolor)

          }else{
              newcolor.splice(index,1)
              setColor(newcolor)
          }

      }
      
    function price(e,min,max){
        const newminprice =[...minprice]
        const index = newminprice.indexOf(min)

        if(index== -1){
            newminprice.push(min)
            setMinprice(newminprice)

        }else{
            newminprice.splice(index,1)
            setMinprice(newminprice)
        }
        
        
        const newmaxprice =[...maxprice]
        const index1 = newmaxprice.indexOf(max)

        if(index1 == -1){
            newmaxprice.push(max)
            setMaxprice(newmaxprice)

        }else{
            newmaxprice.splice(index1,1)
            setMaxprice(newmaxprice)
        }        
    }
 

   function catdetail(e,id){

        const newcate = [...cate];
        const index = newcate.indexOf(e.target.value);
       
        if(index == -1) {
            newcate.push(e.target.value)
            setCate(newcate)
        } else {
            newcate.splice(index, 1);
            setCate(newcate);
        }
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

function scrollTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
}




  function openNot() {
    $('#notification_popup').show();
}

function closeNot() {
    $('#notification_popup').hide();
}


function Paginate(pageno){
    setPageNo(pageno)
    var startpage = itemPerPage*(pageno-1)
    var endpage = itemPerPage*pageno
    setStart(startpage)
    setEnd(endpage)

}



    return (
        <main>

        <div id="notification_popup">
        <p className="font-weight-bold">The item was added to your bag!</p>
        <div class="notification_popup">
            <div class="field_section">
                <div class="field_row">
                    <div class="field_column one_half">
                        <button class="btn btn-primary " onClick={closeNot}>Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <section className="products-listing py-100">
            <div className="container">
            {pro && pro.length !=0 ?(
                <div className="row">
                <div className="col-12 col-xs-3 col-md-3 col-lg-3">
                    <div className="side-filter">
                        <div className="siderbar-widget border-bottom pb-4 mb-4">
                            <h4 className="text-uppercase font-semibold mb-4">Category</h4>
                            <div className="filters">
                                {cat.map(category=>{
                                    return(
                                        <div className="form-group form-check d-flex align-items-center mb-1" key={category.id}>
                                        <div class="custom-checkbox-div">
                                        <input type="checkbox"  className="form-check-input d-none"  value={category.id} id={`custom-checkbox-category${category.id}`} onClick = {(e)=>catdetail(e, category.id)} name="category" readOnly/>
                                        <label className="form-check-label" htmlFor="Check1" for={`custom-checkbox-category${category.id}`}></label>
                                        </div>
                                        <div className="ml-3">
                                        <label className="form-check-label" htmlFor="Check1">{category.name}</label>
                                        </div>
                                    </div>
                                    )
                                })}
                               
                            </div>
                        </div>
                        <div className="siderbar-widget border-bottom pb-4 mb-4">
                            <h4 className="text-uppercase font-semibold mb-4">Brand</h4>
                            <div className="filters">
                            {branded.map(brand=>{
                                return(
                                    
                                    <div className="form-group form-check d-flex align-items-center mb-1" key={brand.id}>
                                        <div class="custom-checkbox-div">
                                            <input type="checkbox" className="form-check-input d-none" id={`custom-checkbox-brand${brand.id}`} value={brand.id}  name="colors" onClick= {(e)=>branddetail(e)}  readOnly/>
                                            
                                            <label for={`custom-checkbox-brand${brand.id}`}></label>
                                        </div>
                                        <div className="ml-3">
                                        <label className="form-check-label" htmlFor="Check1">{brand.name}</label>
                                        </div>

                                </div>
                                )
                            })}
                               
                            </div>
                        </div>
                        <div className="siderbar-widget border-bottom pb-4 mb-4">
                            <h4 className="text-uppercase font-semibold mb-4">Color</h4>
                            <div className="filters">
                                {colors.map(colors=>{
                                    return(
                            
                                        <div className="form-group form-check mb-1 d-flex align-items-center" key={colors.id}>
                                        <div class="custom-checkbox-div">
                                            <input type="checkbox" className="form-check-input d-none" id={`custom-checkbox-color${colors.id}`} value={colors.id}  name="colors" onClick= {(e)=>colordetail(e)}  readOnly/>
                                            
                                            <label for={`custom-checkbox-color${colors.id}`}></label>
                                        </div>
                                        <div className="mb-auto" >
                                       
                                        </div>
                                        <div className="checkbox-color" style={{background: colors.name}}></div>
                                        <div>
                                        <label className="form-check-label" htmlFor="Check1">{colors.name}</label>
                                        </div>
                                    </div>
                                    )
                                })}
                               
                          </div>
                        </div>
                        <div className="siderbar-widget pb-4 mb-4">
                         <h4 className="text-uppercase font-semibold mb-4">Price</h4>
                            <div className="filters">
                            <div className="form-group  mb-1 d-flex align-items-center">

                                <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 0,1000)} name="price"  id="fp0"/>
                                    
                                    <label for="fp0"></label>
                                </div>
                                <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs.0 - Rs.1000</label>
                                </div>
                             </div>

                             <div className="form-group d-flex align-items-center mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 1000,3000)} name="price" id="fp1"/>
                                    <label for="fp1"></label>
                                </div>
                               <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 1000 - Rs.3000</label>
                                </div>
                             </div>
                             <div className="form-group d-flex align-items-center mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 3000,5000)} name="price" id="fp2"/>
                                    <label for="fp2"></label>

                                </div>
                                <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 3000 - Rs.5000</label>
                                </div>
                             </div>
                             <div className="form-group d-flex align-items-center mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 5000,10000)} name="price" id="fp3"/>
                                    <label for="fp3"></label>
                                </div>
                               <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 5000 - Rs.10000</label>
                                </div>
                             </div>
                             <div className="form-group d-flex align-items-center mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 10000,15000)} name="price" id="fp4"/>
                                    <label for="fp4"></label>
                                </div>
                               <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 10000 - Rs.15000</label>
                                </div>
                             </div>
                             <div className="form-group d-flex align-items-center mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 15000,20000)} name="price" id="fp5"/>
                                    <label for="fp5"></label>
                                </div>
                              <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 15000 - Rs.20000</label>
                                </div>
                             </div>
                             <div className="form-group d-flex align-items-center  mb-1">
                             <div class="custom-checkbox-div">
                                    <input type="checkbox" className="form-check-input d-none" onClick={(e)=>price(e, 20000,2000000)} name="price" id="fp6"/>
                                    <label for="fp6"></label>
                                </div>
                                <div className="ml-3">
                                <label className="form-check-label" htmlFor="Check1">Rs. 20000 - Above</label>
                                </div>
                             </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-12 col-xs-9 col-md-9 col-lg-9">
                    <div className="section-title uppercase">
                        <h2 className="mt-0 mb-20">Search Results
                        </h2>
                    </div>
                    <div className="home-products-list">
                        <div className="row">
                        {pro.slice(start,end).map(product=>{
                            return(
                                <div className="col-6 col-sm-6 col-md-4 col-lg-4 mb-4" key={product.id}>


                                <div className="product-widget">
                                    <div className="product-thumb position-relative cateeimage">
                                    <div class="wishlist">
                                       
                                        {wishlist.find(wish=> wish.product_id === product.id) ?
                                            (
                                             
                                                <button class="wishlist-button" onClick={()=>wishButton(product.id,product.category_id,'remove')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                                
                                                <svg class="wishlist-icon active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                    </svg>
                                    </button>)
                                            :
                                            
                                            ( 
                                                
                                                <button class="wishlist-button" onClick={()=>wishButton(product.id,product.category_id,'add')} data-sku="16640ATQJUZP" data-brand="missguided" data-price="169" data-special-price="">
                                                <svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                </svg>
                                </button>
                                )
                                            }
                                           
                               
                                </div>
                                    <Link to={`/product-detail/${product.id}`}>

                                        <img className="img-fluid" src={product.image}/>
                                    </Link>
                                    </div>
                                    <div className="product-info my-3">
                                    {product.brand ?(
                                        <div className="d-block product-brand">{product.brand.name}</div>
                                        ):
                                    (null)}
                                    {product.designer ?(
                                        <div className="d-block product-brand">{product.designer.name}</div>
                                        ):
                                    (null)}
                                        <div className="d-block product-name">{product.name}</div>
                                       
                                        <span className="d-block product-price">
                                        <span class="reduction">Rs. {(product.price-(product.price*product.discount/100)).toFixed(0)}</span>
                                        &nbsp;&nbsp;&nbsp;
                                        {product.discount ===0 ?(null):(
                                            <span className="pre_reduction">Rs.{product.price}</span>
                                            )}
                                           
                                            {product.discount ===0 ?(null):(
                                                <span className="reduction_tag">{product.discount}% OFF</span>
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
            </div>
                ):(
                    <div className="d-flex justify-content-center ">
                    <h4>WE ARE SORRY!
                    THE PAGE YOU REQUESTED CANNOT BE FOUND.</h4>
                    </div>
                )}
                
            </div>
            {pro && pro.length !=0 ? (
                <div className="container">
                <div className="page-pagination float-right mt-50 float-right">
                <nav aria-label="Page navigation">
                <Pagination
                    activePage={pageNo}
                    itemsCountPerPage={itemPerPage}
                    totalItemsCount={pro.length}
                    pageRangeDisplayed={3}
                    onChange={(e)=>Paginate(e)}
                    prevPageText='Previous'
                    nextPageText='Next' 
                    itemClass="page-item"
                    linkClass="page-link"
                />
                </nav>
            </div>
            </div>
            ):(null)}
           
        </section>
    </main> 
    )
}

export default Search

