import axios from "axios";
import React, { useState, useEffect } from "react";
import { Link, Redirect } from "react-router-dom";

function Home(props) {
    const [categories, setCategories] = useState([]);
    const [featureblock, setFeatureblock] = useState([]);
    const [brands, setBrands] = useState([]);
    const [banners, setBanners] = useState([]);
    const [womenProducts, setWomenProducts] = useState([]);
    const [menProducts, setMenProducts] = useState([]);
    const [boutiqueproduct, setBoutiqueproduct] = useState([]);
    const [bb, setBb] = useState([]);
    const [isOpen, setIsOpen] = useState(false);
    const [product_id, setProduct_id] = useState(null);
    const [wishlist, setWishlist] = useState([]);
    const [changewish, setChangewish] = useState(0);

    // function togglePopup(pid){
    //     setIsOpen(!isOpen);
    //     setProduct_id(pid)
    // }

    useEffect(() => {
        $("#main-overlay").show();

        axios
            .get("/product")
            .then(response => {
                setCategories(response.data.categoryblock);
                setFeatureblock(response.data.featureblock);

                setBanners(response.data.banner);

                var newwomenproduct = response.data.women_products.sort(
                    (a, b) => (a.created_at > b.created_at ? -1 : 1)
                );
                setWomenProducts(newwomenproduct);

                var newmenproduct = response.data.men_products.sort((a, b) =>
                    a.created_at > b.created_at ? -1 : 1
                );
                setMenProducts(newmenproduct);
                setBrands(response.data.brands);
                var newboutiqueproduct = response.data.boutique_product.sort(
                    (a, b) => (a.created_at > b.created_at ? -1 : 1)
                );
                setBoutiqueproduct(newboutiqueproduct);

                setBb(response.data.boutique_banner);

                $("#main-overlay").hide();
            })
            .catch(err => {
                console.log(err.request.response);
            });
    }, []);

    useEffect(() => {
        if (localStorage.getItem("token")) {
            const config = {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            };
            axios.get("/wishlist", config).then(response => {
                console.log(response.data);
                setWishlist(response.data);
                $("#sec-overlay").hide();
            });
        }
    }, [changewish]);

    function wishButton(pid, cid, action) {
        $("#sec-overlay").show();
        const data = {
            product: pid,
            category: cid,
            action: action
        };
        const config = {
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
        };

        if (localStorage.getItem("token")) {
            axios.post("/addtowish", data, config).then(response => {
                setChangewish(Math.random);
            });
        } else {
            props.history.push("/signin");
        }
    }

    $(".brand-right").on("click", function() {
        $(".scroll").animate(
            {
                scrollLeft: "+=500px"
            },
            500
        );
        console.log("right");
    });
    $(".brand-left").on("click", function() {
        $(".scroll").animate(
            {
                scrollLeft: "-=500px"
            },
            500
        );
        console.log("left");
    });

    return (
        <main>
            <section className="hero-banner">
                <div
                    id="carouselExampleControls"
                    className="carousel slide"
                    data-ride="carousel"
                >
                    <div className="carousel-inner">
                        {banners.map((banner, index) => {
                            return index == 0 ? (
                                <div className="carousel-item relative active">
                                    <div className="row no-gutters">
                                        <div
                                            className="col-12 col-sm-12 col-md-12 col-lg-12"
                                            key={banner.id}
                                        >
                                            <img
                                                src={banner.image}
                                                className="d-block w-100"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div className="carousel-item relative">
                                    <div className="row no-gutters">
                                        <div
                                            className="col-12 col-sm-12 col-md-12 col-lg-12"
                                            key={banner.id}
                                        >
                                            <img
                                                src={banner.image}
                                                className="d-block w-100"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                    {
                        //     <a className="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        //     <!-- <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                        //     <span className="sr-only">Previous</span> -->
                        //   </a>
                        //   <a className="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        //     <!-- <span className="carousel-control-next-icon" aria-hidden="true"></span>
                        //     <span className="sr-only">Next</span> -->
                        //   </a>
                    }
                </div>
            </section>

            <section class="home-category py-100 clearfix">
                <div class="container">
                    <div class="section-title uppercase">
                        <h2 class="mt-0 mb-20">browse by category</h2>
                    </div>
                    <div class="category-list">
                        <div class="category-list-wrapper">
                            {categories.map(category => {
                                return (
                                    <div class="cat-img" key={category._id}>
                                        <a
                                            href={`${category.link}`}
                                            class="cat-widget text-center"
                                        >
                                            <div class="cat-thumb">
                                                <img src={category.image} />
                                            </div>
                                            <div class="cat-name uppercase">
                                                <h4>{category.title}</h4>
                                            </div>
                                        </a>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </section>

            <section class="layout__daily-feeds mb-100 clearfix">
                <div class="container">
                    <div class="row">
                        {featureblock.map(feature => {
                            return (
                                <div
                                    class="col-6 col-sm-3 col-md-3"
                                    key={feature.id}
                                >
                                    <div class="daily-feeds-widget text-center">
                                        <div class="daily-feeds--title">
                                            {feature.title}
                                        </div>
                                        <div class="daily-feeds--image">
                                            <img src={feature.image} />
                                        </div>
                                        <div class="daily-feeds--copy">
                                            {feature.detail}
                                        </div>
                                        <div class="daily-feeds--cta">
                                            <a
                                                href={feature.link}
                                                class="btn btn-underline"
                                            >
                                                Shop now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            <section className="home-products mb-100">
                <div className="container">
                    <div className="section-title uppercase">
                        <h2 className="mt-0 mb-20">Just IN for Women</h2>
                    </div>
                    <div className="home-products-list">
                        <div className="row">
                            {womenProducts.slice(0, 6).map(womenProduct => {
                                return (
                                    <div
                                        className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2"
                                        key={womenProduct.id}
                                    >
                                        <div className="product-widget">
                                            <div className="product-thumb position-relative">
                                                <div className="wishlist">
                                                    {wishlist.find(
                                                        wish =>
                                                            wish.product_id ===
                                                            womenProduct.id
                                                    ) ? (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    womenProduct.id,
                                                                    womenProduct.category_id,
                                                                    "remove"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon active"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    ) : (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    womenProduct.id,
                                                                    womenProduct.category_id,
                                                                    "add"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    )}
                                                </div>
                                                <Link
                                                    to={`product-detail/${womenProduct.id}`}
                                                >
                                                    <img
                                                        src={womenProduct.image}
                                                    />
                                                </Link>
                                            </div>
                                            <div className="product-info my-3">
                                                {womenProduct.brand ? (
                                                    <Link
                                                        to={`product-detail/${womenProduct.id}`}
                                                        className="d-block product-brand"
                                                    >
                                                        {
                                                            womenProduct.brand
                                                                .name
                                                        }
                                                    </Link>
                                                ) : null}

                                                <Link
                                                    to={`product-detail/${womenProduct.id}`}
                                                    className="d-block product-name"
                                                >
                                                    {womenProduct.name}
                                                </Link>

                                                <span className="d-block product-price">
                                                    <span className="reduction">
                                                        Rs.{" "}
                                                        {(
                                                            womenProduct.price -
                                                            (womenProduct.price *
                                                                womenProduct.discount) /
                                                                100
                                                        ).toFixed(0)}
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    {womenProduct.discount ===
                                                    0 ? null : (
                                                        <span className="pre_reduction">
                                                            Rs.
                                                            {womenProduct.price}
                                                        </span>
                                                    )}
                                                    {womenProduct.discount ===
                                                    0 ? null : (
                                                        <span className="reduction_tag">
                                                            {
                                                                womenProduct.discount
                                                            }
                                                            % OFF
                                                        </span>
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </section>

            <section className="home-products mb-100">
                <div className="container">
                    <div className="section-title uppercase">
                        <h2 className="mt-0 mb-20">Just IN for men</h2>
                    </div>
                    <div className="home-products-list">
                        <div className="row">
                            {menProducts.slice(0, 6).map(manProduct => {
                                return (
                                    <div
                                        className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2"
                                        key={manProduct.id}
                                    >
                                        <div className="product-widget">
                                            <div className="product-thumb position-relative">
                                                <div className="wishlist">
                                                    {wishlist.find(
                                                        wish =>
                                                            wish.product_id ===
                                                            manProduct.id
                                                    ) ? (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    manProduct.id,
                                                                    manProduct.category_id,
                                                                    "remove"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon active"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    ) : (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    manProduct.id,
                                                                    manProduct.category_id,
                                                                    "add"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    )}
                                                </div>
                                                <Link
                                                    to={`product-detail/${manProduct.id}`}
                                                >
                                                    <img
                                                        src={manProduct.image}
                                                    />
                                                </Link>
                                            </div>
                                            <div className="product-info my-3">
                                                {manProduct.brand ? (
                                                    <Link
                                                        to={`product-detail/${manProduct.id}`}
                                                        className="d-block product-brand"
                                                    >
                                                        {manProduct.brand.name}
                                                    </Link>
                                                ) : null}
                                                <Link
                                                    to={`product-detail/${manProduct.id}`}
                                                    className="d-block product-name"
                                                >
                                                    {manProduct.name}
                                                </Link>

                                                <span className="d-block product-price">
                                                    <span className="reduction">
                                                        Rs.{" "}
                                                        {(
                                                            manProduct.price -
                                                            (manProduct.price *
                                                                manProduct.discount) /
                                                                100
                                                        ).toFixed(0)}
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    {manProduct.discount ===
                                                    0 ? null : (
                                                        <span className="pre_reduction">
                                                            Rs.
                                                            {manProduct.price}
                                                        </span>
                                                    )}
                                                    {manProduct.discount ===
                                                    0 ? null : (
                                                        <span className="reduction_tag">
                                                            {
                                                                manProduct.discount
                                                            }
                                                            % OFF
                                                        </span>
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </section>
            <section className="home-products mb-100">
                <div className="container">
                    <div className="section-title uppercase">
                        <h2 className="mt-0 mb-20">boutique Zone</h2>
                    </div>
                    <div className="home-products-list">
                        <div className="row">
                            {boutiqueproduct.slice(0, 6).map(boutique => {
                                return (
                                    <div
                                        className="col-6 col-sm-4 col-md-3 col-lg-2 mb-2"
                                        key={boutique.id}
                                    >
                                        <div className="product-widget">
                                            <div className="product-thumb position-relative">
                                                <div className="wishlist">
                                                    {wishlist.find(
                                                        wish =>
                                                            wish.product_id ===
                                                            boutique.id
                                                    ) ? (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    boutique.id,
                                                                    boutique.category_id,
                                                                    "remove"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon active"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    ) : (
                                                        <button
                                                            className="wishlist-button"
                                                            onClick={() =>
                                                                wishButton(
                                                                    boutique.id,
                                                                    boutique.category_id,
                                                                    "add"
                                                                )
                                                            }
                                                            data-sku="16640ATQJUZP"
                                                            data-brand="missguided"
                                                            data-price="169"
                                                            data-special-price=""
                                                        >
                                                            <svg
                                                                className="wishlist-icon"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"></path>
                                                            </svg>
                                                        </button>
                                                    )}
                                                </div>
                                                <Link
                                                    to={`/product-detail/${boutique.id}`}
                                                >
                                                    <img src={boutique.image} />
                                                </Link>
                                            </div>
                                            <div className="product-info my-3">
                                                {boutique.designer ? (
                                                    <Link
                                                        to={`product-detail/${boutique.id}`}
                                                        className="d-block product-brand"
                                                    >
                                                        {boutique.designer.name}
                                                    </Link>
                                                ) : null}
                                                <Link
                                                    to={`product-detail/${boutique.id}`}
                                                    className="d-block product-name"
                                                >
                                                    {boutique.name}
                                                </Link>

                                                <span className="d-block product-price">
                                                    <span className="reduction">
                                                        Rs.{" "}
                                                        {(
                                                            boutique.price -
                                                            (boutique.price *
                                                                boutique.discount) /
                                                                100
                                                        ).toFixed(0)}
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    {boutique.discount ===
                                                    0 ? null : (
                                                        <span className="pre_reduction">
                                                            Rs.{boutique.price}
                                                        </span>
                                                    )}
                                                    {boutique.discount ===
                                                    0 ? null : (
                                                        <span className="reduction_tag">
                                                            {boutique.discount}%
                                                            OFF
                                                        </span>
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </section>

            {bb ? (
                <section className="ads mb-100">
                    <div className="container">
                        <div className="ads-listt">
                            <div className="row">
                                <div className="col-md-12">
                                    <div className="add-widget relative">
                                        <div className="ad-thumb">
                                            <Link to="/boutique">
                                                <img
                                                    className="img-fluid w-100"
                                                    src={bb.image}
                                                />
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            ) : null}

            <section class="home-brands mb-100">
                <div class="container">
                    <div class="section-title uppercase">
                        <h2 class="mt-0 mb-20">Shop by brands</h2>
                    </div>
                    <div class="home-brands-list">
                        <div class=" col-md-12">
                            <ul class="clearfix">
                                {brands.map(brand => {
                                    return (
                                        <li key={brand._id}>
                                            <div class="brand-widget">
                                                <Link
                                                    to={`/brand/${brand.slug}`}
                                                    className="d-block brand-logo"
                                                >
                                                    <img
                                                        className="img-fluid"
                                                        src={brand.image}
                                                        alt=""
                                                    />
                                                </Link>
                                            </div>
                                        </li>
                                    );
                                })}
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix text-center">
                        <a href="" class="btn btn-underline">
                            View all Brands
                        </a>
                    </div>
                </div>
            </section>
        </main>
    );
}

export default Home;
