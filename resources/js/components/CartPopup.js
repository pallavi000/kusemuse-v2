import axios from "axios";
import { map, size } from "lodash";
import { comment } from "postcss";
import React, { useEffect, useState, useContext } from "react";
import { AddContext } from "./Cartcontext";
import TimeImg from "../../../public//images/time.png";
import CashbackImg from "../../../public/images/cashback.png";
import BoxImg from "../../../public/images/box.png";
import ItemImg from "../../../public/images/new-item.png";
import Cookies from "universal-cookie";
import { Link, useHistory } from "react-router-dom";
import { format } from "timeago.js";

function CartPopup(props) {
    const data = useContext(AddContext);
    const { setCart } = data;
    const { cart } = data;
    const [size, setSize] = useState(null);
    const [color, setColor] = useState([]);
    const [product, setProduct] = useState([]);
    const [category, setCategory] = useState([]);
    const [brand, setBrand] = useState([]);
    const [stock, setStock] = useState(1);
    const [price, setPrice] = useState(null);
    const [quantity, setQuantity] = useState(1);
    const [images, setImages] = useState([]);
    const [originalPrice, setOriginalPrice] = useState(0);
    const [sku, setSku] = useState(null);

    useEffect(() => {
        $("#sec-overlay").show();
        axios
            .get("/detail/" + props.product_id)
            .then(response => {
                console.log(response.data.detail);
                setProduct(response.data.detail);
                setStock(response.data.detail.stock);
                setSku(response.data.detail.sku);
                setColor(response.data.detail.color);
                setPrice(
                    (
                        response.data.detail.price -
                        (response.data.detail.price *
                            response.data.detail.discount) /
                            100
                    ).toFixed(0)
                );
                setOriginalPrice(response.data.detail.price);
                setImages([]);
                if (response.data.detail.feature_images) {
                    var featureImages = response.data.detail.feature_images.split(
                        ","
                    );
                    setImages(featureImages);
                }
                $("#sec-overlay").hide();
            })
            .catch(erro => {
                props.history.push("/");
            });
    }, [props]);

    function cartButton(pid, cid, seller_id) {
        if (!localStorage.getItem("token")) {
            //props.history.push('/signin')

            const cookies = new Cookies();
            var guest_id = cookies.get("guest_id");
            const data = {
                product: pid,
                category: cid,
                size: size,
                color: color.name,
                price: price,
                sku: sku,
                seller_id: seller_id,
                guest_id: guest_id,
                quantity: quantity
            };
            axios
                .post("/guestaddtocart", data)
                .then(response => {
                    props.toggle(null);
                    openNot();
                })
                .catch(error => {
                    console.log(error.request.response);
                });
        } else {
            const data = {
                product: pid,
                category: cid,
                size: size,
                color: color.name,
                price: price,
                sku: sku,
                seller_id: seller_id,
                quantity: quantity
            };
            const config = {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            };
            axios
                .post("/addtocart", data, config)
                .then(response => {
                    props.toggle(null);
                    openNot();
                })
                .catch(error => {
                    console.log(error);
                });
        }
        setCart(cart + 1);
    }

    function pivotsize(e) {
        if (e.target.value) {
            var index = e.target.selectedIndex;
            var optionElement = e.target.childNodes[index];
            var size = optionElement.getAttribute("size_id");
            var sku = optionElement.getAttribute("sku");
            var price = optionElement.getAttribute("price");
            price = parseInt(price).toFixed(0);
            var stock = optionElement.getAttribute("stock");
            var orgprice = optionElement.getAttribute("orgprice");
            setSize(size);
            setSku(sku);
            setPrice(price);
            setStock(stock);
            setOriginalPrice(orgprice);
        }
    }

    function renderStocks(stock) {
        const list = [];
        for (var i = 1; i <= stock; i++) {
            list.push(<option value={i}>{i}</option>);
        }
        return list;
    }

    function openNot() {
        $(".cartpopupp").show();
    }

    function closeNot() {
        $(".cartpopupp").hide();
    }

    return (
        <div className="row">
            <div className="col-md-6">
                <div
                    id="detailSlider"
                    className="carousel slide"
                    data-ride="carousel"
                >
                    <div className="row">
                        <div className="col-4 col-md-3">
                            <ol className="carousel-indicators">
                                <li
                                    data-target="#detailSlider"
                                    data-slide-to="0"
                                    className="active"
                                >
                                    <img src={product.image} />
                                </li>
                                {images.map((image, index) => {
                                    return (
                                        <li
                                            data-target="#detailSlider"
                                            data-slide-to={index + 1}
                                            className="active"
                                            key={index}
                                        >
                                            <img src={image} />
                                        </li>
                                    );
                                })}
                            </ol>
                        </div>
                        <div className="col-8 col-md-9 pl-xs-0">
                            <div className="carousel-inner">
                                <div className="carousel-item active">
                                    <img
                                        src={product.image}
                                        className="d-block w-100"
                                        alt="..."
                                    />
                                </div>
                                {images.map((image, index) => {
                                    return (
                                        <div
                                            className="carousel-item"
                                            key={index}
                                        >
                                            <img
                                                src={image}
                                                className="d-block w-100"
                                                alt="..."
                                            />
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="col-md-6">
                <div className="product-details pt-3">
                    <h2 className="product-title text-uppercase pro">
                        {product.name}
                    </h2>
                    {brand ? (
                        <span className="product-shrt-desc">{brand.name}</span>
                    ) : null}

                    <h3 className="product-price proprice">
                        Rs.{price} <span>VAT Included</span>
                    </h3>
                    <span className="product-shrt-desc pt-2 font-bold">
                        Stock: {stock}
                    </span>
                    {product.discount ? (
                        <div className="discount">
                            <del>Rs.{originalPrice}</del>
                            <span className="d-inline pl-2 discount-price">
                                {product.discount}% off
                            </span>
                        </div>
                    ) : null}

                    {product.sizes &&
                    Object.keys(product.sizes).length !== 0 ? (
                        <div className="select-size">
                            <form>
                                <div className="form-group mt-4">
                                    <div className="row">
                                        <div className="col-sm-3">
                                            <label
                                                htmlFor=""
                                                className="pt-2 font-bold"
                                            >
                                                SIZE
                                            </label>
                                        </div>
                                        <div className="col-sm-9">
                                            <select
                                                className="form-control"
                                                id=""
                                                onChange={e => pivotsize(e)}
                                            >
                                                <option value="">
                                                    Select size
                                                </option>
                                                {product.sizes.map(sizes => {
                                                    return (
                                                        <option
                                                            key={sizes.id}
                                                            size_id={sizes.id}
                                                            orgprice={
                                                                sizes.pivot
                                                                    .price
                                                            }
                                                            sku={
                                                                sizes.pivot.sku
                                                            }
                                                            price={
                                                                sizes.pivot
                                                                    .price -
                                                                (sizes.pivot
                                                                    .price *
                                                                    product.discount) /
                                                                    100
                                                            }
                                                            stock={
                                                                sizes.pivot
                                                                    .stock
                                                            }
                                                            value={sizes.id}
                                                        >
                                                            {sizes.name}
                                                        </option>
                                                    );
                                                })}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    ) : null}

                    {stock <= 0 ? (
                        <div className="add-to-cart-link mt-4 mb-4">
                            <div className="btn btn-primary w-100" disabled>
                                product out of stock
                            </div>
                        </div>
                    ) : size ? (
                        <div>
                            <div className="product-single__form-price">
                                <label className="variant__label__popup">
                                    Quantity
                                </label>
                                <select
                                    className="form-control w-25"
                                    onChange={e => setQuantity(e.target.value)}
                                >
                                    {renderStocks(stock)}
                                </select>
                            </div>
                            <div className="add-to-cart-link mt-4 mb-4">
                                <div
                                    className="btn btn-primary w-100"
                                    onClick={() =>
                                        cartButton(
                                            product.id,
                                            product.category_id,
                                            product.seller_id
                                        )
                                    }
                                >
                                    Add to Cart
                                </div>
                            </div>
                        </div>
                    ) : product.sizes &&
                      Object.keys(product.sizes).length === 0 ? (
                        <div>
                            <div className="product-single__form-price">
                                <label className="variant__label__popup">
                                    Quantity
                                </label>
                                <select
                                    className="form-control w-25"
                                    onChange={e => setQuantity(e.target.value)}
                                >
                                    {renderStocks(stock)}
                                </select>
                            </div>

                            <div className="add-to-cart-link mt-4 mb-4">
                                <div
                                    className="btn btn-primary w-100"
                                    onClick={() =>
                                        cartButton(
                                            product.id,
                                            product.category_id,
                                            product.seller_id
                                        )
                                    }
                                >
                                    Add to cart
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="add-to-cart-link mt-4 mb-4">
                            <div className="btn btn-primary w-100" disabled>
                                Select Size
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

export default CartPopup;
