<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testy</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function(event) {
           if(Utils.getCookie("session") == "") window.location = "login_page.php"
           else {
               $.ajax({
                    type: "POST",
                    url: "getusername.php",
                    data: {"token": Utils.getCookie("session")},
                    success: function(resp) {
                        $("#username").text(resp)
                    },
                });
           }

           $("#btnLogOut").on("click", function(event) {
                Utils.setCookie("session", "", -1000)
                window.location = window.location
           })
        })
    </script>
</head>

<body>
    <h1>Zalogowano jako: <span id="username">NIEZALOGOWANO</span></h1>
    <input type="button" id="btnLogOut" value="Wyloguj"/>
</body>

</html>