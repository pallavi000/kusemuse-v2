import axios from 'axios'
import React,{useEffect, useState} from 'react'
import {Link} from 'react-router-dom';
import $ from 'jquery'
import UserSidebar from '../global/UserSidebar';



function User(props) {

const[customer, setCustomer] = useState([])
const[user,setUser] = useState([])
const[currentpassword,setCurrentpassword] = useState(null)
const[newpassword,setNewpassword] = useState(null)
const[confirmpassword,setConfirmpassword] = useState(null)
const[error,setError] = useState(null)
const[success,setSuccess] = useState(null)
const[errors,setErrors] = useState([])
const[view,setView] = useState('dashboard')





useEffect(() => {

    $('#main-overlay').show();

    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
   axios.get('/profile',config).then(response=>{
       console.log(response.data)
       setUser(response.data)
       setCustomer(response.data.customer)
       $('#main-overlay').hide();

   })

   if(props.match.params.parameter=="address"){
    setView("address")
    }else if(props.match.params.parameter=="changepassword"){
        setView("changepassword")
    }else{
        setView("dashboard")
    }
}, [props])


function changepassword(e){
    $('#sec-overlay').show()
e.preventDefault()
const config = {
    headers: {
      Authorization: 'Bearer '+localStorage.getItem('token')
    }
  }
if(newpassword != confirmpassword){
    setError('password did not match')
    return false;
}
const data = {
    currentpassword,
    newpassword
}
axios.post('/changepassword',data,config).then(response=>{
    setView("dashboard")
    $('#sec-overlay').hide()
   setSuccess("password has been successfully updated")

}).catch(err=>{
    if(err.request.status == 422){
        var error = JSON.parse(err.request.response)
        setErrors(error.errors)
    }else{
        setError("invalid password")
    }
    $('#sec-overlay').hide()

   
})

}






    return (
        <main>
      <section class="dashboard-ui position-relative">
          <div class="container">
              <div class="row">
               
           
            <UserSidebar/>
            
         

        {view==='dashboard'?(
            <div class="col-md-8 col-lg-9">
            {success ? (
                <div className="alert alert-success">{success}</div>
            ):(null)}
            <div class="dashboard-panel">
                <div class="panel-widget p-5">                              
                    <div class="font-weight-bold mb-3">ACCOUNT INFORMATION</div>
                        <div class="account-info">
                            <strong>Name:</strong>
                            <p>{user.name}</p>
                        </div>
                        <div class="account-info">
                            <strong>Email:</strong>
                            <p>{user.email}</p>
                        </div>
                        {customer ?(
                            <div class="account-info">
                            <strong>Phone No:</strong>
                            <p>{customer.phone}</p>
                        </div>
                        ):
                        (null)}
                        <div className="account-info">
                        <button className = "btn btn-border" onClick={()=>props.history.push('/user/changepassword')}>Change Password</button>
                         </div>  
                    </div>
                </div>
            </div>



            



       
            
        ): view === "changepassword" ?(
            <div class="col-md-8 col-lg-9">
            <div class="dashboard-panel">
                <div class="panel-widget p-5">  

            <form onSubmit={(e)=>changepassword(e)}>
            {error ? (<div className="alert alert-danger">
                {error}
                </div>):(null)}

                {errors.newpassword ?(
                    errors.newpassword.map(password=>{
                      return(
                        <div className="alert alert-danger">{password}</div>
        
                      )
        
                    })
                  ):(null)}

            <div className="form-group">
            <label>Current Password</label>
            <input className="form-control" type="password" name="currentpassword" onChange={(e)=>setCurrentpassword(e.target.value)} placeholder="current password" required />
            </div>
            <div className="form-group">
            <label>New Password</label>
            <input className="form-control" type="password" name="newpassword" onChange={(e)=>setNewpassword(e.target.value)} placeholder=" new password" required  />
            </div>
            <div className="form-group">
            <label>Confirm Password</label>
            <input className="form-control" type="password" name="confirmpassword" onChange={(e)=>setConfirmpassword(e.target.value)} placeholder=" confirm password" required   />
            </div>

                <button className="btn btn-primary w-25">Submit</button>
            </form>
            </div>
            </div>
            </div>

        ):(

            <div class="col-md-8 col-lg-9">
            {customer ?(
            <div class="dashboard-panel">
                <div class="panel-widget p-5">                              
                    <div class="font-weight-bold mb-3">SHIPPING INFORMATION</div>
                    <div class="account-info">
                        <strong>COUNTRY:</strong>
                        <p>{customer.provience}</p>
                    </div>
                    <div class="account-info">
                        <strong>DISTRICT:</strong>
                        <p>{customer.district}</p>
                    </div>
                    <div class="account-info">
                        <strong>CITY:</strong>
                        <p>{customer.city}</p>
                    </div>
                    <div class="account-info">
                        <strong>STREET:</strong>
                        <p>{customer.street}</p>
                    </div>
                    <div className="account-info">
                    <Link to="/edit" className="btn btn-border">Edit Address</Link>
                </div>
                   
                </div>
            </div>
            ):(
                <div class="dashboard-panel">
                <div class="panel-widget p-5"> 
                    <strong className="text-uppercase">You have no saved addresses</strong>
                    <br/>
                    <p className="profileaddp">You will see your saved address when you add a new address.</p>
                    <div className="account-info my-5">
                    <Link to="/address" className="btn btn-primary w-25">Add Address</Link>
                    </div>
                </div>
                </div>
            )}
           
        </div>
        )}
           
</div>


        </div>
        </section>
        </main>
    )
    
}

export default User
