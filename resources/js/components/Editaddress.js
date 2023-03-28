import React,{useEffect, useState} from 'react'
import axios from 'axios';
import { useHistory } from 'react-router';
import $ from 'jquery'


function Editaddress(props) {
    const[edit, setEdit]= useState([])
    const history = useHistory()
    const[address, setAddress]= useState({
        provience:'',
        city:'',
        district:'',
        street:'',
        phone:'',
    })
    
    useEffect(() => {
        const config = {
            headers: {
              Authorization: 'Bearer '+localStorage.getItem('token')
            }
          }
      axios .get('/editaddress',config).then(response=>{
          setEdit(response.data)
          console.log(response.data)
          setAddress({
              provience:response.data.provience,
              city:response.data.city,
              district:response.data.district,
              street:response.data.street,
              phone:response.data.phone,

          })
      })
    }, [])



   function changeadd(e){
       setAddress({
           ...address,
           [e.target.name] : e.target.value
        })

   }

   function addsubmit(e){
       e.preventDefault()
      console.log(address)
      
      const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }

      axios.post('/replaceadd',address,config).then(response=>{
        history.push('/user')
        console.log(response.data)
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

    return (
      
        <div className="container py-5 w-50 mx-auto">
        <form className="w-100 mx-auto" onSubmit={(e)=>addsubmit(e)} >
        <div className="phone-group mb-3"><div>
        <label className="phone-level">Mobile number*</label>
        <div className="phone-inner">
          <div className="phonee">+977</div>
             <input name="phone" placeholder="Please enter a valid mobile number" className="phone-input"  name="phone"  defaultValue={edit.phone} onChange={(e)=>changeadd(e)}  />
         </div>
        </div>
      </div>
      
   

    <div className="row">
    <div className="col-md-12 mb-3">
      <label>State/Province</label>

    <select className="custom-select d-block w-100" onChange={(e)=>changeadd(e)} name="provience" id="state" required>
	  <option value="" filter="000">Please Select Province</option>
		<option value="Province No. 1" filter="201">Province No. 1</option>
		<option value="Province No. 2" filter="202">Province No. 2</option>
		<option value="Province No. 3" filter="203">Province No. 3</option>
		<option value="Province No. 4" filter="204">Province No. 4</option>
		<option value="Province No. 5" filter="205">Province No. 5</option>
		<option value="Province No. 6" filter="206">Province No. 6</option>
		<option value="Province No. 7" filter="207">Province No. 7</option>
	</select>


	<label>District</label>
	<select name="district"  className="custom-select d-block w-100" onChange={(e)=>changeadd(e)} id="district" required>

      <option value="">Please Select District</option>
	        <option value="Bhojpur" className="201">Bhojpur</option>
	        <option value="Dhankutta" className="201">Dhankutta</option>
	        <option value="Ilam" className="201">Ilam</option>
	        <option value="Jhapa" className="201">Jhapa</option>
	        <option value="Khotang" className="201">Khotang</option>
	        <option value="Morang" className="201">Morang</option>
	        <option value="Okhaldunga" className="201">Okhaldunga</option>
	        <option value="Panchthar" className="201">Panchthar</option>
	        <option value="Sankhuwasabha" className="201">Sankhuwasabha</option>
	        <option value="Solukhumbu" className="201">Solukhumbu</option>
	        <option value="Sunsari" className="201">Sunsari</option>
	        <option value="Taplejung" className="201">Taplejung</option>
	        <option value="Terhathum" className="201">Terhathum</option>
	        <option value="Udaypur" className="201">Udaypur</option>

	 
	        <option value="Bara" className="202">Bara</option>
	        <option value="Parsa" className="202">Parsa</option>
	        <option value="Dhanusa" className="202">Dhanusa</option>
	        <option value="Mahottari" className="202">Mahottari</option>
	        <option value="Saptari" className="202">Saptari</option>
	        <option value="Sarlahi" className="202">Sarlahi</option>
	        <option value="Siraha" className="202">Siraha</option>	        
	        <option value="Rauthat" className="202">Rauthat</option>

	 
	        <option value="Bhaktapur" className="203">Bhaktapur</option>
	        <option value="Chitwan" className="203">Chitwan</option>
	        <option value="Dhading" className="203">Dhading</option>
	        <option value="Dolkha" className="203">Dolkha</option>
	        <option value="Kathmandu" className="203">Kathmandu</option>
	        <option value="Kavreplanchauk" className="203">Kavreplanchauk</option>	        
	        <option value="Lalitpur" className="203">Lalitpur</option>
	        <option value="Makwanpur" className="203">Makwanpur</option>
	        <option value="Nuwakot" className="203">Nuwakot</option>
	        <option value="Ramechhap" className="203">Ramechhap</option>
	        <option value="Rasuwa" className="203">Rasuwa</option>
	        <option value="Sindhuli" className="203">Sindhuli</option>
	        <option value="Sindhupalchauk" className="203">Sindhupalchauk</option>

	        <option value="Baglung" className="204">Baglung</option>
	        <option value="Gorkha" className="204">Gorkha</option>
	        <option value="Kaski" className="204">Kaski</option>
	        <option value="Lamjung" className="204">Lamjung</option>
	        <option value="Manag" className="204">Manag</option>
	        <option value="Mustang" className="204">Mustang</option>
	        <option value="Myagdi" className="204">Myagdi</option>
	        <option value="Nawalpur" className="204">Nawalpur</option>
	        <option value="Parwat" className="204">Parwat</option>
	        <option value="Syangja" className="204">Syangja</option>
	        <option value="Tanahun" className="204">Tanahun</option>

	        <option value="Arghakhanchi" className="205">Arghakhanchi</option>
	        <option value="Banke" className="205">Banke</option>
	        <option value="Bardiya" className="205">Bardiya</option>
	        <option value="Dang Deukhuri" className="205">Dang Deukhuri</option>
	        <option value="Eastern Rukum" className="205">Eastern Rukum</option>
	        <option value="Gulmi" className="205">Gulmi</option>	        
	        <option value="Kapilvastu" className="205">Kapilvastu</option>
	        <option value="Palpa" className="205">Palpa</option>
	        <option value="Parasi" className="205">Parasi</option>
	        <option value="Pyuthan" className="205">Pyuthan</option>
	        <option value="Rolpa" className="205">Rolpa</option>	        
	        <option value="Rupandehi" className="205">Rupandehi</option>

	    
	        <option value="Dailekh" className="206">Dailekh</option>
	        <option value="Dolpa" className="206">Dolpa</option>
	        <option value="Humla" className="206">Humla</option>
	        <option value="Jajarkot" className="206">Jajarkot</option>
	        <option value="Jumla" className="206">Jumla</option>
	        <option value="Kalikot" className="206">Kalikot</option>
	        <option value="Mugu" className="206">Mugu</option>	    	
	        <option value="Salyan" className="206">Salyan</option>
	        <option value="Surkhet" className="206">Surkhet</option>
	        <option value="Western Rukum" className="206">Western Rukum</option>

	    
	        <option value="Achham" className="207">Achham</option>
	        <option value="Baitadi" className="207">Baitadi</option>
	        <option value="Bajhang" className="207">Bajhang</option>
	        <option value="Bajura" className="207">Bajura</option>
	        <option value="Dadeldhura" className="207">Dadeldhura</option>
	        <option value="Darchula" className="207">Darchula</option>
	        <option value="Doti" className="207">Doti</option>
	        <option value="Kailali" className="207">Kailali</option>
	        <option value="Kanchanpur" className="207">Kanchanpur</option>

	</select>
</div></div>
 
        <div className="form-group">
        <label>city </label>
        <input type="text" className="form-control" defaultValue={edit.city} onChange={(e)=>changeadd(e)} name="city"  placeholder="city"/>
        </div>
 
        <div className="form-group">
        <label>street </label>
        <input type="text" className="form-control" name="street" defaultValue={edit.street} onChange={(e)=>changeadd(e)}  placeholder="street"/>
        </div>
       
       
        <button type="submit" className="btn btn-primary">Submit</button>
      </form>
      </div>
       
    )
}

export default Editaddress
