import React,{useEffect, useState} from 'react'
import axios from 'axios';
import { useHistory } from 'react-router';
import $ from 'jquery'
import Editaddress from './checkout/Editaddress'


function Edit() {
    

    return (
      
        <div className="container py-5 w-50 mx-auto justify-content-center">
        <Editaddress/>
      </div>
       
    )
}

export default Edit
