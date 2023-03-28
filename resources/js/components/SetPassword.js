import React,{useState} from 'react'
import $ from 'jquery'

function SetPassword(props) {
    const[errors,setErrors] = useState([])

    const[error,setError] = useState(null)
    const[success,setSuccess] = useState(null)
    const[password,setPassword] = useState('')
    const[confirmpassword,setConfirmpassword] = useState('')


    function changepassword(e){
        e.preventDefault()
        $('#sec-overlay').show()

        if(password != confirmpassword){
            setError('password did not match')
            return false;
        }
        const data = {
            key: props.match.params.slug,
            password,
        }
        axios.post('/setpassword',data).then(response=>{
            localStorage.setItem('token', response.data)
            $('#sec-overlay').hide();
            props.history.push('/')
        }).catch(err=>{
            if(err.request.status==422){
                var error = JSON.parse(err.request.response)
                setErrors(error.errors)
            }else{
                setError("invalid password or Key")
            }
           
            $('#sec-overlay').hide();
        })
        
        }

    return (
        <div className="container d-flex justify-content-center py-5 my-5">
        <div class="col-md-8">
        <form onSubmit={(e)=>changepassword(e)}>
        {error ? (<div className="alert alert-danger">
            {error}
        </div>):(null)}

        {errors.password ?(
            errors.password.map(password=>{
              return(
                <div className="alert alert-danger">{password}</div>

              )

            })
          ):(null)}
        <div className="form-group">
        <label>New Password</label>
        <input className="form-control" type="password" name="newpassword" onChange={(e)=>setPassword(e.target.value)} placeholder="password" required  />
        </div>
        <div className="form-group">
        <label>Confirm Password</label>
        <input className="form-control" type="password" name="confirmpassword" onChange={(e)=>setConfirmpassword(e.target.value)} placeholder=" confirm password" required   />
        </div>
            <button className="btn btn-primary w-25">Submit</button>
        </form>
        </div>
        </div>
    )
}

export default SetPassword
