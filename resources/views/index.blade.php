<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <style>
            body {
                text-align: center;
            }
            form {
                display: inline-block;
            }
        </style>

        <script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>
    </head>
    <body>
        <form method="POST" id="phone" action="/send">
            @csrf
            <label><p id="label">Введите номер телефона без восьмерки (10 цифр)</p><input name="number" type="tel"
                ></label>
            <button type="submit">Отправить</button>
        </form>
    </body>
    <script>
        let send = false;
        $(document).on("beforeSubmit", "#phone", function (e) {
            e.preventDefault();
        }).on('submit', "#phone", function (e) {
            e.preventDefault();
            if(!send) {
                send = true;
                $.ajax({
                    type: 'post',
                    url: '/send',
                    data: $('#phone').serialize(),
                    success: function (data) {
                        // console.log(data);
                        if(data.code === true) {
                            $('#label').text('Введите код из СМС');
                            $('#phone')[0].reset();
                            // Второй запрос
                            $(document).on("beforeSubmit", "#phone", function (e) {
                                e.preventDefault();
                            }).on('submit', "#phone", function (e) {
                                e.preventDefault();
                                let newForm = document.getElementById("phone");
                                let newData = new FormData(newForm);
                                $.ajax({
                                    type: 'post',
                                    url: '/login',
                                    data: newData,
                                    processData: false,
                                    contentType: false,
                                    success: function (data) {
                                        if(data.code === true) {
                                            window.location.replace('/auth')
                                        } else {
                                            alert("Не верный код");
                                            window.location.replace('/')
                                        }
                                    },
                                    error: function (data) {
                                    }
                                });
                            });
                        } else {
                            alert(data.msg);
                            window.location.reload();
                        }
                        // console.log(data);
                    },
                    error: function () {
                    }
                });
            }
        });
    </script>

</html>
