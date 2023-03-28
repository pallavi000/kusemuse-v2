import React,{useEffect,useState} from 'react'
import axios from 'axios'

function ContactUs(props) {
    const[contact,setContact] = useState([])

    useEffect(() => {
        $('#main-overlay').show();
        const data = {
            page:'contact-us'
        }
      axios.post('/page-content',data).then(response=>{
          console.log(response.data)
          setContact(response.data)
          $('#main-overlay').hide();
      })
    }, [props])

    return (
        <div className="container p-5 text-center">
            <h1>Contact Us</h1>
            <h4>{contact.title}</h4>
            <p dangerouslySetInnerHTML={{__html:contact.content}}></p>
        </div>
    )
}

export default ContactUs
