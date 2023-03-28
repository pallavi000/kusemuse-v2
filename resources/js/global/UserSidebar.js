
import axios from 'axios'
import React,{useEffect,useState,useContext} from 'react'
import {Link, Redirect,withRouter} from 'react-router-dom';


function UserSidebar(props) {
    const[user,setUser] = useState([])

    useEffect(() => {
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
       axios.get('/profile',config).then(response=>{
           console.log(response.data)
           setUser(response.data)
       })

        $('.dash').removeClass('active')
        if(props.location.pathname.includes('/user/address')){
            $('.shipping').addClass('active')
        }else if(props.location.pathname.includes('/orderlist')){
            $('.orders').addClass('active')
        }else if(props.location.pathname.includes('/wishlist')){
            $('.wishlistdash').addClass('active')
        }else $('.dashboard').addClass('active')
   

    }, [props])



    function loggedout(){
        localStorage.removeItem("token")
        props.history.push('/')
        // setIsLogedIn(false)
    
      }







    return (
        <div class="sidebar-ui">
        <h5 className="text-capitalize">{user.name}</h5>
        <div class="logout pointer" onClick={loggedout}>Sign Out</div>
        <div class="w-100 mt-4 mb-4">
            <ul class="list-unstyled">
              <li className="dashboard dash">
                  <Link to="/user"><i class="fa fa-user"></i> Dashboard</Link>
              </li>
              <li className="shipping dash">
                  <Link to="/user/address"><i class="fa fa-map-marker"></i> Shipping Address</Link>
              </li>
              <li className="orders  dash">
                  <Link to="/orderlist"><i class="fa fa-shopping-cart"></i> Orders</Link>
              </li>
              <li class="wishlistdash dash">
                  <Link to="/wishlist"><i class="fa fa-heart"></i> Wishlist</Link>
              </li>
            </ul>
        </div>
    </div>
    
    )
}

export default withRouter(UserSidebar)
