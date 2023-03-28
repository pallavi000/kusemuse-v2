import axios from "axios";
import React, { useEffect, useState } from "react";
import KhaltiCheckout from "khalti-checkout-web";
import { v4 as uuidv4 } from "uuid";
import ESEWA from "../../../public/images/esewa.png";
import KHALTI from "../../../public/images/khalti.png";
import COD from "../../../public/images/cod.png";

function Payment(props) {
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);
    const [fee, setFee] = useState(0);
    const [items, setItems] = useState([]);
    const [coupon, setCoupon] = useState(0);

    useEffect(() => {
        $("#main-overlay").show();

        // const config = {
        //     headers: {
        //       Authorization: 'Bearer '+localStorage.getItem('token')
        //     }
        //   }
        //   axios.get('/cartcount',config).then(item=>{
        //     console.log(item.data)
        //     setCart(item.data)
        //    var inttotal = 0;
        //     item.data.forEach(item=>{
        //         var value = item.price*item.quantity;
        //         inttotal += value;
        //     });
        //     setTotal(inttotal)
        // })
        if (!props.location.query) {
            props.history.push("/checkout");
        } else {
            setTotal(
                props.location.query.total +
                    props.location.query.fee -
                    props.location.query.coupon_discount
            );
            setItems(props.location.query.item);
            setFee(props.location.query.fee);
            setCoupon(props.location.query.coupon_discount);
        }

        $("#main-overlay").hide();
    }, [props]);

    function cod() {
        $("#sec-overlay").show();
        var txn = uuidv4();
        payment("COD", "pending", total, txn, "COD");
    }

    function payment(
        payment_method,
        payment_status,
        amount,
        transaction_id,
        payment_type
    ) {
        const config = {
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
        };
        const data = {
            coupon_discount: props.location.query.coupon_discount,
            coupon_pid: props.location.query.coupon_pid,
            couponID: props.location.query.couponId,
            fee: props.location.query.fee,
            payment_method: payment_method,
            payment_status: payment_status,
            days: props.location.query.days,
            amount: amount,
            transaction_id: transaction_id,
            payment_type: payment_type,
            note: props.location.query.note
        };

        axios
            .post("/payment", data, config)
            .then(response => {
                localStorage.setItem("notification", "true");
                $("#sec-overlay").hide();
                props.history.push("/order-received/" + transaction_id);
                console.log(response.data);
            })
            .catch(error => {
                console.log(error.request.response);
                $("#sec-overlay").hide();
                localStorage.setItem("payment_error", error.request.response);
                props.history.push("/cartitem");
            });
    }

    function khalti() {
        let config = {
            // replace this key with yours
            publicKey: "test_public_key_28a78100166644c99e6692c7b0a4e7dd",
            productIdentity: "1234567890",
            productName: "Ecommerce Product",
            productUrl: "http://127.0.0.1:8000",
            eventHandler: {
                onSuccess(payload) {
                    const config = {
                        headers: {
                            Authorization:
                                "Bearer " + localStorage.getItem("token")
                        }
                    };
                    // hit merchant api for initiating verfication
                    console.log(payload);
                    $("#sec-overlay").show();

                    axios
                        .post("/paymentverify", payload, config)
                        .then(response => {
                            payment(
                                "khalti",
                                "done",
                                response.data.amount,
                                response.data.idx,
                                response.data.type.name
                            );
                            console.log(response.data);
                        });
                },
                // onError handler is optional
                onError(error) {
                    // handle errors
                    console.log(error);
                },
                onClose() {
                    console.log("widget is closing");
                }
            },
            paymentPreference: [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT"
            ]
        };

        let checkout = new KhaltiCheckout(config);

        // minimum transaction amount must be 10, i.e 1000 in paisa.
        checkout.show({ amount: 100 * 100 });
    }

    function esewa() {
        //Development
        var path = "https://esewa.com.np/epay/main";
        var params = {
            amt: total - fee + parseInt(coupon),
            psc: 0,
            pdc: fee,
            txAmt: 0,
            tAmt: total,
            pid: uuidv4(),
            scd: "NP-ES-THAKURIC",
            su: "https://kusemuse.com/esewaSuccess",
            fu: "https://kusemuse.com/esewaFailed"
        };

        var edata = {
            cd: props.location.query.coupon_discount,
            cpid: props.location.query.coupon_pid,
            cid: props.location.query.couponId,
            sd: props.location.query.days,
            ont: props.location.query.note,
            spf: props.location.query.fee
        };
        localStorage.setItem("edata", JSON.stringify(edata));

        var form = document.createElement("form");
        form.setAttribute("method", "POST");
        form.setAttribute("action", path);

        for (var key in params) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }

        document.body.appendChild(form);
        form.submit();
    }

    return (
        <div className="container-fluid py-5 mb-5">
            <div className="pb-5 text-center">
                <h2>Payment</h2>
            </div>
            <div className="row mt-5 justify-content-center">
                <div className="col-md-6">
                    <div className="card p-5 ">
                        <h5 className="card-title text-center">
                            Payment Method
                        </h5>
                        <div class="payment-tab pt-4">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a
                                        class="nav-link active"
                                        id="home-tab"
                                        data-toggle="tab"
                                        href="#home"
                                        role="tab"
                                        aria-controls="home"
                                        aria-selected="true"
                                    >
                                        <span>
                                            <img src={ESEWA} width="100" />
                                        </span>
                                    </a>
                                </li>
                                {/* <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                <span><img src={KHALTI} width="100"/></span>
                            </a>
                          </li> */}
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        id="contact-tab"
                                        data-toggle="tab"
                                        href="#contact"
                                        role="tab"
                                        aria-controls="contact"
                                        aria-selected="false"
                                    >
                                        <span>
                                            <img src={COD} width="100" />
                                        </span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div
                                    class="tab-pane fade show active"
                                    id="home"
                                    role="tabpanel"
                                    aria-labelledby="home-tab"
                                >
                                    <div class="payment-tab-content mt-3">
                                        <h4>ESEWA</h4>
                                        <p>
                                            You will be redirected to eSewa
                                            Payment page
                                        </p>
                                        <button
                                            className="btn btn-primary"
                                            onClick={() => esewa()}
                                        >
                                            Pay Now
                                        </button>
                                    </div>
                                </div>

                                {/* <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                              <div class="payment-tab-content mt-3">
                                  <h4>KHALTI</h4>
                                  <p>
                                  You will be redirected to Khalti Payment page 
                                  </p>
                                  <button className="btn btn-primary" onClick= {()=>khalti()}>Pay Now</button>

                              </div>
                          </div> */}

                                <div
                                    class="tab-pane fade"
                                    id="contact"
                                    role="tabpanel"
                                    aria-labelledby="contact-tab"
                                >
                                    <div class="payment-tab-content mt-3">
                                        <h4>COD</h4>
                                        <p>Pay with cash upon delivery.</p>

                                        <button
                                            className="btn btn-primary"
                                            onClick={() => cod()}
                                        >
                                            Confirm Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* <div className="card-body text-center paymentcard">
        <div className="esewa pointer payment-image"  onClick={()=>esewa()} >
        <img src={ESEWA} />
        </div>
        <div className="khalti pointer payment-image"  onClick= {()=>khalti()}>
        <img src={KHALTI} />
        </div>
        <div className="cod pointer payment-image"  onClick={()=>cod()}>
        <img src={COD} />
        </div>

        </div> */}
                    </div>
                </div>
                <div className="col-md-4">
                    <div className="col-12 col-md-12 col-lg-12 ">
                        <h6 className="mb-7">Order Items ({items.length})</h6>

                        <hr className="my-7" />
                        <ul className="list-group list-group-lg list-group-flush-y list-group-flush-x mb-7">
                            {items.map(item => {
                                return (
                                    <li
                                        className="list-group-item"
                                        key={item.id}
                                    >
                                        <div className="row align-items-center">
                                            <div className="col-4">
                                                <a href="product.html">
                                                    <img
                                                        src={item.product.image}
                                                        alt="..."
                                                        className="img-fluid"
                                                    />
                                                </a>
                                            </div>
                                            <div className="col">
                                                <p className="mb-4 font-size-sm font-weight-bold">
                                                    <a
                                                        className="text-body"
                                                        href="product.html"
                                                    >
                                                        {item.product.name}
                                                    </a>{" "}
                                                    <br />
                                                    <span className="text-muted">
                                                        Rs.{item.price}
                                                    </span>
                                                </p>

                                                <div className="font-size-sm text-muted">
                                                    {item.sizes ? (
                                                        <span>
                                                            Size:{" "}
                                                            {item.sizes.name}
                                                            <br />
                                                        </span>
                                                    ) : null}
                                                    Color: {item.color}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                );
                            })}
                        </ul>

                        <div className="card mb-9 bg-light">
                            <div className="card-body">
                                <ul className="list-group list-group-sm list-group-flush-y list-group-flush-x">
                                    <li className="list-group-item d-flex">
                                        <span>Subtotal</span>{" "}
                                        <span className="ml-auto font-size-sm">
                                            Rs.{total - fee + parseInt(coupon)}
                                        </span>
                                    </li>
                                    <li className="list-group-item d-flex">
                                        <span>Coupon Discount</span>{" "}
                                        <span className="ml-auto font-size-sm">
                                            Rs.{coupon}
                                        </span>
                                    </li>
                                    <li className="list-group-item d-flex">
                                        <span>Shipping</span>{" "}
                                        <span className="ml-auto font-size-sm">
                                            Rs.{fee}
                                        </span>
                                    </li>
                                    <li className="list-group-item d-flex font-size-lg font-weight-bold">
                                        <span>Total</span>{" "}
                                        <span className="ml-auto">
                                            Rs.{total}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p className="mb-7 font-size-xs text-gray-500">
                            Your personal data will be used to process your
                            order, support your experience throughout this
                            website, and for other purposes described in our
                            privacy policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Payment;
