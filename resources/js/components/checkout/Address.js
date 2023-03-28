import axios from 'axios';
import React,{useState,useRef,useEffect} from 'react'
import { useHistory } from 'react-router-dom';
import $ from 'jquery'
import { create } from 'lodash';


function Address(props) {

  const config = {
    headers: {
      Authorization: 'Bearer '+localStorage.getItem('token')
    }
  }


  const scrolltoPhone = useRef()
    const history = useHistory();
    const[shipdetail,setShipdetail] = useState(false)
    const[cities,setCities] = useState([])
    const[country,setCountry] = useState([])
  const[input, setInput]= useState({
    provience:'',    
    city:'',
    district:'',
    street:'',
    phone:'',
    note:'',
    shiping_provience:'',
    shiping_city:'',
    shiping_district:'',
    shiping_street:'',
    shiping_phone:'',
  })

  useEffect(() => {
  axios.get('/cities',config).then(response=>{
    console.log(response.data)
    setCities(response.data.cities)
    setCountry(response.data.country)
    setInput({
      provience:response.data.country.name,    
      shiping_provience:response.data.country.name,
     
    })
    
  })
  }, [])


// form submit

    function addsubmit(e){
        e.preventDefault();
        const config = {
          headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
          }
        }

        if(input.phone.length !=10){
          $('.invalidphone').show()
          scrolltoPhone.current.scrollIntoView()
          return false;
        } else {
          $('.invalidphone').hide()
          
        }
        $('#sec-overlay').show()
        if($('.shipadd').is(':checked')){

        const data = {
          shiping_provience:input.provience,
          shiping_city:input.city,
          shiping_district:input.district,
          shiping_street:input.street,
          shiping_phone:input.phone,         
        }
        var newinput = {...input, ...data}
        
      } else {
        var newinput = input
      }


     
       axios.post('/address',newinput,config).then(response=>{
        $('#sec-overlay').hide()

       history.push('/checkout')
       }).catch(error=>{
         console.log(error.request.response)
         $('#sec-overlay').hide()

       })
       
    }


    function change(e){
      setInput ({
        ... input,
        [e.target.name]: e.target.value,
      })
    }

    

  function shipping(e){
    if($('.shipadd').is(':checked')){
      $('.shipform').hide()
    }else{
      $('.shipform').show()
    }
 
  }

  function shipping(e){
    if($('.shipadd').is(':checked')){
      setShipdetail(false)
    }else{
      setShipdetail(true)
    }
 
  }

$(document).ready(function(){
  $('#countryId').on('change',function(e){
    var country_name =  $(this).val();
      $('.district-option').hide();
      $('.'+country_name).show();
  })
})
 

    return (
        <div>
        <div className="container">
        <div className="">
        <h6 className="mb-7">Billing Details</h6>        
        </div>
        <form className="mx-auto py-4" onSubmit={(e)=>addsubmit(e)}>

        <div className="form-group phone-group mb-3">
          <div>
              <label className="phone-level ">Mobile Number*</label>
              <div className="phone-inner" ref={scrolltoPhone}>
                <div className="phonee">+977</div>
                  <input name="phone" placeholder="Please enter a valid mobile number" onChange={(e)=>change(e)} className="phone-input form-control-add" maxLength="10" required/>

              </div>
              <div className="invalid-feedback invalidphone">Phone number must be 10 digit !</div>

            </div>
        </div>


      <div className="row">
      <div className="col-12 col-md-6">
      <div className="form-group">
        <label className="" >Country</label>
      <input type="text" className="d-block w-100 form-control-add" defaultValue={country.name} name="provience" required readOnly/>
        {
        //   <select className=" d-block w-100 form-control-add" onChange={(e)=>change(e)} name="provience"  id="countryId" required readOnly>
   
        //   <option value="" filter="000">Please Select Country</option>
        //   {country.map(cont=>{
        //     return(
        //       <option value={cont.name} key={cont.name}>{cont.name}</option>
      
        //     )
        //   })}
        // </select>
        }

</div>
</div>
<div className="col-12 col-md-6">
<div className="form-group">
	<label className="">District</label>

	<select name="district"  className=" d-block w-100 form-control-add " onChange={(e)=>change(e)} id="district" required>
      <option value="">Please Select District</option>
      {cities.map(distric=>{
        return(
          <option value={distric.name} className={`district-option ${distric.country?.name}`} key={distric.id}>{distric.name}</option>
        )
      })}
	</select>

  </div>
      </div>
    </div>
        
       
        <div className="form-group ">
        <label className="">City </label>
        <input type="text" className="form-control-add form-control-add-sm"  name="city" onChange={(e)=>change(e)}  placeholder="City" required/>
        </div>
       
        <div className="form-group">
        <label  className="">Street </label>
        <input type="text" className="form-control-add form-control-add-sm" name="street" onChange={(e)=>change(e)}   placeholder="Street" required/>
        </div>
       

        {shipdetail ? (
          <div className="mx-auto py-4 shipform" >
          <h6 className="mb-7">Shipping Details</h6>        
        <div className="form-group phone-group mb-3"><div>
        <label className="phone-level  ">Mobile Number*</label>
        <div className="phone-inner">
          <div className="phonee">+977</div>
             <input name="shiping_phone" placeholder="Please enter a valid mobile number" onChange={(e)=>change(e)} className="phone-input form-control-add" maxLength="10" required />

         </div>
         <div className="invalid-feedback invalidphone">Phone number must be 10 digit !</div>

        </div>
      </div>
      <div className="row">
      <div className="col-12 col-md-6">
        <div className="form-group">
        <label className="">Country</label>
        <input type="text" className="d-block w-100 form-control-add" defaultValue={country.name} name="provience" required readOnly/>

  </div>
  </div>
  <div className="col-12 col-md-6">
  <div className="form-group">
  <label className="">District</label>
  <select name="shiping_district"  className=" d-block w-100 form-control-add " onChange={(e)=>change(e)} id="district" required>
      <option value="">Please Select District</option>
      {cities.map(distric=>{
        return(
          <option value={distric.name} className={`district-option ${distric.country?.name}`} key={distric.id}>{distric.name}</option>
        )
      })}
	</select>
      </div>
      </div>
    
      
    </div>
        
       
        <div className="form-group ">
        <label className="">City </label>
        <input type="text" className="form-control-add"  name="shiping_city" onChange={(e)=>change(e)}  placeholder="City" required />
        </div>
       
        
  
        <div className="form-group">
        <label className="">Street </label>
        <input type="text" className="form-control-add" name="shiping_street" onChange={(e)=>change(e)}   placeholder="Street" required/>
        </div>
        </div>
        ):(null)}
        

      

                     
      <input type="checkbox"  className="shipadd"  onChange={(e)=>shipping(e)} defaultChecked/> Ship to a same address?

      <div className="col-12 mt-5  px-0">
      <button className="btn btn-sm btn-outline-dark" type="submit">
        Save Address
      </button>
      </div>
        
      </form>
      </div>
       </div>
    )
}

export default Address
