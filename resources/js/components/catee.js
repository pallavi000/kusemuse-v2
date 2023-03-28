import axios from 'axios'
import { map } from 'lodash'
import React,{useEffect,useState} from 'react'
import {Link,Redirect} from 'react-router-dom'
import $ from 'jquery'
import { Input } from 'postcss'

function Category(props) {
    const [cat, setCat]= useState([])
    const[branded, setBranded] = useState([])
    const[colors,setColors] = useState([])
    const[pro, setPro] = useState([])
    const [var1, setVar1] = useState(null)

    const[cate, setCate] = useState('')
    const [color,setColor] = useState('')
    const[brand,setBrand] = useState('')




    useEffect(() => {
      axios.get('/catdetail/'+props.match.params.slug).then(response=>{
          setCat(response.data.categories)
          setBranded(response.data.brands)
          setColors(response.data.colors)
          setPro(response.data.products)
          setCate(props.match.params.slug)
      }).catch(error=>{
          console.log(error.request.response)
      })
    }, [props, var1])

 

   function catdetail(e,slug){
    
    var val = e.target.value;
    var obj = $('input[name="category"]');
    $('input[name="colors"]').prop('checked',false);
    $.each(obj, function(key, value) {
        var va = $(value).val();
        if(val===va) {
            if($(value).is(':checked')) {
                setCate(val)
            } else {
                setCate(props.match.params.slug)
                setVar1(Math.random())
              $('input[name="brand"]').prop('checked',false);
              
            }
        } else {
            $('input[value="'+va+'"]').prop('checked', false);
        }
    })

    axios.get('/catdetail/'+slug).then(response=>{
        setPro(response.data.products)
        setBranded(response.data.brands)
    })

   }

   function branddetail(e,slug){
    var currentBrand = e.target.value;
    var obj = $('input[name="brand"]');
    var temp_brand;
    var is_true = false;
    $.each(obj,function(key,value){
         var b = $(value).val();
         if(currentBrand==b){
           if( $('input[value="'+currentBrand+'"]').is(':checked') )
           {
            setBrand(currentBrand)
            temp_brand = currentBrand;

           } else {
               temp_brand = '';
                setBrand('')
             $('input[name="category"]').prop('checked', false);
               setVar1(Math.random())
               is_true = true;
           }
         }
         else{
             $('input[value="'+b+'"]').prop('checked',false);
         }
    })

    if(is_true) {
        return false;
    }

    const data={
        category:cate,
        brand: temp_brand,
        color:color,
    }

    axios.post('/filter',data).then(response=>{
        console.log(response.data)
        setPro(response.data)
    }).catch(error=>{
        console.log(error.request.response)
    })
  
 }


   function colordetail(e,slug){
    var color = e.target.value; 
    var is_true =false;
    var temp_color
    var obj = $('input[name="colors"]');
    $.each(obj,function(key,value){
        var c = $(value).val();
        if(color==c){
            if($(value).is(':checked')){
                setColor(color)
                temp_color = color
            }else{
                setColor('')
                temp_color =''
                setVar1(Math.random())
                is_true = true;
                $('input[name="category"]').prop('checked',false);
            }
        }
        else{
            $('input[value="'+c+'"]').prop('checked',false)
        }
    })
    if(is_true){
return false;
    }

    const data={
        category:cate,
        brand: brand,
        color:temp_color,
    }
    
    axios.post('/filter',data).then(response=>{
        console.log(response.data)
        setPro(response.data)
    }).catch(error=>{
        console.log(error.request.response)
    })
   }
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

  if(localStorage.getItem('token')){
    axios.post('/addtowish',data,config).then(response=>{
        console.log(response.data)
    })
  }else{
      props.history.push('/signin')
  }
   
   
}





    return (
        <div className="container mt-5">
            <div className="row">
                <div className="col-md-4">
                    <div className="card">
                         <div className="card-body">
                        <b> Categories:</b>

                         {cat.map(category=>{
                             return(
                                 
                                 <div key={category.id}>
                             
                                <input type="checkbox" value={category.slug}  onClick = {(e)=>catdetail(e, category.slug)} name="category" readOnly/> {category.name}
                                 </div>
                             )
                         })}
                         </div>
                     </div>
                     <div className="card">
                        <div className="card-body">
                            <b>Brands:</b>
                            {branded.map(brand=>{
                                return(
                                 <div key={brand.id}>
                                    <input type="checkbox" value={brand.slug}  name="brand" onClick={(e)=>branddetail(e,brand.slug)} readOnly/> {brand.name}
                                </div>
                                )
                            })}
                        </div>
                    </div>
                    <div className="card">
                        <div className="card-body">
                            <b>colors</b>
                            {colors.map(colors=>{
                                return(
                                 <div key={colors.id}>
                                    <input type="checkbox" value={colors.slug}  name="colors" onClick= {(e)=>colordetail(e,colors.slug)}  readOnly/> {colors.name}
                                </div>
                                )
                            })}
                        </div>
                    </div>



                </div>
                <div className="col-md-8">
                     <div className ="row">
                     
                     {pro.map(product=>{
                        return(
                            <div className="col-md-4" key={product.id}>
                            <div className="card">
                            <div className="card-body">

                            
                            <img className="img-fluid" src={product.image}/>
                            <div className="card-body card-body-cascade text-center">
                            <h4 className="card-title"><strong><Link to={`/product-detail/${product.id}`}>{product.name}</Link></strong></h4>
                            
                            <p className="price">Rs.{product.price}</p> 
                            <p className="card-text">{product.detail} </p>
                            <button className="btn btn-light" onClick={()=>wishButton(product.id,product.category_id)}>add to wishlist</button>

                            </div>
                         

                            </div>
                            </div>
                           
                            </div>
                        )
                    })}
                     </div>
                     
                </div>
            </div>
        </div>
    )
}


export default Category
