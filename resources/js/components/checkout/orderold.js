<div className="row">
<div className="col-md-12 order-md-2 mb-4">
  <h4 className="d-flex justify-content-between align-items-center mb-3">
    <span className="text-muted">Review Your Order</span>
 
<Link to ="/cartitem" rel="noreferrer" class="editcart">Edit cart</Link>
  </h4>
  <ul className="list-group mb-3">
  {item.map(item=>{
      return(
        <li className="list-group-item  lh-condensed" key={item.id}>
        <div className="row">
        <div className="col-md-4 addImg">
            <img src={item.product.image} />
        </div>
        <div className="col-md-8">
        <h6 className=" mb-3">{item.product.name}</h6>
        {item.sizes ? (
            <small className="text-muted">Size:{item.sizes.name}</small>
        )
          :
      (null)}
        <span className="text-muted float-right">Quantity:{item.quantity}</span>
        <span className="d-block product-price">
        

        <span class="pre_reduction">Rs.{(item.price/(100-item.product.discount)*100).toFixed(0)}</span>
        
        &nbsp;
        <span class="reduction">Rs. {item.price}</span>    
        </span>
        <h6 className=" font-weight-bold mt-3" style={{clear:"both"}}>Rs.{item.price*item.quantity}</h6>
        </div>
        
      
        </div>
       

      </li>
      )
  })}
   
    </ul>
</div>
</div>