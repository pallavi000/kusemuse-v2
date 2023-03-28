import axios from 'axios';
import React,{useState} from 'react'
import Address from './checkout/Address'
import { useHistory } from 'react-router-dom';
import $ from 'jquery'


function Add(props) {
  //   const history = useHistory();
  // const[input, setInput]= useState({
  //   provience:'',
  //   city:'',
  //   district:'',
  //   street:'',
  //   phone:'',
  // })



  //   function addsubmit(e){
  //       e.preventDefault();
      
  //      console.log(input)
  //       const config = {
  //         headers: {
  //           Authorization: 'Bearer '+localStorage.getItem('token')
  //         }
  //       }

     
  //      axios.post('/address',input,config).then(response=>{
  //      history.push('/user')
  //      }).catch(error=>{
  //        console.log(error)
  //      })
       
  //   }


  //   function change(e){
  //     setInput ({
  //       ... input,
  //       [e.target.name]: e.target.value,
  //     })
  //   }

  //   $(document).ready(function() {
  //     //get the selected option value
  //     let selDefault = $('#state option:selected').attr('filter');
  //     //lets make sure it is displayed
  //     $('.'+selDefault).show();
  //     //lets add selected attribute to first class
  //     let attr = document.getElementsByClassName(selDefault)[0];
  //     //attr.setAttribute('selected','selected');
    
  //     //lets cut out the displayed items
  //     let states = ['201','202','203','204','205','206','207'];
  //     states.splice($.inArray(selDefault,states), 1);
    
  //     //lets hide remaining items
  //     $.each(states, function(index,value) {
  //       $('.'+value).hide();
  //       //we'll remove selected attribute from hiding options if there is any //clean it
  //       $('.'+value).removeAttr('selected','selected');
  //     });
    
    
  //     //Let's work on select onchange
  //     $('#state').on('change', function() {
  //       //Repeating the same method as above
  //       let selectVal = $('#state option:selected').attr('filter');
  //       $('.'+selectVal).show();
    
  //       let changedAttr = document.getElementsByClassName(selectVal)[0];
  //       //changedAttr.setAttribute('selected','selected');
    
  //       let provinces = ['201','202','203','204','205','206','207'];
  //       provinces.splice($.inArray(selectVal,provinces), 1);
    
  //       $.each(provinces, function(index,value) {
  //         $('.'+value).hide();
  //         $('.'+value).removeAttr('selected','selected');
  //       });
  //     });
    
  //   });
    

  
    return (
        <div>
        <Address/>
        </div>
    )
}

export default Add
