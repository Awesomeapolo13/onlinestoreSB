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

            const buttonOrder = shopOrder.querySelector('.button');
            const popupEnd = document.querySelector('.shop-page__popup-end');

            buttonOrder.addEventListener('click', (evt) => {

                form.noValidate = true;

                const inputs = Array.from(shopOrder.querySelectorAll('[required]'));
                const radios = Array.from(shopOrder.querySelectorAll('[checked]'));
                let isValidForm = false;

                inputs.forEach(inp => {

                    if (!!inp.value) {

                        if (inp.classList.contains('custom-form__input--error')) {
                            inp.classList.remove('custom-form__input--error');
                        }
                        isValidForm = true;

                    } else {

                        inp.classList.add('custom-form__input--error');
                        isValidForm = false
                    }
                });
                //В случае успешной валидации направить ajax запрос
                if (isValidForm) {
                    let orderData = {};//объект с данными формы
                    //запись данных из обязательных полей
                    inputs.forEach(inp => {
                        // console.log(inp.getAttribute('name'));
                        orderData[inp.getAttribute('name')] = inp.value;
                    });
                    //запись данных из радио-кнопок
                    radios.forEach(radio => {
                        console.log(radio.getAttribute('name'));
                        orderData[radio.getAttribute('name')] = radio.value;
                    });
                    //запись данных кнопки
                    orderData[buttonOrder.getAttribute('name')] = buttonOrder.value;

                    const commentArea = document.querySelector('.custom-form__textarea');
                    //запись данных из textarea
                    orderData[commentArea.getAttribute('name')] = commentArea.value;

                    console.log(orderData);

                    $.ajax({
                        url: '/server/index.php',
                        type: 'POST',
                        dataType: 'json',
                        data: orderData,
                        success: function (response) {
                            console.log('success');
                            const serverSuccess = document.querySelector('.shop-page__end-message');
                            serverSuccess.textContent = response.message;
                        },
                        error: function (e) {
                            console.log('error', e);

                            const serverErr = document.querySelector('.shop-page__end-message');
                            serverErr.textContent = 'Заполните все обязательные поля';
                        }
                    });
                }


                if (inputs.every(inp => !!inp.value)) {

                    evt.preventDefault();

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

                } else {
                    window.scroll(0, 0);
                    evt.preventDefault();
                }
            });
        }
    });
}

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
            } else {
                status.textContent = 'Не выполнено';
            }

            status.classList.toggle('order-item__info--no');
            status.classList.toggle('order-item__info--yes');

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
const addList = document.querySelector('.add-list');
if (addList) {

    const form = document.querySelector('.custom-form');
    labelHidden(form);

    const addButton = addList.querySelector('.add-list__item--add');
    const addInput = addList.querySelector('#product-photo');

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

    const button = document.querySelector('.button');
    const popupEnd = document.querySelector('.page-add__popup-end');

    button.addEventListener('click', (evt) => {

        evt.preventDefault();

        form.hidden = true;
        popupEnd.hidden = false;

    })

}

const productsList = document.querySelector('.page-products__list');
if (productsList) {

    productsList.addEventListener('click', evt => {

        const target = evt.target;

        if (target.classList && target.classList.contains('product-item__delete')) {

            productsList.removeChild(target.parentElement);

        }

    });

}

let minPrice = '';
let maxPrice = '';

// jquery range maxmin
if (document.querySelector('.shop-page')) {

    $('.range__line').slider({
        min: 350,
        max: 32000,
        values: [350, 32000],
        range: true,
        stop: function (event, ui) {

            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
            minPrice = $('.input_min_price').val($('.range__line').slider('values', 0));
            maxPrice = $('.input_max_price').val($('.range__line').slider('values', 1));
        },
        slide: function (event, ui) {

            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
        }
    });

}

let productSortSelectors = document.querySelectorAll('.custom-form__select');
let productSortType = productSortSelectors[0];
let productOrderType = productSortSelectors[1];

// productSortType.addEventListener('select', getSortParams());
// productOrderType.addEventListener('select', getOrderParams());

function getSortParams() {
    console.log('sort');
    $.ajax({
        url: "/",
        type: "get", //send it through get method
        data: {
            sortCategory: productSortType.value,
        },
        success: function (response) {
            console.log('success sort');
            // location.reload();
        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });
}

function getOrderParams() {
    console.log('order');
    $.ajax({
        url: "/",
        type: "get", //send it through get method
        data: {
            prices: productOrderType.value,
        },
        success: function (response) {
            console.log('success order');
        },
        error: function (xhr) {
            console.log(xhr);
        }
    });
}



// let sortSelect = document.querySelector('.')
