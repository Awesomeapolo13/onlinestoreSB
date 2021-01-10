'use strict';

const toggleHidden = (...fields) => {

    fields.forEach((field) => {

        if (field.hidden === true) {

            field.hidden = false;

        } else {

            field.hidden = true;

        }
    });
};

const labelHidden = (form) => {

    form.addEventListener('focusout', (evt) => {

        const field = evt.target;
        const label = field.nextElementSibling;

        if (field.tagName === 'INPUT' && field.value && label) {

            label.hidden = true;

        } else if (label) {

            label.hidden = false;

        }
    });
};

const toggleDelivery = (elem) => {

    const delivery = elem.querySelector('.js-radio');
    const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
    const deliveryNo = elem.querySelector('.shop-page__delivery--no');
    const fields = deliveryYes.querySelectorAll('.custom-form__input');

    delivery.addEventListener('change', (evt) => {

        if (evt.target.id === 'dev-no') {

            fields.forEach(inp => {
                if (inp.required === true) {
                    inp.required = false;
                }
            });


            toggleHidden(deliveryYes, deliveryNo);

            deliveryNo.classList.add('fade');
            setTimeout(() => {
                deliveryNo.classList.remove('fade');
            }, 1000);

        } else {

            fields.forEach(inp => {
                if (inp.required === false) {
                    inp.required = true;
                }
            });

            toggleHidden(deliveryYes, deliveryNo);

            deliveryYes.classList.add('fade');
            setTimeout(() => {
                deliveryYes.classList.remove('fade');
            }, 1000);
        }
    });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

    filterWrapper.addEventListener('click', evt => {

        const filterList = filterWrapper.querySelectorAll('.filter__list-item');

        filterList.forEach(filter => {

            if (filter.classList.contains('active')) {

                filter.classList.remove('active');

            }

        });

        const filter = evt.target;

        filter.classList.add('active');

    });

}


//Оформление заказа
const shopPage = document.querySelector('.shop-page');
if (shopPage) {

    let productId = null; //идентификатор товара
    let productPrice = null; //цена товара

    //Выделяем идентификатор товара и его цену
    let products = document.querySelectorAll('.shop__item');
    for (let product of products) {

        product.addEventListener('click', () => {

            for (let elem of product.childNodes) {
                if (elem.tagName === 'INPUT' && elem.getAttribute('class') === 'productId') {
                    productId = elem.value;
                }
                if (elem.tagName === 'INPUT' && elem.getAttribute('class') === 'price') {
                    productPrice = elem.value;
                }
            }
        });

    }

    // Влидация и отправление заказа
    $(function () {
        $('#addOrderForm').submit(function (e) {
            e.preventDefault();
            let orderData = new FormData($('#addOrderForm')[0]);
            orderData.append('sendOrder', 'true');
            orderData.set('productId', productId);
            orderData.set('productPrice', productPrice);

            //Элемент с сообщением об ошибке валидации от сервера
            const serverError = document.querySelector('.error');
            const shopOrder = document.querySelector('.shop-page__order');
            //Сообщение об успешном оформлении заказа
            const popupEnd = document.querySelector('.shop-page__popup-end');

            //Валидация обязательных полей формы
            shopOrder.querySelector('.custom-form').noValidate = true;

            //Текстовые поля обязательные для заполнения
            const inputs = Array.from(shopOrder.querySelectorAll('[required]'));
            let isValidForm = false;

            //Валидация текстовых полей
            inputs.forEach(inp => {

                if (!!inp.value) {

                    if (inp.classList.contains('custom-form__input--error')) {
                        inp.classList.remove('custom-form__input--error');
                    }
                    isValidForm = true;

                } else {

                    inp.classList.add('custom-form__input--error');
                    isValidForm = false
                    window.scroll(0, 0);
                }
            });

            if (!isValidForm) {
                serverError.textContent = 'Для оформления заказа необходимо заполнить все обязательные поля';
                serverError.hidden = false;
            } else {

                //Направить запрос в случае успешной валидации
                $.ajax({
                    url: '/server/index.php',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: orderData,
                    cache: false,
                    success: function (response) {
                        // console.log('success', response);
                        const serverSuccess = document.querySelector('.shop-page__end-message');

                        if (response.error) {
                            serverError.textContent = response.message;
                            serverError.hidden = false;
                        } else {
                            serverError.hidden = true;
                            serverSuccess.textContent = response.message;

                            toggleHidden(shopOrder, popupEnd);

                            popupEnd.classList.add('fade');
                            setTimeout(() => popupEnd.classList.remove('fade'), 1000);

                            window.scroll(0, 0);

                            const buttonEnd = popupEnd.querySelector('.button');

                            buttonEnd.addEventListener('click', () => {

                                popupEnd.classList.add('fade-reverse');

                                setTimeout(() => {

                                    popupEnd.classList.remove('fade-reverse');

                                    toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

                                }, 1000);

                            });
                        }
                    },
                    error: function (e) {
                        console.log('error', e);
                        if (e) {
                            $('.error').text('Возникла ошибка при добавлении/изменении новго товара. Попробуйте позднее');
                        }
                    }
                });
            }


        })
    })

}

