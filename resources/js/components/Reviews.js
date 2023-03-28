
import React,{useEffect,useState} from 'react'
import $ from 'jquery'
import { withRouter} from 'react-router'



function Reviews(props) {

   const[image,setImage] = useState([])
    const [com, setCom] = useState('')
    const[star,setStar] = useState(0)
    const[order,setOrder] = useState([])
    const[product, setProduct] = useState([])


    useEffect(() => {

      $('#main-overlay').show();

      const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token')
        }
      }
      if(localStorage.getItem('token')){
        axios.get('/buyers/'+props.pid, config).then(response=>{
          setOrder(response.data) 
          $('#main-overlay').hide();
        })

        axios.get('/single-product/'+props.pid).then(response=>{
          setProduct(response.data)
          
        })

      }
  return () => {
  setOrder([])
  }
    }, [props])



    function comment(e){
      $('#sec-overlay').show()

      e.preventDefault();

        const data = new FormData()
        data.append('comment',com)
        data.append('product',props.pid)
        data.append('rating',star)
        data.append('seller_id',product.seller_id)
        
        for(var i=0;i<image.length;i++) {
            data.append('image[]',image[i])
        }

      const config = {
        headers: {
          Authorization: 'Bearer '+localStorage.getItem('token'),
          'content-type': 'multipart/form-data',
        }
      }
      axios.post('/comment',data,config).then(response=>{
        //   console.log(response.data)
        localStorage.setItem('notification',true)
        props.history.push('/orderlist')
        $('textarea[name="comment"]').val('')
        $('#sec-overlay').hide()

      }).catch(error=>{
        console.log(error.request.response)
        $('#sec-overlay').hide()

      })
    }

    $(document).ready(function(){
      $("input[type='radio']").click(function(){
      var sim = $("input[type='radio']:checked").val();
      setStar(sim)
      // alert(sim);
      if (sim<3) { $('.myratings').css('color','red'); $(".myratings").text(sim); }else{ $('.myratings').css('color','green'); $(".myratings").text(sim); } }); 
    });


    return (
        <div>
        {order.id ? (
            <form onSubmit ={(e)=>comment(e)} >
                <div className="form-group">
                  <div className="rating-star py-4" >
                  <fieldset className="rating"> 
                    <input type="radio" id="star5" name="rating" value="5" /><label className="full" htmlFor="star5" title="Awesome - 5 stars"></label><input type="radio" id="star4" name="rating" value="4" /><label className="full" htmlFor="star4" title="Pretty good - 4 stars"></label>  <input type="radio" id="star3" name="rating" value="3" /><label className="full" htmlFor="star3" title="Meh - 3 stars"></label> <input type="radio" id="star2" name="rating" value="2" /><label className="full" htmlFor="star2" title="Kinda bad - 2 stars"></label> <input type="radio" id="star1" name="rating" value="1" /><label className="full" htmlFor="star1" title="Sucks big time - 1 star"></label>
                    </fieldset>
                </div>
              </div>
              <br/>
              <div className="form-group">
                <label className="w-100" htmlFor="comment ">Comment:</label>
                <textarea className="form-control" rows="2" name="comment" onChange={(e)=> setCom(e.target.value)} placeholder ="anything about this product"></textarea>
              </div>
              <div className="form-group">
              <label>Upload Image</label>
              <input type="file" name="image" onChange={(e)=>setImage(e.target.files)} multiple/>
              </div>
                <button className="btn btn-primary" >Submit</button>
            </form>
              )
            :
            (null)
          }
        </div>
    )
}

export default withRouter(Reviews)
