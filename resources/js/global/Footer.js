import React, { useState,useEffect } from 'react'
import { ToastProvider, useToasts } from 'react-toast-notifications'
import Logo from '../../../public/assets/images/logo-white.png'
import BOXimg from '../../../public/assets/images/icons/box-2.png'
import BRAND from '../../../public/assets/images/icons/brand.png'
import SHIPPING from '../../../public/assets/images/icons/shipping.png'
import SECURE from '../../../public/assets/images/icons/secure.png'
import RETURN from '../../../public/assets/images/icons/return.png'
import {Link,withRouter} from 'react-router-dom'
import axios from 'axios'


function Notification(props){
    const { addToast } = useToasts()
    useEffect(() => {
        addToast(props.notification, {
            appearance: 'success',
            autoDismiss: true,
            autoDismissTimeout: 3000
        })
    }, [props])
    return(
        <div></div>
    )  

}



function Footer() {

    const[email,setEmail] = useState('')
    const[success,setSuccess] = useState(false)


    function subscription(e){
        e.preventDefault(e)
        const data={
            email
        }
        axios.post('/subscription',data).then(response=>{
            setSuccess(response.data)
            setTimeout(() => {
                setSuccess(false)
            }, 4000);
        }).catch(err=>{
            console.log(err.request.response)
        })
    }





    return (

<main>
<section className="features clearfix py-4">
            <div className="container">
            {success && 
        <ToastProvider>
            <Notification notification={success}/>
        </ToastProvider>}
                <div className="bottom-features">
                    <div className="pf-list position-relative">
                        <span className="pf-icon">
                          <img src={BOXimg} className="img-fluid"/>
                        </span>
                        <h6 className="text-capitalize font-semibold mt-2 mb-0">Original Products</h6>
                    </div>
                    <div className="pf-list position-relative">
                        <span className="pf-icon">
                          <img src={BRAND} className="img-fluid"/>
                        </span>
                        <h6 className="text-capitalize font-semibold mt-2 mb-0">Nepali Brand</h6>
                    </div>
                    <div className="pf-list position-relative">
                        <span className="pf-icon">
                          <img src={SHIPPING} className="img-fluid"/>
                        </span>
                        <h6 className="text-capitalize font-semibold mt-2 mb-0">Express Shipping</h6>
                    </div>
                    <div className="pf-list position-relative">
                        <span className="pf-icon">
                          <img src={SECURE} className="img-fluid"/>
                        </span>
                        <h6 className="text-capitalize font-semibold mt-2 mb-0">Secure payment system</h6>
                    </div>
                    <div className="pf-list position-relative">
                        <span className="pf-icon">
                          <img src={RETURN} className="img-fluid"/>
                        </span>
                        <h6 className="text-capitalize font-semibold mt-2 mb-0">Return Policy</h6>
                    </div>
                </div>
            </div>
        </section>
        <footer className="l-footer bg-dark py-100">
        <div className="container">
            <div className="footer-main mb-4">
                <div className="row">
                    <div className="col-sm-4 col-md-3 col-lg-2">
                        <div className="footer-widget">
                            <h4 className="widget-title mt-0 mb-30 text-white">shop</h4>
                            <div className="footer-menu">
                                <ul>
                                  <li><a href="">Men</a></li>
                                  <li><a href="">Women</a></li>
                                  <li><a href="">Unisex</a></li>
                                  <li><a href="">Home & Living</a></li>
                                  <li><a href="">Kusemuse HOME</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div className="col-sm-4 col-md-3 col-lg-2">
                    <div className="footer-widget">
                        <h4 className="widget-title mt-0 mb-30 text-white">more</h4>
                        <div className="footer-menu">
                            <ul>
                              <li><Link to="/about-us">About Kusemuse</Link></li>
                              <li><Link to="/contact-us">Contact</Link></li>
                              <li><a href="">My account</a></li>
                              <li><a href="">Track order </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div className="col-sm-4 col-md-2 col-lg-4">
                <div className="footer-widget">
                    <h4 className="widget-title mt-0 mb-30 text-white">help</h4>
                    <div className="footer-menu">
                        <ul>
                          <li><a href="">FAQ</a></li>
                          <li><a href="">Warranty</a></li>
                          <li><a href="">Return Policy</a></li>
                          <li><a href="">Payment Policy</a></li>
                          <li><Link to="/terms-and-condition">Terms and Conditions</Link></li>
                          <li><a href="">Return Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div className="col-sm-12 col-md-4 col-lg-4">
                        <div className="footer-widget">
                            <h4 className="widget-title mt-0 mb-30 text-white">stay in touch</h4>

                            <div className="subscribe-form">
                
                                <form className="form position-relative" onSubmit={(e)=>subscription(e)}>
                                    <div className="form-row align-items-center">
                                        <div className="col-auto">
                                          <input type="email" className="form-control" name="email" onChange={(e)=>setEmail(e.target.value)} id="inlineFormInput" placeholder="Enter your email address.." required/>
                                        </div>
                                    </div>
                                    <button type="submit" className="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="footer-bottom">
                <div className="footer-social text-center mb-4">
                    <a href="" className="d-inline"><i className="fa fa-facebook-f"></i></a>
                    <a href="" className="d-inline"><i className="fa fa-twitter"></i></a>
                    <a href="" className="d-inline"><i className="fa fa-instagram"></i></a>
                    <a href="" className="d-inline"><i className="fa fa-youtube"></i></a>
                </div>
                <div className="footer-bottom-desc text-center">
                    <a href="index.html" className="d-inline-block mb-3">
                        <img src={Logo}/>
                    </a>
                    <div className="col-sm-12 offset-md-1 col-md-10">
                        <p className="mb-0">
                        A Premium product of thakuri Creation Pvt. Ltd.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </main>
    )
}


export default withRouter(Footer)