//Перечень товаров и форма оформления заказа
const shopList = document.querySelector('.shop__list');
if (shopList) {

    shopList.addEventListener('click', (evt) => {

        const prod = evt.path || (evt.composedPath && evt.composedPath());


        if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {


            const shopOrder = document.querySelector('.shop-page__order');

            toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

            window.scroll(0, 0);

            shopOrder.classList.add('fade');
            setTimeout(() => shopOrder.classList.remove('fade'), 1000);

            const form = shopOrder.querySelector('.custom-form');
            labelHidden(form);

            toggleDelivery(shopOrder);
        }
    });
}

//определяем id заказа, на котором был сделан клик и добавляем его в запрос
let orderStatus = {};

let orders = document.querySelectorAll('.page-order__item');
orders.forEach((order) => {
    let orderId = order.querySelector('.order-item__info--id');
    let orderStatusBtn = order.querySelector('.order-item__btn');

    orderStatusBtn.addEventListener('click', () => {
        if (orderStatusBtn.getAttribute('id') === orderId.innerHTML) {
            orderStatus.id = orderId.innerHTML;
            orderStatus.changeStatus = 'change';
            console.log('id', orderStatus.id);
        }
    });
});


const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

    pageOrderList.addEventListener('click', evt => {


        if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
            var path = evt.path || (evt.composedPath && evt.composedPath());
            Array.from(path).forEach(element => {

                if (element.classList && element.classList.contains('page-order__item')) {

                    element.classList.toggle('order-item--active');

                }

            });

            evt.target.classList.toggle('order-item__toggle--active');

        }

        if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

            const status = evt.target.previousElementSibling;

            if (status.classList && status.classList.contains('order-item__info--no')) {
                status.textContent = 'Выполнено';
                orderStatus['done'] = 1;
            } else {
                status.textContent = 'Не выполнено';
                orderStatus['done'] = 0;
            }

            status.classList.toggle('order-item__info--no');
            status.classList.toggle('order-item__info--yes');
            //Запрос на изменение статуса заказа
            $.ajax({
                url: '/server/index.php',
                type: 'POST',
                dataType: 'json',
                data: orderStatus,
                cache: false,
                success: function (response) {
                    // console.log('success', response);
                },
                error: function (e) {
                    console.log('error', e);
                }
            });
        }

    });

}

const checkList = (list, btn) => {

    if (list.children.length === 1) {

        btn.hidden = false;

    } else {
        btn.hidden = true;
    }

};

