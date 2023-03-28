import React,{useEffect,useState,useContext} from 'react'
import axios from 'axios'
import {Link} from 'react-router-dom'
import {AddContext} from '../Cartcontext';
import $ from 'jquery'
import Pagination from "react-js-pagination";

function Designer(props) {
    const data = useContext(AddContext)
    const{setCart}= data
    const{cart} = data
    const[items,setItems] = useState([])
    const[designers,setDesigners]= useState([])
    const[colors,setColors] = useState([])
    const[categories,setCategories] =  useState([])
    const[cate,setCate] = useState([])
    const[var1,setVar1] = useState(null)
    const[color,setColor] = useState([])
    const[minprice,setMinprice] = useState([])
    const[maxprice,setMaxprice] = useState([])
    const[wishlist,setWishlist] = useState([])
    const[changewish,setChangewish] = useState(0)

    const[start,setStart] = useState(0)
    const[end,setEnd] = useState(9)
    const[itemPerPage,setItemPerPage] = useState(9)
    const[pageNo,setPageNo] = useState(1)
  

    useEffect(() => {
        $('#main-overlay').show();
       axios.get('/designer/'+props.match.params.slug).then(response=>{
           setColors(response.data.colors)
           setDesigners(response.data.designer)
           setCategories(response.data.categories)
           $('#main-overlay').hide();

       }).catch(error=>{
           console.log(error.request.response)
           $('#main-overlay').hide();
       })
    }, [props])

    useEffect(() => {
        $('#main-overlay').show();
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


// add to wishlist
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



    // sidebar filtering 

    useEffect(() => {
        $('#sec-overlay').show()
        scrollTop()
        const data={
            designer:props.match.params.slug,
            cate:cate,
            color:color,
            minprice:minprice,
            maxprice:maxprice
        }
        
        console.log(data)

        axios.post('/designerfetch',data).then(response=>{
            setItems(response.data)
            $('#sec-overlay').hide()
        }).catch(err=>{
            console.log(err.request.response)
        })
       
    }, [props,color,cate,minprice])


    function designcolor(e){
        const newcolor = [...color]
        const index = newcolor.indexOf(e.target.value)
        if(index==-1){
            newcolor.push(e.target.value)
            setColor(newcolor)

        }else{
            newcolor.splice(index,1)
            setColor(newcolor)
        }

    }

    function scrollTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
          });
    }
    
    function price(e, min,max){

        const newmaxprice =[...maxprice]
        const index = newmaxprice.indexOf(max)
        if(index== -1){
            newmaxprice.push(max)
            setMaxprice(newmaxprice)

        }else{
            newmaxprice.splice(index,1)
            setMaxprice(newmaxprice)
        }

        const newminprice =[...minprice]
        const index1 = newminprice.indexOf(min)
        if(index1 == -1){
            newminprice.push(min)
            setMinprice(newminprice)

        }else{
            newminprice.splice(index1,1)
            setMinprice(newminprice)
        }

       }



