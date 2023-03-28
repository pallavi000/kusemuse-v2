import axios from 'axios';
import React,{useState,useEffect,useRef} from 'react'
import { useHistory } from 'react-router-dom';
import $ from 'jquery'
import { create } from 'lodash';


function Editaddress(props) {
  const scrolltoPhone = useRef()
  const[country,setCountry] = useState([])
  const[cities,setCities] = useState([])
    const history = useHistory();
    const[shipdetail,setShipdetail] = useState(false)
  const[address, setAddress]= useState({
    provience:'',
    city:'',
    district:'',
    street:'',
    phone:'',
    shiping_provience:'',
    shiping_city:'',
    shiping_district:'',
    shiping_street:'',
    shiping_phone:'',
    
  })
 useEffect(() => {
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
      axios .get('/editaddress',config).then(response=>{
          console.log(response.data)
          setAddress({
              provience:response.data.billing.provience,
              city:response.data.billing.city,
              district:response.data.billing.district,
              street:response.data.billing.street,
              phone:response.data.billing.phone,
              shiping_provience:response.data.shipping.provience,
              shiping_city:response.data.shipping.city,
              shiping_district:response.data.shipping.district,
              shiping_street:response.data.shipping.street,
              shiping_phone:response.data.shipping.phone,
          })
      })

      axios.get('/cities',config).then(response=>{
        console.log(response.data)
        setCities(response.data.cities)
        setCountry(response.data.country)
        
      })
    }, [])



    function addsubmit(e){
        e.preventDefault();
        const config = {
          headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
          }
        }

        if(address.phone.length !=10){
          $('.invalidphone').show()
          scrolltoPhone.current.scrollIntoView()
          return false;
        } else {
          $('.invalidphone').hide()
          
        }
        $('#sec-overlay').show()

        if($('.shipadd').is(':checked')){
        const data = {
          shiping_provience:address.provience,
          shiping_city:address.city,
          shiping_district:address.district,
          shiping_street:address.street,
          shiping_phone:address.phone,         
        }
        var newinput = {...address, ...data}
        
      } else {
        var newinput = address
      }
      
       axios.post('/replaceadd',newinput,config).then(response=>{
         console.log(response.data)
        $('#sec-overlay').hide()

       history.push('/checkout')
       }).catch(error=>{
         console.log(error.request.response)
         $('#sec-overlay').hide()

       })
       
    }


    function change(e){
      setAddress ({
        ... address,
        [e.target.name]: e.target.value,
      })
    }

    $(document).ready(function() {
      //get the selected option value
      let selDefault = $('#state option:selected').attr('filter');
      //lets make sure it is displayed
      $('.'+selDefault).show();
      //lets add selected attribute to first class
      let attr = document.getElementsByClassName(selDefault)[0];
      //attr.setAttribute('selected','selected');
    
      //lets cut out the displayed items
      let states = ['201','202','203','204','205','206','207'];
      states.splice($.inArray(selDefault,states), 1);
    
      //lets hide remaining items
      $.each(states, function(index,value) {
        $('.'+value).hide();
        //we'll remove selected attribute from hiding options if there is any //clean it
        $('.'+value).removeAttr('selected','selected');
      });
    
    
      //Let's work on select onchange
      $('#state').on('change', function() {
        //Repeating the same method as above
        let selectVal = $('#state option:selected').attr('filter');
        $('.'+selectVal).show();
    
        let changedAttr = document.getElementsByClassName(selectVal)[0];
        //changedAttr.setAttribute('selected','selected');
    
        let provinces = ['201','202','203','204','205','206','207'];
        provinces.splice($.inArray(selectVal,provinces), 1);
    
        $.each(provinces, function(index,value) {
          $('.'+value).hide();
          $('.'+value).removeAttr('selected','selected');
        });
      });
    
    });

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
        <h6 class="mb-7">Billing Details</h6>        
        </div>
        <form className="mx-auto py-4" onSubmit={(e)=>addsubmit(e)}>
        <div className="form-group phone-group mb-3"><div>
        <label className="phone-level">Mobile number*</label>
        <div className="phone-inner" ref= {scrolltoPhone}>
          <div className="phonee">+977</div>
             <input name="phone" placeholder="Please enter a valid mobile number" defaultValue={address.phone} onChange={(e)=>change(e)} className="phone-input form-control-add" maxLength="10" required/>
             
         </div>
         <div className="invalid-feedback invalidphone">Phone number must be 10 digit !</div>
        </div>
      </div>
      <div className="row">
      <div class="col-12 col-md-6">
      <div className="form-group">

        <label>Country</label>
        <input type="text" className="d-block w-100 form-control-add" defaultValue={country.name} name="provience" required readOnly/>



  </div>
  </div>

  <div class="col-12 col-md-6">