//Запрос на добаление/изменение товара
const pageAdd = document.querySelector('.page-add');
if (pageAdd) {
    $(function () {
        $('#addProductForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData($('#addProductForm')[0]);
            let image = document.getElementById('productPhoto').files;
            const hiddenInput = document.getElementById('oldPath');
            formData.append('productImg', image);
            //Добавление информации о типе запроса (добаление или изменение)
            if (hiddenInput) {
                formData.append('changeProduct', 'yes');
            } else {
                formData.append('addProduct', 'yes');
            }
            $.ajax({
                url: '/server/index.php',
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                cache: false,
                success: function (response) {
                    // console.log('success', response);
                    if (response.error) {
                        $('.error').text(response.message);
                    } else {
                        const form = document.querySelector('.custom-form');
                        const popupEnd = document.querySelector('.page-add__popup-end');
                        form.hidden = true;
                        popupEnd.hidden = false;
                    }
                },
                error: function (e) {
                    console.log('error', e);
                    if (e) {
                        $('.error').text('Возникла ошибка при добавлении/изменении новго товара. Попробуйте позднее');
                    }
                }
            });

        })
    })

}


const addList = document.querySelector('.add-list');
if (addList) {

    const form = document.querySelector('.custom-form');
    labelHidden(form);

    const addButton = addList.querySelector('.add-list__item--add');
    const addInput = addList.querySelector('#productPhoto');

    checkList(addList, addButton);

    addInput.addEventListener('change', evt => {

        const template = document.createElement('LI');
        const img = document.createElement('IMG');

        template.className = 'add-list__item add-list__item--active';
        template.addEventListener('click', evt => {
            addList.removeChild(evt.target);
            addInput.value = '';
            checkList(addList, addButton);
        });

        const file = evt.target.files[0];
        const reader = new FileReader();

        reader.onload = (evt) => {
            img.src = evt.target.result;
            template.appendChild(img);
            addList.appendChild(template);
            checkList(addList, addButton);
        };

        reader.readAsDataURL(file);

    });
}

//Запрос на удаление товара
const productsPage = document.querySelector('.page-products');
if (productsPage) {
    const errorSpan = document.querySelector('.error');
    let deleteProductObj = {};
    let adminProductsList = document.querySelectorAll('.page-products__item');
    adminProductsList.forEach((product) => {
        let productsId = product.querySelectorAll('.product-item__field');
        let productId = '';

        productsId.forEach((field) => {
            if (field.getAttribute('id') === 'productId') {
                productId = field.innerHTML;
            }
        });

        let deleteProductBtn = product.querySelector('.product-item__delete');

        deleteProductBtn.addEventListener('click', (evt) => {
            if (deleteProductBtn.getAttribute('id') === productId) {
                deleteProductObj.id = productId;
                deleteProductObj.deleteProduct = 'delete';
                console.log(deleteProductObj);
                //Запрос на удаление товара
                $.ajax({
                    url: '/server/index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: deleteProductObj,
                    cache: false,
                    success: function (response) {
                        // console.log('success', response);
                        if (response.error) {

                            errorSpan.textContent = response.message;

                        } else {
                            errorSpan.textContent = '';
                            const target = evt.target;
                            const productsList = document.querySelector('.page-products__list');
                            if (target.classList && target.classList.contains('product-item__delete')) {

                                productsList.removeChild(target.parentElement);

                            }
                        }
                    },
                    error: function (e) {
                        console.log('error', e);
                    }
                });
            }
        });
    });
}

let minPrice = '';
let maxPrice = '';

// jquery range maxmin
if (document.querySelector('.shop-page')) {

    $('.range__line').slider({
        min: 350,
        max: 32000,
        values: [minPrice ? minPrice : 350, maxPrice ? maxPrice : 32000],
        range: true,
        stop: function (event, ui) {

            $('.min-price').text((minPrice ? minPrice : $('.range__line').slider('values', 0)) + ' руб.');
            $('.max-price').text((maxPrice ? maxPrice : $('.range__line').slider('values', 1)) + ' руб.');
            minPrice = $('.input_min_price').val($('.range__line').slider('values', 0));
            maxPrice = $('.input_max_price').val($('.range__line').slider('values', 1));
        },
        slide: function (event, ui) {

            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
        }
    });

}
