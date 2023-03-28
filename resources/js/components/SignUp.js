import axios from 'axios'
import React,{useState} from 'react'
import GoogleLogin from 'react-google-login';
import FacebookLogin from 'react-facebook-login';
import $ from 'jquery'

function SignUp(props) {
  const[errors,setErrors] = useState([])
  const[user,setUser]=useState({
    name:'',
    email:'',
    password:'',
    gender:''
  })
  const[error,setError] = useState(null)


  function checkEmail(email){
    const regex = new RegExp(
      '^(([^<>()[\\]\\\\.,;:\\s@\\"]+(\\.[^<>()[\\]\\\\.,;:\\s@\\"]+)*)|' +
      '(\\".+\\"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])' +
      '|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$'
      );

      return regex.test(email)
  }


  function register(e){
    e.preventDefault();
   var validate =  checkEmail(user.email)
   if(!validate){
     $('.invalid-feedback').show()
     return 
   }


    $('#sec-overlay').show()

  

    axios.post('/signup',user).then(response=>{
      localStorage.setItem('token', response.data.accessToken)
      $('#sec-overlay').hide()
      if(props.match.params.parameter == 'checkout'){
        props.history.push('/checkout')
      }else{
        props.history.push('/')
      }

    }).catch(error=>{
      if(error.request.status == 422){
        var err = JSON.parse(error.request.response)
        setErrors(err.errors)
       console.log(err)

      }else{
        setError('email already in used!!!')
        console.log(error.request.response)
      }
      $('#sec-overlay').hide()

    })
  }

  function input(e){
setUser({
  ...user,
  [e.target.name]:e.target.value
})
  }

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
    


    return (
        <div className="container py-5 text-center">
        <div className="col-lg-6 pb-4 mx-auto signup-page">
        <h3 className="font-weight-bold mb-4"> Signup to continue</h3>
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
      fields="name,email,picture"
      callback={responseFacebook}
      onFailure={errorFacebook}
    />
      </div>
        </div>
        { error ?
          (<div className="alert alert-danger">{error}</div>)
          :
        (null)
      }

      
            <div className="mt-4">
            {errors.email ?(
              errors.email.map(email=>{
                return(
                  <div className="alert alert-danger">{email}</div>
                )
              })
            ):(null)}
            {errors.password ?(
              errors.password.map(password=>{
                return(
                  <div className="alert alert-danger">{password}</div>
  
                )
  
              })
            ):(null)}
            {errors.name ?(
              errors.name.map(name=>{
                return(
                  <div className="alert alert-danger">{name}</div>
  
                )
              })
            ):(null)}
            </div>
       
    
      
        <form className="mt-5 pt-5" onSubmit={(e)=>register(e)}>
        <div className="form-group">
          <input type="text" className="form-control w-75 mx-auto" name="name" placeholder="Enter name" onChange={(e)=>input(e)} id="name" required />
        </div>
        <div className="form-group">
          <input type="email" className="form-control w-75 mx-auto" name="email" placeholder="Enter email" onChange={(e)=>input(e)} id="email" required />
          <small className="invalid-feedback">Invalid Email</small>
        </div>
        <div className="form-group">
          <input type="password" className="form-control w-75 mx-auto" placeholder="Enter password" name="password" oid="pwd"  onChange={(e)=>input(e)} required />
        </div>
     <div className="form-group">
        <div className="uaForm__inlineFields">
        <label className="uaForm__blockRadioWrapper">
        <input name="gender" type="radio" value="female" onChange={(e)=>input(e)} className="uaForm__input uaForm__input--blockRadio"/>
        <div className="uaForm__blockRadioValue">Female</div></label>

        <label className="uaForm__blockRadioWrapper">
        <input name="gender" type="radio" value="male" onChange={(e)=>input(e)}  className="uaForm__input uaForm__input--blockRadio"/>
        <div className="uaForm__blockRadioValue">Male</div></label>
        </div>
        </div>

        <div className="form-group form-check">
          <label className="form-check-label">
            <input className="form-check-input" type="checkbox"/> Remember me
          </label>
        </div>

        <button type="submit" className="btn btn-primary w-75 mt-3">Submit</button>
      </form>  
        </div>
        </div>
    )
}

export default SignUp
