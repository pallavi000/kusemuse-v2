import React,{useEffect,useState} from 'react'
import {withRouter} from 'react-router-dom'

function Protected(props) {
    const Cmp = props.Cmp;

    if(!localStorage.getItem('token')){
        props.history.push('/signin')
        return false;
       }
 


    return (
        <div>
              <Cmp {...props} />
        </div>
    )
}

export default withRouter(Protected)
