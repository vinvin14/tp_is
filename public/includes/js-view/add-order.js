function addOrder()
{
    $('#product-category').on('input', function () {
                var content = $('#products');
                $.ajax({
                    url: '/ajax/get/products/'+ $(this).val(),
                    type: 'get',
                    success: function (data) {
                        console.log(data)
                        content.html('');
                        data.forEach(function (index) {
                            console.log(data)
                            content.append('' +
                                    '<div class="col-xs-12 col-md-6 col-lg-3 my-2" id="product-container" data-productid="'+index.id+'" data-points="'+index.points+'" data-stockid="'+index.stock_id+'" data-id="'+index.id+'">' +
                                    '<div class="card shadow-sm" style="width: 13rem;">' +
                                    '<img class="card-img-top" height="120px" src="'+(index.uploaded_img ? index.uploaded_img : '/storage/utilities/no_image.png')+'" alt="Card image cap">' +
                                    '<div class="card-body text-center">' +
                                    '<div class="font-weight-bold " id="title" title="'+ index.title +'">'+ index.title +'</div>' +
                                    '<div class="font-weight-bold text-truncate price">₱<span id="price">'+ index.price +'</span></div>' +
                                    '<span id="qty" data-qty="'+ (index.qty - index.orderQty) +'" data-unit="'+ index.unit +'"></span>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            });
                            $("#keyword").on("keyup", function() {
                                var value = $(this).val().toLowerCase();
                                content.find(".card").filter(function() {
                                    $(this).parent().toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });

                            $('div[id="product-container"]').click(function (e) {
                                var productID = $(this).data('id');
                                var stockID = $(this).data('stockid');
                                var productID = $(this).data('productid');
                                var points = $(this).data('points');
                                var price = $(this).find('.price').text().replace('₱', '');

                                // console.log(e.target);
                                $(this).find('.card').addClass('product-selected border border-success');
                                $(this).find('.card-img-top').css('opacity', 1);

                                $('#order-details').html('' +
                                    '<div class="form-group mt-5">' +
                                        '<label>Product Title</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('#title').text() +'</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Price</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('.price').text() +'</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Unit</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('#qty').data('unit')  +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Quantity</label>' +
                                        '<div class="font-weight-bold text-info"><span id="remaining-qty">'+ $(this).find('#qty').data('qty') +'</span> Remaining</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<input type="hidden" value="'+productID+'" name="product_id">' +
                                        '<input type="hidden" value="'+stockID+'" name="stock_id">' +
                                        '<input type="hidden" step="0.01" value="'+price+'" name="price">' +
                                        '<input type="hidden" step="0.01" value="'+points+'" name="total_points">' +
                                        '<label>Buying Quantity</label>' +
                                        '<div class="input-group mb-2">' +
                                            '<div class="input-group-prepend">' +
                                                '<span class="btn btn-outline-primary font-weight-bold" id="q-minus"><i class="fas fa-fw fa-minus"></i></span>' +
                                            '</div>' +
                                            '<input type="number" class="form-control text-center" min="1" value="1" id="buying-qty" name="qty" required>' +
                                            '<div class="input-group-append">' +
                                                ' <span class="btn btn-outline-primary font-weight-bold" id="q-add"><i class="fas fa-fw fa-plus"></i></span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Discount Amount</label>' +
                                        '<input type="number" class="form-control" name="discount_amount">' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Total Amount</label>' +
                                        '<div class="font-weight-bold">₱<span id="total-amount">'+price+'</span></div>' +
                                    '</div>' +
                                    '<button type="submit" class="btn btn-primary btn-block">Add this Product</button>'
                                );
                                $('#q-add').click(function () {
                                    var buyingQty = parseInt($('#buying-qty').val());
                                    var remainingQty = parseInt($('#remaining-qty').text());

                                    if (buyingQty+1 > remainingQty) {
                                        Swal.fire({
                                            title: 'Oops!, We have a problem!',
                                            text: 'Item has insufficient quantity for the request!!',
                                            icon: 'error',
                                            confirmButtonText: 'Okay!'
                                        });
                                        $('#buying-qty').val(remainingQty);
                                        $('#total-amount').text((parseInt($('#buying-qty').val()) * price));
                                        return false;
                                    }
                                    $('#buying-qty').val(buyingQty+1);
                                    $('#total-amount').text((parseInt($('#buying-qty').val()) * price));
                                });

                                $('#q-minus').click(function () {
                                    var buyingQty = parseInt($('#buying-qty').val());


                                    if (buyingQty < 2) {
                                        Swal.fire({
                                            title: 'Oops!, We have a problem!',
                                            text: 'Buying quantity should have a value!',
                                            icon: 'error',
                                            confirmButtonText: 'Okay!'
                                        });
                                        $('#buying-qty').val(1);
                                        $('#total-amount').text((parseInt($('#buying-qty').val()) * price));
                                        return false;
                                    }
                                    $('#buying-qty').val(buyingQty-1);
                                    $('#total-amount').text((parseInt($('#buying-qty').val()) * price));
                                });
                                $('#buying-qty').on('blur', function () {
                                    // console.log($(this).val());
                                    var remainingQty = parseInt($('#remaining-qty').text());
                                    if ($(this).val() > remainingQty) {
                                        Swal.fire({
                                            title: 'Oops!, We have a problem!',
                                            text: 'Item has insufficient quantity for the request!',
                                            icon: 'error',
                                            confirmButtonText: 'Okay!'
                                        });
                                        $(this).val(remainingQty);
                                        $('#total-amount').text((parseInt($('#buying-qty').val()) * price));
                                    }
                                });

                                $(this).siblings().click(function () {
                                    if (productID != $(this).data('id')) {
                                        $('div[data-id='+productID+']').find('.card').removeClass('product-selected border border-success');
                                        $('div[data-id='+productID+']').find('.card-img-top').css('opacity', 0.5);
                                    }
                                });
                            });
                    },
                    error: function (data) {
                            content.html('<h3 class="text-center m-4">'+data.responseJSON+'</h3>');
                    }
                });
            });
}