function catedesign(e){
    const newcate = [...cate]
    const index= newcate.indexOf(e.target.value)
    if(index==-1){
        newcate.push(e.target.value)
        setCate(newcate)

    }else{
        newcate.splice(index,1)
        setCate(newcate)
    }
    }

    // paginate

    function Paginate(pageno){
        setPageNo(pageno)
        var startpage = itemPerPage*(pageno-1)
        var endpage = itemPerPage*pageno
        setStart(startpage)
        setEnd(endpage)
    
    }
        // produrt sorting according to date and price

    function sortpro(action){
        if(action=="date"){
            var x = items.sort((a,b)=>{
                return new Date(b.created_at).getTime() - 
                new Date(a.created_at).getTime()
            })
            console.log(x)
            setItems([...x])
        }
        
        if(action=="highPrice"){
            var x = items.sort((a,b)=>{
                var aPrice = a.price-(a.price*a.discount/100)
                var bPrice = b.price-(b.price*b.discount/100)
                if(aPrice<bPrice){
                    return 1
                }else if(aPrice>bPrice){
                    return -1
                }else return 0
            })
            console.log(x)
            setItems([...x])
        
        }
        
        if(action=="lowPrice"){
            var x = items.sort((a,b)=>{
                var aPrice = a.price-(a.price*a.discount/100)
                var bPrice = b.price-(b.price*b.discount/100)
        
                if(aPrice>bPrice){
                    return 1
                }else if(aPrice<bPrice){
                    return -1
                }else return 0
            })
            console.log(x)
            setItems([...x])
        
        }
        }

        // arrow toggle
        $('.panel-collapse').on('show.bs.collapse', function () {
            $(this).siblings('.panel-heading').addClass('active');
          });
          
          $('.panel-collapse').on('hide.bs.collapse', function () {
            $(this).siblings('.panel-heading').removeClass('active');
          });

    return (
        <main>
        <section className="products-listing py-100">
         <div className="container">
         <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><Link to="/">Home</Link></li>
             <li class="breadcrumb-item"><Link to="/boutique">Boutique</Link></li>
             <li class="breadcrumb-item active text-capitalize" aria-current="page">{designers.name}</li>
         </ol>
         </nav>
         <div class="row">
                    <div class="col-12 col-xs-3 col-md-3 col-lg-3">
                        <div class="siderbar-widget pb-4 mb-4">
                            <div class="side-filter">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                          <h4 class="panel-title mb-0">
                                            <a class="pl-0 pr-0" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                              Category
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="filters">
                                                {categories.map(category=>{
                        return(
                            <div class="form-group form-check mb-1" key={category.id}>
                                                        <input type="checkbox" class="form-check-input"  value={category.slug} id={`custom-checkbox-category${category.id}`} onClick = {(e)=>catedesign(e, category.slug)} name="category" readOnly/>
                                                        <label class="form-check-label" for={`custom-checkbox-category${category.id}`}>{category.name}</label>
                                                </div>
                        )
                    })}
                     </div>
                     </div>
                     </div>
                     </div>
                 

                    <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title mb-0">
                        <a class="collapsed pl-0 pr-0" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           Designer
                        </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <div class="panel-body">
                                <div class="filters">
                 
                        <div class="form-group form-check mb-1">
                        <label className="form-check-label" htmlFor="Check1">{designers.name}</label>

                           
                        </div>
                  
                    </div>
                    </div>
                    </div>
                 </div>
             </div>
             <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingThree">
                                          <h4 class="panel-title mb-0">
                                            <a class="collapsed pl-0 pr-0" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                              Color
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                          <div class="panel-body">
                                            <div class="panel-body">
                                                <div class="filters">
                                    {colors.map(colors=>{
                                        return(
                                            <div class="form-group form-check mb-1">
                                                        <input type="checkbox" class="form-check-input" id={`custom-checkbox-color${colors.id}`} value={colors.id}  name="colors" onClick= {(e)=>designcolor(e)}  readOnly/>
                                                        <label class="form-check-label" for={`custom-checkbox-color${colors.id}`}>{colors.name}</label>
                                                    </div>
                                        )
                                    })}
                                   </div>
                                   </div>
                                </div>
                              </div>
                       
                            </div>
                            <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingFour">
                                          <h4 class="panel-title mb-0">
                                            <a class="collapsed pl-0 pr-0" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                              Price
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                            <div class="panel-body">
                                                <div class="filters">
                                                <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e, 0,1000)} name="price"  id="fp0"/>
                                                        <label class="form-check-label" for="fp0">Rs.0 - Rs.1000</label>
                                                    </div>

                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,1000,3000)} name="price"  id="fp1"/>
                                                        <label class="form-check-label" for="fp1"> Rs.1000 - Rs.3000</label>
                                                    </div>

                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,3000,5000)} name="price"  id="fp2"/>
                                                        <label class="form-check-label" for="fp2"> Rs.3000 - Rs.5000</label>
                                                    </div>

                                                    
                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,5000,10000)} name="price"  id="fp3"/>
                                                        <label class="form-check-label" for="fp3"> Rs.5000 - Rs.10000</label>
                                                    </div>

                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,10000,15000)} name="price"  id="fp4"/>
                                                        <label class="form-check-label" for="fp4"> Rs.10000 - Rs.15000</label>
                                                    </div>

                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,15000,20000)} name="price"  id="fp5"/>
                                                        <label class="form-check-label" for="fp5"> Rs.15000 - Rs.20000</label>
                                                    </div>

                                                    <div class="form-group form-check mb-1">
                                                        <input type="checkbox" onClick={(e)=>price(e,20000,20000000)} name="price"  id="fp6"/>
                                                        <label class="form-check-label" for="fp6">  Rs.20000 - Above</label>
                                                    </div>
                                                </div>
                                                </div>
                                </div>
                                </div>
                            </div>
                        </div> 
                        </div>
                    </div>
                        <div className="col-12 col-xs-9 col-md-9 col-lg-9">
                        <div class="category-sort">
                        <div>Sort By :</div>
                        <div className="form-group">

                        <select className="form-control" onChange={(e)=>sortpro(e.target.value)}>
                            <option value="date">New Arrivals</option>
                            <option value="highPrice">Price: High to Low</option>
                            <option value="lowPrice">Price: Low to High</option>
                        </select>

                        </div>
                        </div>

                        <div class="col-12 col-xs-9 col-md-9 col-lg-9">
                        <div class="home-products-list">
                        
                            <div className="row">
                            {items.slice(start,end).map(product=>{
                                return(
                                    <div className="col-6 col-sm-6 col-md-4 col-lg-3 mb-4" key={product.id}>


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

                                            <img  src={product.image}/>
                                        </Link>
                                       
                                        </div>
                                        <div className="product-info my-30">
                                        {product.designer ?(
                                            <Link to={`product-detail/${product.id}`} className="d-block product-brand">{product.designer.name}</Link>
                    
                                        ):(null)}
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
                    </div>
                    </div>
      <div className="container">
    <div className="page-pagination float-right mt-50 float-right">
            <nav aria-label="Page navigation">
            <Pagination
          activePage={pageNo}
          itemsCountPerPage={itemPerPage}
          totalItemsCount={items.length}
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
    </section>
    </main>
    )
}

export default Designer
