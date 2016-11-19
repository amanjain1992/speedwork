$(document).ready(function() {
    var timer;
    $(document).on('blur keyup', '[data-validate-username]', function() {
        if (timer) {
            clearTimeout(timer);
        }
        var $this = $(this);
        timer = setTimeout(function() {
            var target = $('.ac-username-message');
            var field = $this.data('validate-username');
            var data = {};
            data[field] = $this.val();
            $.ajax({
                type: "POST",
                url: _link('members/register?task=validate&format=json'),
                data: {
                    data: data
                },
                success: function(res) {
                    if (res.status == 'ERROR') {
                        target.html(res.message);
                    } else {
                        target.empty();
                    }
                }
            });
        }, 1000)
    });
});

function onLoginVerify(res) {
    $('.verify-login').fadeOut();
    $('.verify-login-code').fadeIn();
}
