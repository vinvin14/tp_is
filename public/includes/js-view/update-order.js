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
                            if (data[0] === 200) {
                                Swal.fire(
                                    'Success!',
                                    'Order has been updated!',
                                    'success',
                                  )

                                setTimeout(function() {
                                        location.reload();
                                }, 1000);
                            }
                            else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...!',
                                    text: data[1],
                                  })
                            }
                        },
                        error: function (data) {
                            console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...!',
                                text: data.responseJSON,
                              })
                        }
                    });
                });
            }
        });
    });
}
