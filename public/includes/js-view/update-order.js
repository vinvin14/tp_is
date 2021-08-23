function updateOrder()
{
    $('a[id="update-order"]').click(function () {

        var id = $(this).data('id');
        $.ajax({
            url: '/ajax/get/order/'+id,
            type: 'get',
            success:function (data) {
                $('#updateOrderModal #update-title').text(data.title);
                $('#updateOrderModal #update-qty').val(data.qty);
                $('#updateOrderModal #update-discount').val(data.discount_amount);
                $('#update-save').click(function () {

                    $.ajax({
                        url: '/ajax/update/order/'+id,
                        type: 'POST',
                        data: {
                            qty: $('#updateOrderModal #update-qty').val(),
                            discount_amount:  $('#updateOrderModal #update-discount').val()
                        },
                        success:function (data) {
                            console.log(data);
                            if (data) {
                                Swal.fire(
                                    'Success!',
                                    'Order has been updated!',
                                    'success',
                                  )

                                setTimeout(function() {
                                        location.reload();
                                }, 1000);
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...!',
                                text: 'Something went wrong!',
                              })
                        }
                    });
                });
            }
        });
    });
}
