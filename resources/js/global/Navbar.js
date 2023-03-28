import axios from "axios";
import React, { useState, useEffect, useContext } from "react";
import { Link, Redirect, useHistory, withRouter } from "react-router-dom";
import { AddContext } from "../components/Cartcontext";
import LogoImg from "../../../public/assets/images/logo-white.png";
import UserImg from "../../../public/assets/images/user.png";
import CartImg from "../../../public/assets/images/cart.png";
import kuse from "../../../public/assets/images/kuse.png";
import { v4 as uuidv4 } from "uuid";
import Cookies from "universal-cookie";
import $ from "jquery";
//import '../../../node_modules/nanoscroller/bin/javascripts/jquery.nanoscroller'
import nanoScroller from "nanoscroller";

function Navbar(props) {
    const data = useContext(AddContext);
    const { cart } = data;

    const [cat, setCat] = useState([]);
    const [user, setUser] = useState([]);
    const [isLoggedIn, setIsLogedIn] = useState(false);
    const history = useHistory();
    const [query, setQueries] = useState("");

    useEffect(() => {
        const cookies = new Cookies();
        if (localStorage.getItem("token")) {
            var guest_id = cookies.get("guest_id");
            const config = {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            };
            axios
                .get("/checkguest/" + guest_id, config)
                .then(check => {
                    setUser(check.data);
                    setIsLogedIn(true);
                })
                .catch(error => {
                    console.log(error.response);
                });
        } else {
            if (cookies.get("guest_id")) {
            } else {
                var cook = uuidv4();
                cookies.set("guest_id", cook, { path: "/" });
            }
        }
    }, [props]);

    function loggedout() {
        localStorage.removeItem("token");
        history.push("/");
        // setIsLogedIn(false)
    }

    useEffect(() => {
        axios
            .get("/category")
            .then(response => {
                var newCat = response.data;

                newCat = newCat.filter(f => f.slug !== "boutique");

                // console.log(newCat)
                setCat(newCat);

                var mobileNav = $('<div id="mobileNav"></div>').prependTo(
                    ".offcanvas .nano .nano-content"
                );
                mobileNav.append($(".header .my-menu > ul").clone());
                // mobileNav.append($('.header .header-action > .header-search').clone());
                // mobileNav.append($('.header .header-action > .action-icon').clone());
            })
            .catch(error => {
                console.log(error.request.response);
            });
    }, []);

    function loggedout() {
        localStorage.removeItem("token");
        props.history.push("/");
        // setIsLogedIn(false)
    }
    function openNot() {
        $(".cartpopupp").show();
    }

    function closeNot() {
        $(".cartpopupp").hide();
    }

    // search query

    function result(e) {
        e.preventDefault();
        console.log("search result");
        props.history.push({
            pathname: "/search/",
            search: `?q=${query}`
        });
    }

    // offcanvas toggle
    $(function() {
        // scrollbar js initialization
        $(".nano").nanoScroller();

        var hH = $(".header").height();
        //alert(hH);

        // cloning menu items in mobile

        // offcanvas toggle
        $(".toggle a").click(function(e) {
            $(".offcanvas").addClass("show-offcanvas");
            $("body").addClass("pushed");
            $(".body-inactive").fadeIn(350);
            e.preventDefault();
        });

        // closing ups clicking on the screen
        $(".body-inactive, .coff a").click(function(e) {
            $(".offcanvas").removeClass("show-offcanvas");
            $("body").removeClass("pushed");
            $(".body-inactive").fadeOut(150);
            e.preventDefault();
        });
    });

    $(document).ready(function() {
        // Submenu DropDown
        $("#mobileNav li.hasDropdown > a").click(function(e) {
            console.log("dasf");
            findSubMenuDisplay = $(this)
                .parent()
                .children(".dropdown")
                .css("display");
            if (findSubMenuDisplay == "block") {
                $(this)
                    .parent()
                    .children(".dropdown")
                    .slideUp(350);
            } else {
                $(".dropdown").slideUp(350);
                $(this)
                    .parent()
                    .children(".dropdown")
                    .slideDown(350);
            }
            e.preventDefault();
        });
    });

    $("#detailSlider").carousel({
        interval: false
    });

    return (
        <main>
            <div class="body-inactive fixed cover"></div>
            <div class="offcanvas">
                <div class="coff absolute">
                    <a href="">
                        <img src="assets/images/csb.png" width="22" />
                    </a>
                </div>
                <div class="nano">
                    <div class="nano-content"></div>
                </div>
            </div>

            <div id="notification_popup" className="cartpopupp">
                <p className="font-weight-bold">
                    The item was added to your bag!
                </p>
                <div className="notification_popup">
                    <div className="field_section">
                        <div className="field_row">
                            <div className="field_column one_half">
                                <button
                                    className="btn btn-primary "
                                    onClick={closeNot}
                                >
                                    Continue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-overlay">
                <div className="overlay__inner">
                    <div className="overlay__content">
                        <span className="spinner-icon"></span>
                    </div>
                </div>
            </div>

            <div id="sec-overlay">
                <div id="sec-container"></div>
                <div className="overlay__content">
                    <span className="spinner-icon sec"></span>
                </div>
            </div>

            {/* {props.location.pathname.includes('checkout') || props.location.pathname.includes('payment') ?
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
( */}

            <header class="header py-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-lg-2">
                            <Link to="/" className="logo d-block">
                                <img src={LogoImg} className="img-fluid" />
                            </Link>
                        </div>
                        <div class="mobile-action">
                            <Link class="action-icon" to="/profile">
                                <img src={UserImg} />
                            </Link>
                            <Link className="action-icon" to="/cartitem">
                                <img src={CartImg} /> {cart}
                            </Link>
                        </div>
                        <div class="toggle">
                            <a href="#">
                                <img src="assets/images/toggle.png" />
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="header-menu my-menu">
                                <ul class="m-0 p-0 d-flex text-uppercase text-white">
                                    {cat.map(category => {
                                        return (
                                            <li
                                                className="d-block pl-4 font-medium"
                                                key={category.id}
                                            >
                                                <Link to={`/${category.slug}`}>
                                                    {category.name}
                                                </Link>
                                            </li>
                                        );
                                    })}
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-7">
                            <div class="header-action">
                                <div className="header-search">
                                    <form
                                        method="GET"
                                        action=""
                                        acceptCharset="UTF-8"
                                        onSubmit={e => result(e)}
                                        role="form"
                                        className="form"
                                    >
                                        <div className="search-input-group relative">
                                            <input
                                                className="form-control rounded-pill"
                                                onChange={e =>
                                                    setQueries(e.target.value)
                                                }
                                                data-style="btn-primary"
                                                placeholder="Search entire store.."
                                                aria-label="Search entire store.."
                                                name="criteria"
                                                type="text"
                                            />
                                            <button
                                                type="sumbit"
                                                aria-label="Search"
                                            >
                                                <svg
                                                    fill="#ffffff"
                                                    width="24"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    className="search w-6 h-6"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                    ></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <li className="nav-item dropdown d-inline-block">
                                    <a
                                        className="nav-link dropdown-toggle action-icon"
                                        href="/user"
                                        id="navbarDropdown"
                                        role="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <img src={UserImg} />
                                    </a>
                                    {localStorage.getItem("token") ? (
                                        <div
                                            className="dropdown-menu"
                                            aria-labelledby="navbarDropdown"
                                        >
                                            <Link
                                                className="dropdown-item"
                                                to="/user"
                                            >
                                                profile
                                            </Link>
                                            <Link
                                                className="dropdown-item"
                                                to="/wishlist"
                                            >
                                                Wishlist
                                            </Link>
                                            <div
                                                className="dropdown-item"
                                                onClick={loggedout}
                                            >
                                                logout
                                            </div>
                                        </div>
                                    ) : (
                                        <div
                                            className="dropdown-menu"
                                            aria-labelledby="navbarDropdown"
                                        >
                                            <Link
                                                className="dropdown-item"
                                                to="/signin"
                                            >
                                                Login
                                            </Link>
                                            <Link
                                                className="dropdown-item"
                                                to="/signup"
                                            >
                                                Register
                                            </Link>
                                        </div>
                                    )}
                                </li>

                                <Link
                                    className="action-icon text-secondary"
                                    to="/cartitem"
                                >
                                    <img src={CartImg} /> {cart}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </main>
    );
}

export default withRouter(Navbar);
