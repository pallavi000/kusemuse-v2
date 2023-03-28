import axios from 'axios';
import React,{useState,useEffect,createContext} from 'react'
export const AddContext = createContext();
import {withRouter} from 'react-router-dom';
import Cookies from 'universal-cookie';


function Cartcontext(props) {

const [cart,setCart]= useState(0)

const[cartdetail,setCartdetail] = useState([])



useEffect(() => {
  if(localStorage.getItem('token')){    
    const config = {
      headers: {
        Authorization: 'Bearer '+localStorage.getItem('token')
      }
    }
    axios.get('/cartcount',config).then(response =>{
      setCartdetail(response.data)
    var c=0;
        response.data.forEach(res=>{

          c += res.quantity;
        })

        setCart(c)
    }).catch(error=>{
      console.log(error.response)
    })
  
  } else{
    const cookies = new Cookies()
    if(cookies.get('guest_id')){
      var guest_id = cookies.get('guest_id')
      axios.get('/guestcount/'+guest_id).then(response=>{
        setCartdetail(response.data)
        var c=0;
        response.data.forEach(res=>{

          c += res.quantity;
        })

        setCart(c)        
      })


    } else {
      setCart(0)
    }

   
  }
    
   
}, [props])

function cartButton(pid,cid){
  setCart(cart+1);
  const data = {
      'product': pid,
      'category': cid
  }
  const config = {
      headers: {
        Authorization: 'Bearer '+localStorage.getItem('token')
      }
    }
  axios.post('/addtocart', data,config);
}

    return (
        <AddContext.Provider value={{cart,setCart,cartButton,cartdetail}}>
        {props.children}
        </AddContext.Provider>         
    )
}

export default withRouter(Cartcontext)
