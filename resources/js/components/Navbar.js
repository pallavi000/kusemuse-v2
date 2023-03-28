<div>  

      <div id="notification_popup" className="cartpopupp">
        <p className="font-weight-bold">The item was added to your bag!</p>
        <div className="notification_popup">
            <div className="field_section">
                <div className="field_row">
                    <div className="field_column one_half">
                        <button className="btn btn-primary " onClick={closeNot}>Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div id="main-overlay">
    <div className="overlay__inner">
        <div className="overlay__content"><span className="spinner-icon"></span></div>
    </div>
</div>

<div id="sec-overlay">
	<div id="sec-container"></div>
	<div className="overlay__content"><span className="spinner-icon sec"></span></div>
</div>


{props.location.pathname.includes('checkout') || props.location.pathname.includes('payment') ?
(
  <div className="container">
  <div className="pt-5 mb-3 text-center">
  <Link to="/">
    <img className="d-block mx-auto" src={kuse} alt="" width="mx-auto" height="mx-auto"/>
    </Link>
  </div>
  </div>
)
:
(

  <div>
<header className="header py-4">
<div className="container">
<nav class="navbar navbar-expand-lg navbar-dark py-0 pl-0 ">
<a href="/" className="logo d-block">
<img src={LogoImg} className="img-fluid"/>
</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse ml-5" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto text-uppercase">
     
      {cat.map(category=>{
        return(
  
        <li key={category.id} className="nav-item font-medium">
          <Link className="nav-link" to={`/${category.slug}`} style={{color:"white"}}>{category.name}</Link>
        </li>
   
        )
    })}     
    </ul>
    <div className="header-action">
    <div className="header-search">
                  <form method="GET"  acceptCharset="UTF-8" role="form" onSubmit={(e)=>result(e)} className="form">
                    <div className="search-input-group relative">
                        <input className="form-control rounded-pill" onChange={(e)=>setQueries(e.target.value)} data-style="btn-primary"  placeholder="Search entire store.." aria-label="Search entire store.." name="criteria" type="text"/>
                        <button type="sumbit" aria-label="Search">
                            <svg fill="#ffffff" width="24" viewBox="0 0 24 24" stroke="currentColor" className="search w-6 h-6"><path stroklinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                      </div>
                  </form>

                  <li className="nav-item dropdown d-inline-block">
                  <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src={UserImg}/>
                  </a>
                  {localStorage.getItem('token') ?(
                    <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                    <Link className="dropdown-item"  to="/user">profile</Link>
                    <Link className="dropdown-item"  to="/wishlist">Wishlist</Link>
                    <div className="dropdown-item"  onClick={loggedout} >logout</div>
                  </div>
  
                  )
                :
              (
                <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                <Link className="dropdown-item"  to="/signin">Login</Link>
                <Link className="dropdown-item"  to="/signup" >Register</Link>
              </div>
  
              )}
                </li>
                <Link  className="action-icon text-secondary" to="/cartitem" >
                <img src={CartImg}/> {cart}
            </Link>


        </div>
  </div>
  </div>





</nav>

  </div>
</header>
</div>
)
}
</div>