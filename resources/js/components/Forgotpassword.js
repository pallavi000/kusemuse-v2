import axios from 'axios'
import React, { useState } from 'react'
import $ from 'jquery'

function Forgotpassword() {
    const[email,setEmail]= useState('')
    const[error,setError] = useState(null)
    const[success,setSuccess] = useState(null)


function changePassword(e){
    $('#sec-overlay').show();
    e.preventDefault()
    const data ={
        email,
    }
    axios.post('/verifyemail',data).then(response=>{
        setSuccess("password reset link sent to your email")
        $('#sec-overlay').hide();
        setError(null)
    }).catch(error=>{
        setError("Email doesnot exist !!")
        setSuccess(null)
        $('#sec-overlay').hide();
    })




}


    return (
        <div className="container d-flex justify-content-center py-5">
            <form className="mt-5 pt-5 w-50 mx-auto" onSubmit={(e)=>changePassword(e)} >
            {error ? (
                <div className="alert alert-danger">{error}</div>
            ):(null)}
            {success ? (
                <div className="alert alert-success">{success}</div>
  
            ):(null)}
            <div className="form-group">
            <input type="email" className="form-control" name="email" onChange={(e)=>setEmail(e.target.value)} placeholder="Enter Your Email" required/>
            </div>
            <button className="btn btn-primary">Submit</button>
            </form>
        </div>
    )
}

export default Forgotpassword
