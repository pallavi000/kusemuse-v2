import React,{useEffect,useState} from 'react'
import axios from 'axios'

function TermsAndCondition(props) {
    const[terms,setTerms] = useState([])

    useEffect(() => {
        $('#main-overlay').show();
        const data = {
            page:'terms-and-condition'
        }
      axios.post('/page-content',data).then(response=>{
          console.log(response.data)
          setTerms(response.data)
          $('#main-overlay').hide();
      })
    }, [props])

    return (
        <div className="container p-5 text-center">
        <h1>Terms and condition</h1>
        <h4>{terms.title}</h4>
            <p dangerouslySetInnerHTML={{__html:terms.content}}></p>
        </div>
    )
}

export default TermsAndCondition
