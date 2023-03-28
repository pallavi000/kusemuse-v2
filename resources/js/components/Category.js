import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {Link,Redirect} from 'react-router-dom'
import Pagination from "react-js-pagination";
import $ from 'jquery'



function Category(props) {
    const [cat, setCat]= useState([])
    const[branded, setBranded] = useState([])
    const[colors,setColors] = useState([])
    const[pro, setPro] = useState([])
    const [var1, setVar1] = useState(null)

    const[cate, setCate] = useState(props.match.params.slug)
    const [color,setColor] = useState([])
    const[brand,setBrand] = useState([])
    const[minprice,setMinprice] = useState([])
    const[maxprice,setMaxprice] = useState([])
    const[wishlist,setWishlist] = useState([])
    const[changewish,setChangewish] = useState(0)
    const[breadcrumb,setBreadcrumb] = useState([])
    const[currentCategory,setCurrentCategory] = useState([])
    const[start,setStart] = useState(0)
    const[end,setEnd] = useState(9)
    const[itemPerPage,setItemPerPage] = useState(9)
    const[pageNo,setPageNo] = useState(1)


    useEffect(() => {
        $('#main-overlay').show();
      axios.get('/catdetail/'+props.match.params.slug).then(response=>{
          console.log(response.data.breadcrumb)
          setCat(response.data.categories)
          setBranded(response.data.brands)
          setColors(response.data.colors)
          setCate(props.match.params.slug)
          setBreadcrumb(response.data.breadcrumb.reverse())
          setCurrentCategory(response.data.currentCategory)
           $('#main-overlay').hide();

      }).catch(error=>{
          console.log(error.request.response)
          $('#main-overlay').hide();
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

    useEffect(() => {
        $('#sec-overlay').show()
        scrollTop()
        const data ={
            cate:cate,
            color:color,
            brand:brand,
            minprice:minprice,
            maxprice:maxprice
        }
        console.log(data)

        axios.post('/filter',data).then(response=>{
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
 

   function catdetail(e,slug){
    var val = e.target.value;
    var obj = $('input[name="category"]');
    $.each(obj, function(key, value) {
        var va = $(value).val();
        if(val===va) {
            if($(value).is(':checked')) {
                setCate(val)
            } else {
                setCate(props.match.params.slug)              
            }
        } else {
            $('input[value="'+va+'"]').prop('checked', false);
        }
    })
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




function sortpro(action){
if(action=="date"){
    var x = pro.sort((a,b)=>{
        return new Date(b.created_at).getTime() - 
        new Date(a.created_at).getTime()
    })
    console.log(x)
    setPro([...x])
}

if(action=="highPrice"){
    var x = pro.sort((a,b)=>{
        var aPrice = a.price-(a.price*a.discount/100)
        var bPrice = b.price-(b.price*b.discount/100)
        if(aPrice<bPrice){
            return 1
        }else if(aPrice>bPrice){
            return -1
        }else return 0
    })
    console.log(x)
    setPro([...x])

}

if(action=="lowPrice"){
    var x = pro.sort((a,b)=>{
        var aPrice = a.price-(a.price*a.discount/100)
        var bPrice = b.price-(b.price*b.discount/100)

        if(aPrice>bPrice){
            return 1
        }else if(aPrice<bPrice){
            return -1
        }else return 0
    })
    console.log(x)
    setPro([...x])

}
}


function Paginate(pageno){
    setPageNo(pageno)
    var startpage = itemPerPage*(pageno-1)
    var endpage = itemPerPage*pageno
    setStart(startpage)
    setEnd(endpage)

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

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><Link to="/">Home</Link></li>
                    {breadcrumb.map(category=>{
                        return(
                            category.parent_id && category.parent_id!=0?(
                                <li class="breadcrumb-item text-capitalize" key={category.id}><Link to={`/category/${category.slug}`}>{category.name}</Link></li>
                            ):(
                                <li class="breadcrumb-item text-capitalize" key={category.id}><Link to={`/${category.slug}`}>{category.name}</Link></li>
                            )
                           
                        )
                    })}
                    <li class="breadcrumb-item active text-capitalize" aria-current="page">{currentCategory.name}</li>
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
                                    {cat.map(category=>{
                                        return(
                                            <div class="form-group form-check mb-1" key={category.id}>
                                                        <input type="checkbox" class="form-check-input"  value={category.slug} id={`custom-checkbox-category${category.id}`} onClick = {(e)=>catdetail(e, category.slug)} name="category" readOnly/>
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
                                              Brand
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <div class="panel-body">
                                                    <div class="filters">



                        
                                {branded.map(brand=>{
                                    return(

                                        <div class="form-group form-check mb-1" key={brand.id}>
                                                            <input type="checkbox" class="form-check-input" id={`custom-checkbox-brand${brand.id}`} value={brand.id}  name="colors" onClick= {(e)=>branddetail(e)}  readOnly/>
                                                            <label class="form-check-label" for={`custom-checkbox-brand${brand.id}`}>{brand.name}</label>
                                                        </div>
                                    )
                                })}
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
                                                        <input type="checkbox" class="form-check-input" id={`custom-checkbox-color${colors.id}`} value={colors.id}  name="colors" onClick= {(e)=>colordetail(e)}  readOnly/>
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
                            {pro.slice(start,end).map(product=>{
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
                                        {product.brand ?(
                                            <div className="d-block product-brand">{product.brand.name}</div>
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
                </div>
            </div>
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
        </section>
    </main> 
    )
}

export default Category
