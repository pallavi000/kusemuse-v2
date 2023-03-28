import React,{useEffect,useState} from 'react'
import axios from 'axios'

function AboutUs(props) {
    const[about,setAbout] = useState([])

useEffect(() => {
    $('#main-overlay').show();
    const data = {
        page:'about-us'
    }
  axios.post('/page-content',data).then(response=>{
      console.log(response.data)
      setAbout(response.data)
      $('#main-overlay').hide();
  })
}, [props])


    return (
        <div className="container p-5">
            <h1>About Us</h1>
            <h4>{about.title}</h4>
            <p dangerouslySetInnerHTML={{__html:about.content}}></p>
        </div>
    )
}

export default AboutUs
