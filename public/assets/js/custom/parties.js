$(document).ready(function(){
    $('.view-btn').each(function () {
        let container = $(this);
        let service = container.data('id');
        $('#details-view_'+service).on('click',function () {
            $('#party-id').text($('#details-view_'+service).data('party-id'));
            $('#name').text($('#details-view_'+service).data('name'));
            $('#phone').text($('#details-view_'+service).data('phone'));
            $('#email').text($('#details-view_'+service).data('email'));
            $('#address').text($('#details-view_'+service).data('address'));
            $('#country').text($('#details-view_'+service).data('country'));
            $('#currency').text($('#details-view_'+service).data('currency'));
            $('#total-bill').text($('#details-view_'+service).data('total-bill'));
            $('#advance-payment').text($('#details-view_'+service).data('advance-payment'));
            $('#due-payment').text($('#details-view_'+service).data('due-payment'));
            $('#balance').text($('#details-view_'+service).data('balance'));
            $('#remarks').text($('#details-view_'+service).data('remarks'));
        });
    });
});