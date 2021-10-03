$(function($) {
    $(document).ready(function() {
        if ($('.user-name').text().length > 60) {
            $('.user-name em').hide();
        }
        $('#navbar-togg').on('click', function() {
            $('#navbar-togg').toggleClass('nav-tog');
        })
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar,#content').toggleClass('active');
            $('.hamburger').toggleClass('is-active');
        });
        var customSwitchTheme = document.getElementById('customSwitchTheme');
        if (customSwitchTheme) {
            initTheme();
            $('#customSwitchTheme').change(function() {
                resetTheme();
            });

            function initTheme() {
                var darkThemeSelected = (localStorage.getItem('customSwitchTheme') !== null && localStorage.getItem('customSwitchTheme') === 'dark');
                customSwitchTheme.checked = darkThemeSelected;
                darkThemeSelected ? document.body.setAttribute('data-theme', 'dark') : document.body.removeAttribute('data-theme');
            };

            function resetTheme() {
                if (customSwitchTheme.checked) {
                    document.body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('customSwitchTheme', 'dark');
                } else {
                    document.body.removeAttribute('data-theme');
                    localStorage.removeItem('customSwitchTheme');
                }
            }
        }


        const togglePassword = document.querySelector('#password_eye');
        const password = document.querySelector('#password');
        if (togglePassword !== null) {
            togglePassword.addEventListener('click', function(e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        }

        const togglePassword2 = document.querySelector('#password_eye2');
        const password2 = document.querySelector('#user_form_password_first');
        if (togglePassword2 !== null) {
            togglePassword2.addEventListener('click', function(e) {
                // toggle the type attribute
                const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
                password2.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        }

        const togglePassword3 = document.querySelector('#password_eye3');
        const password3 = document.querySelector('#user_form_password_second');
        if (togglePassword3 !== null) {
            togglePassword3.addEventListener('click', function(e) {
                // toggle the type attribute
                const type = password3.getAttribute('type') === 'password' ? 'text' : 'password';
                password3.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        }

        const rememberSwitch = document.querySelector('#RememberMe');
        if (rememberSwitch) {
            initSwitch();
            rememberSwitch.addEventListener('change', function(event) {
                resetSwitch();
            });

            function initSwitch() {
                // update checkbox
                rememberSwitch.checked = (localStorage.getItem('rememberMe') !== null && localStorage.getItem('rememberMe') === 'enabled');
            }

            function resetSwitch() {
                if (rememberSwitch.checked) {
                    localStorage.setItem('rememberMe', 'enabled');
                } else {
                    localStorage.removeItem('rememberMe');
                }
            }
        }
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $('.toast').toast('show')
    });
    if (window.innerWidth <= 768) {
        $('#sidebar,#content').addClass('active');
    }
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#sidebar,#content').addClass('active');
        } else {
            $('#sidebar,#content').removeClass('active');
        }
    });

});