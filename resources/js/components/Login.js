
import React, { Component, useState,useEffect } from 'react';
import axios from 'axios';
import GoogleLogin from 'react-google-login';
import FacebookLogin from 'react-facebook-login';
import {Link} from 'react-router-dom'


function Login(props) {

    const[email,setEmail] = useState(null)
    const[password,setPassword] = useState(null)
    const[error,setError] = useState(null)

useEffect(() => {
  if(localStorage.getItem('token')){
    const config = {
      headers: {
        Authorization: 'Bearer '+localStorage.getItem('token')
      }
    }
    axios.get('/check',config).then(check=>{
     
      props.history.push('/')
      
    }).catch(error=>{
      
    })


  }
}, [])


function responseGoogle(response){
  $('#sec-overlay').show()


const data={
  
  name:response.profileObj.name,
  email:response.profileObj.email,
  password: response.profileObj.googleId
}


axios.post('/googlelogin',data).then(response=>{
  localStorage.setItem('token',response.data.accessToken)
  $('#sec-overlay').hide()

  if(props.match.params.parameter == 'checkout'){
    props.history.push('/checkout')
  }else{
    props.history.push('/')
  }
}).catch(err=>{
  console.log(err.request.response)
  $('#sec-overlay').hide()

})

}

function errorGoogle(response){
  
  console.log(response)
  $('#sec-overlay').hide()

}

function responseFacebook(response){
  $('#sec-overlay').show()

  const data= {
    name:response.name,
    email:response.email,
    password:response.id
  }

  axios.post('/googlelogin',data).then(response=>{
    localStorage.setItem('token',response.data.accessToken)
    $('#sec-overlay').hide()

    if(props.match.params.parameter == 'checkout'){
      props.history.push('/checkout')
    }else{
      props.history.push('/')
    }
 
  }).catch(err=>{
    console.log(err.request.response)
    $('#sec-overlay').hide()

  })
}
function errorFacebook(error){
console.log(error)
$('#sec-overlay').hide()

}



 function signup(e){
  $('#sec-overlay').show()

      e.preventDefault();
      const data = {
          'email': email,
          'password': password,
      }
        axios.post('/reactlogin', data).then(reactlogin=>{

            localStorage.setItem("token", reactlogin.data.accessToken);
            $('#sec-overlay').hide()

            if(props.match.params.parameter == 'checkout'){
              props.history.push('/checkout')
            }else{
              props.history.push('/')
            }
           
            
        }).catch(error=>{
          setError( 'Username or Password is invalid.')
            console.log(error.request.response);
            $('#sec-overlay').hide()

        })
  }



    return (
      <div className="container py-5 text-center">
      <div className="col-lg-6  pb-4 signup-page mx-auto ">
      <h3 className="font-weight-bold mb-4"> Signin to continue</h3>
      <div className="row mt-4">
      <div className="col-md-6">
      <GoogleLogin cssClass =" gmailLogin d-block mx-auto my-3 w-75"

      clientId="70562926460-epr3hblcs7e8f7mkq8t2oda5mjnq6mov.apps.googleusercontent.com"

      buttonText="Login with Google"

      onSuccess={responseGoogle}

      onFailure={errorGoogle}
      cookiePolicy={'single_host_origin'}

        ></GoogleLogin>
      </div>
      <div className="col-md-6">
      <FacebookLogin cssClass=" fblogin d-block w-75"
      icon="fa-facebook-official"
      appId="515762042851978"
      fields="name,email,picture,gender"
      callback={()=>responseFacebook}
      onFailure={()=>errorFacebook}
    />
      </div>
      </div>
        <form className="mt-5 pt-5" action="" onSubmit={(e)=>signup(e)}>
        { error ?
        (<div className="alert alert-danger">{error}</div>)
        :
      (null)
    }
    <div className="form-group">
          <input type="email" className="form-control w-75 mx-auto" onChange={(e)=>setEmail(e.target.value)} placeholder="Enter Email"  />
        
      </div>
      <div className="form-group">
          <input className="form-control w-75 mx-auto" type="password" onChange={(e)=>setPassword(e.target.value)} placeholder="Enter Password" />
        
        </div>
        
        <div>
          <button  className="btn btn-primary w-75 mt-3" type="submit">SIGN IN</button>
        </div>
        <div className="form-group mt-3">
        <Link to="/forgotpassword" className="nav-link">Forgot Password ?</Link>
       </div>
        </form>
        <h4 className="font-weight-bold mt-4">Dont have an account??</h4>
        {props.match.params.parameter =='checkout' ? (
          <Link className="btn btn-secondary text-uppercase mt-5" to="/signup/checkout">Create an Account</Link>
        )
      :
      (        <Link className="btn btn-secondary text-uppercase mt-5" to="/signup">Create an Account</Link>
      )}
        </div>
      </div>
    
        );
   
}

export default Login ;

