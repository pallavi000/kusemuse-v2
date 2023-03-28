import React from 'react'
import {Link} from 'react-router-dom'
import ERROR from '../../../../public/assets/images/404.jpg'

function ErrorPage() {
    return (
        <main>
        <section className="not-found">
            <div className="container">
                <div className="not-found-page text-center">
                  <div className="404-img">
                    <img className="img-fluid" src={ERROR}/>
                  </div>
                  <div className="404-text">
                      <h3 className="my-5">Page not found!</h3>
                      <p>The page you’re trying to visit doesn’t exist.</p>
                      <Link to="/" className="btn btn-primary my-5">Back to Homepage</Link>
                  </div>
                </div>
            </div>
        </section>

    </main>
    )
}

export default ErrorPage
