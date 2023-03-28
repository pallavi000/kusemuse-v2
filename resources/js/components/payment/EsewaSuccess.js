import axios from 'axios'
import React,{useEffect} from 'react'
import $ from 'jquery'

function EsewaSuccess(props) {
    const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }


useEffect(() => {
    $('#main-overlay').show();
   var params = new URLSearchParams(props.location.search)
   var oid = params.get('oid')
   var amount = params.get('amt')
   var refId = params.get('refId')

    var params= {
        oid,
        amount,
        refId
    }

    axios.post('/esewa-verification',params,config).then(response=>{
        if(response.data.toLowerCase().includes('success')){

            if(localStorage.getItem('edata')){
               var edata = JSON.parse(localStorage.getItem('edata')) 
            }else{
               var edata = {
                   cd: 0,
                   cpid: 0,
                   cid:0,
                   sd:0,
                   ont:'',
                   spf:0
               }
            }

            localStorage.removeItem('edata')

            const data = {
                coupon_discount: edata.cd,
                coupon_pid:edata.cpid,
                couponID: edata.cid,
                days : edata.sd,
                note :edata.ont,
                fee: edata.spf,
                payment_method: 'esewa',
                payment_status: 'completed',
                amount:amount,
                transaction_id:oid,
                payment_type: refId,
            }
            axios.post('/payment',data,config).then(response=>{            
                localStorage.setItem('notification','true')
                $('#main-overlay').hide();
                props.history.push('/order-received/'+oid)
              
            }).catch(error=>{
                localStorage.setItem('payment_error', error.request.response)
                props.history.push('/cartitem');
                $('#main-overlay');
            })

            
        }else{
            //esewwa error
            console.log('esewa verification failed if statement')
            console.log(response.data)
            props.history.push('/esewaFailed')

        }

    }).catch(err=>{
            console.log(err)
        props.history.push('/esewaFailed')
    })

  
}, [props])



    return (
        <div>
            success
        </div>
    )
}

export default EsewaSuccess
