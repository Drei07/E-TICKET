//Scroll top----------------------------------------------------------------------------------------------------->
//navbar----------------------------------------------------------------------------------------------------->
var navbar = document.querySelector('header')

window.onscroll = function() {
    if (window.pageYOffset > 0) {
        navbar.classList.add('header-active')
    } else {
        navbar.classList.remove('header-active')
    }
};

$(document).ready(function() {
    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
            $('#scrollToTop').fadeIn();
        } else {
            $('#scrollToTop').fadeOut();
        }
    });
});

function scrolltop() {
    $('html, body').animate({
        scrollTop: 0
    }, 300);
}

//history----------------------------------------------------------------------------------------------------->

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
};

//live search---------------------------------------------------------------------------------------//
$(document).ready(function() {

    load_data(1);

    function load_data(page, query = '') {
        $.ajax({
            url: "public/establishments_name_data2.php",
            method: "POST",
            data: {
                page: page,
                query: query
            },
            success: function(data) {
                $('#dynamic_content').html(data);
            }
        });
    }

    $(document).on('click', '.page-link', function() {
        var page = $(this).data('page_number');
        var query = $('#search_box').val();
        load_data(page, query);
    });

    $('#search_box').keyup(function() {
        var query = $('#search_box').val();
        load_data(1, query);
    });

});

//scroll to top----------------------------------------------------------------------------------------------------->

//covid----------------------------------------------------------------------------------------------------->
AOS.init({
    offset: 150,
    duration: 1500,

});

//reCAPTCHA

grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo $SiteKEY ?>', {
        action: 'submit'
    }).then(function(token) {
        document.getElementById("g-token").value = token;
    });
});

//modals----------------------------------------------------------------------------------------------------->
(function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

//birthdate----------------------------------------------------------------------------------------------------->
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');

}

function getAge(dateString) {
    var birthdate = new Date().getTime();
    if (typeof dateString === 'undefined' || dateString === null || (String(dateString) === 'NaN')) {
        birthdate = new Date().getTime();
    }
    birthdate = new Date(dateString).getTime();
    var now = new Date().getTime();
    var n = (now - birthdate) / 1000;
    if (n < 604800) {
        var day_n = Math.floor(n / 86400);
        if (typeof day_n === 'undefined' || day_n === null || (String(day_n) === 'NaN')) {
            return '';
        } else {
            return day_n + '' + (day_n > 1 ? '' : '') + '';
        }
    } else if (n < 2629743) {
        var week_n = Math.floor(n / 604800);
        if (typeof week_n === 'undefined' || week_n === null || (String(week_n) === 'NaN')) {
            return '';
        } else {
            return week_n + '' + (week_n > 1 ? '' : '') + '';
        }
    } else if (n < 31562417) {
        var month_n = Math.floor(n / 2629743);
        if (typeof month_n === 'undefined' || month_n === null || (String(month_n) === 'NaN')) {
            return '';
        } else {
            return month_n + ' ' + (month_n > 1 ? '' : '') + '';
        }
    } else {
        var year_n = Math.floor(n / 31556926);
        if (typeof year_n === 'undefined' || year_n === null || (String(year_n) === 'NaN')) {
            return year_n = '';
        } else {
            return year_n + '' + (year_n > 1 ? '' : '') + '';
        }
    }
}

function getAgeVal(pid) {
    var birthdate = formatDate(document.getElementById("txtbirthdate").value);
    var count = document.getElementById("txtbirthdate").value.length;
    if (count == '10') {
        var age = getAge(birthdate);
        var str = age;
        var res = str.substring(0, 1);
        if (res == '-' || res == '0') {
            document.getElementById("txtbirthdate").value = "";
            document.getElementById("txtage").value = "";
            $('#txtbirthdate').focus();
            return false;
        } else {
            document.getElementById("txtage").value = age;
        }
    } else {
        document.getElementById("txtage").value = "";
        return false;
    }
};
//password and confirm-password----------------------------------------------------------------------------------------------------->
var check = function() {
    if (document.getElementById('password').value ==
        document.getElementById('confirm_password').value) {
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerHTML = 'Password match';
        document.getElementById('btn-register').disabled = false;
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 2000);

        if (document.getElementById('password').value == "") {
            document.getElementById('message').innerHTML = '';
        }

    } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Password and Confirm password did not match';
        document.getElementById('btn-register').disabled = true;
    }
};

//form alert----------------------------------------------------------------------------------------------------->
function IsEmpty() {
    if (document.forms['form'].FName.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }


    if (document.forms['form'].LName.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].PNumber.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].BDate.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].Age.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].Sex.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].Barangay.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }


    if (document.forms['form'].gmail.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }
    return true;
};

//male/female----------------------------------------------------------------------------------------------------->

window.validate = function() {

    data = document.forms['form']['Sex'].value;
    var starts = ['male', 'female', 'Male', 'Female'];

    for (var i = 0; i <= starts.length; i++)
        if (data.indexOf(starts[i]) == 0) {
            return true;
        }
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'Please filled out all the fields';
    setTimeout(function() {
        var msg = document.getElementById("message").innerHTML = '';
    }, 3000);

    return false;


};

//Password show/hide---------------------------------------------------------------------------------------------------->

function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    var y = document.getElementById("confirm_password");

    if (y.type === "password") {
        y.type = "text";
    } else {
        y.type = "password";
    }
}

//No space---------------------------------------------------------------------------------------------------->

$(function() {
    $('#password').on('keypress', function(e) {
        if (e.which == 32) {
            return false;
        }
    });
});

$(function() {
    $('#confirm_password').on('keypress', function(e) {
        if (e.which == 32) {
            return false;
        }
    });
});

//numbers only----------------------------------------------------------------------------------------------------->
$('.numbers').keypress(function(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
});