<div className="form-group">
	<label>District</label>
	<select name="district"  className=" d-block w-100 form-control-add" onChange={(e)=>change(e)} id="district" required>

      <option defaultValue={address.district} >{address.district}</option>
	        {cities.map(city=>{
            return(
              city.country?(
                <option value={city.name} className={`district-option ${city.country?.name}`} key={city.id}>{city.name}</option>

              ):(
                <option value={city.name} className={`district-option`} key={city.id}>{city.name}</option>

              )
            )
          })}

	</select>
      </div>
      </div>
    </div>
        
       
        <div className="form-group ">
        <label>City </label>
        <input type="text" className="form-control-add" defaultValue={address.city} name="city" onChange={(e)=>change(e)}  placeholder="City" required/>
        </div>
       
        <div className="form-group">
        <label>Street </label>
        <input type="text" className="form-control-add" name="street" defaultValue={address.street} onChange={(e)=>change(e)}  placeholder="Street" required/>
        </div>
     
     
{shipdetail ? (
<main>
<div className="mx-auto py-4 shipform" >
        <h6 class="mb-7">Shipping Details</h6>        
      <div className="form-group phone-group mb-3"><div>
      <label className="phone-level">Mobile number*</label>
      <div className="phone-inner">
        <div className="phonee">+977</div>
           <input name="shiping_phone"  placeholder="Please enter a valid mobile number" defaultValue={address.shiping_phone} onChange={(e)=>change(e)} className="phone-input form-control-add" maxLength="10" required />

       </div>
       <div className="invalid-feedback invalidphone">Phone number must be 10 digit!</div>

      </div>
    </div>
    <div className="row">
    <div class="col-12 col-md-6">
<div className="form-group">
      <label>Country</label>
      <input type="text" className="d-block w-100 form-control-add" defaultValue={country.name} name="provience" required readOnly/>

</div>
</div>

<div class="col-12 col-md-6">
<div className="form-group">
<label>District</label>

    <select name="shiping_district"  className=" d-block w-100 form-control-add" onChange={(e)=>change(e)} id="district" required>

      <option defaultValue={address.district} >{address.district}</option>
	        {cities.map(city=>{
            return(
              city.country ?(
                <option value={city.name} className={`district-option ${city.country?.name}`} key={city.id}>{city.name}</option>

              ):(
                <option value={city.name} className={`district-option`} key={city.id}>{city.name}</option>
              )
            )
          })}

	</select>


    </div>
    </div>
  </div>
      
     
      <div className="form-group ">
      <label>City </label>
      <input type="text" className="form-control-add"  defaultValue={address.shiping_city}  name="shiping_city" onChange={(e)=>change(e)}  placeholder="City" required />
      </div>
     
      <div className="form-group">
      <label>Street </label>
      <input type="text" className="form-control-add"  name="shiping_street" onChange={(e)=>change(e)} defaultValue={address.shiping_street}   placeholder="Street" required />
      </div>
      </div>


</main>
):(null)}
        

                     
      <input type="checkbox"  className="shipadd"   onChange={(e)=>shipping(e)} defaultChecked/> Ship to a same address?

      <div class="col-12 mt-5  px-0">
      <button class="btn btn-sm btn-outline-dark" type="submit">
       Save Address
      </button>

     </div>
      </form>
      </div>
       </div>
    )
  }

export default Editaddress
