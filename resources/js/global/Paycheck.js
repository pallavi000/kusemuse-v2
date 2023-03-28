import React from 'react'
import kuse from '../../../public/assets/images/kuse.png'
import {Link} from 'react-router-dom'

function Paycheck(props) {
    console.log(props)
    return (
   
        <div className="container">
        <div className="pt-5 mb-3 text-center">
        <Link to="/">
          <img className="d-block mx-auto" src={kuse} alt="" width="mx-auto" height="mx-auto"/>
          </Link>
        </div>
        </div>
    )
}

export default Paycheck
