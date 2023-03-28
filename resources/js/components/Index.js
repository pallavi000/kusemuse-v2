import axios from "axios";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import React, { Component, useEffect } from "react";
// import Navbar from './Navbar';
import Home from "./Home";
import Product_detail from "./Product_detail";
import Category from "./Category";
import Product from "./Product";
import Login from "./Login";
import Cartitem from "./Cartitem";
import User from "./User";
import Add from "./Add";
import AddContextProvider from "./Cartcontext";

import ReactDOM from "react-dom";
import Edit from "./Edit";
import Checkout from "./checkout/Checkout";
import Payment from "./Payment";
import Orderlist from "./Orderlist";
import SignUp from "./SignUp";
import Item from "./wishlist/Item";
import Navbar from "../global/Navbar";
import Footer from "../global/Footer";
import Boutique from "./boutique/Boutique";
import Designer from "./boutique/Designer";
import Protected from "./Protected";
import CategoryPage from "./category/CategoryPage";
import BrandPage from "./BrandPage";
import Search from "./Search";
import OrderReceived from "./OrderReceived";
import Forgotpassword from "./Forgotpassword";
import SetPassword from "./SetPassword";
import Reviews from "./Reviews";
import OrderReview from "./OrderReview";
import EsewaSuccess from "./payment/EsewaSuccess";
import EsewaFailed from "./payment/EsewaFailed";
import AboutUs from "./pages/AboutUs";
import ContactUs from "./pages/ContactUs";
import TermssAndCondition from "./pages/TermsAndCondition";
import ErrorPage from "./pages/ErrorPage";
import OrderTracking from "./OrderTracking";

axios.defaults.baseURL = "https://kusemuse.com/api";

function Index(props) {
    return (
        <div>
            <Router>
                <AddContextProvider>
                    <Navbar />
                    <Switch>
                        <Route exact path="/" component={Home} />
                        <Route exact path="/product">
                            <Protected Cmp={Product} />
                        </Route>
                        <Route
                            exact
                            path="/category/:slug"
                            component={Category}
                        />
                        <Route
                            exact
                            path="/signin/:parameter?"
                            component={Login}
                        />
                        <Route exact path="/cartitem" component={Cartitem} />
                        <Route exact path="/user/:parameter?">
                            <Protected Cmp={User} />
                        </Route>
                        <Route exact path="/address">
                            <Protected Cmp={Add} />
                        </Route>
                        <Route exact path="/edit">
                            <Protected Cmp={Edit} />
                        </Route>
                        <Route exact path="/checkout" component={Checkout} />
                        <Route exact path="/payment">
                            <Protected Cmp={Payment} />
                        </Route>
                        <Route exact path="/orderlist">
                            <Protected Cmp={Orderlist} />
                        </Route>
                        <Route exact path="/wishlist">
                            <Protected Cmp={Item} />
                        </Route>

                        <Route
                            exact
                            path="/track-your-order"
                            component={OrderTracking}
                        />
                        <Route exact path="/contact-us" component={ContactUs} />
                        <Route exact path="/about-us" component={AboutUs} />
                        <Route
                            exact
                            path="/terms-and-condition"
                            component={TermssAndCondition}
                        />

                        <Route
                            exact
                            path="/forgotpassword"
                            component={Forgotpassword}
                        />
                        <Route
                            exact
                            path="/setpassword/:slug"
                            component={SetPassword}
                        />

                        <Route
                            exact
                            path="/search/:parameter?"
                            component={Search}
                        />

                        <Route
                            exact
                            path="/product-detail/:id"
                            component={Product_detail}
                        />
                        <Route
                            exact
                            path="/orders/review/:id"
                            component={OrderReview}
                        />

                        <Route
                            exact
                            path="/signup/:parameter?"
                            component={SignUp}
                        />

                        <Route
                            exaxct
                            path="/boutique/:slug?"
                            component={Boutique}
                        />

                        <Route
                            exaxct
                            path="/designer/:slug"
                            component={Designer}
                        />
                        <Route
                            exact
                            path="/order-received/:id"
                            component={OrderReceived}
                        />

                        <Route
                            exact
                            path="/esewaSuccess"
                            component={EsewaSuccess}
                        />
                        <Route
                            exact
                            path="/esewaFailed"
                            component={EsewaFailed}
                        />

                        <Route
                            exact
                            path="/brand/:slug"
                            component={BrandPage}
                        />
                        <Route exact path="/:slug" component={CategoryPage} />

                        <Route component={ErrorPage} />
                    </Switch>
                    <Footer />
                </AddContextProvider>
            </Router>
        </div>
    );
}

export default Index;

if (document.getElementById("app")) {
    ReactDOM.render(<Index />, document.getElementById("app"));
}
