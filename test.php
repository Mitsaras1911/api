<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var data2={};
            data2.name = "Dimitris";
            data2.surname = "Christodoulou";


            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    data:data2
                    },
                success: function (data) {
                    alert(data);


                    }
            });
        });



    </script>
</head>
<body>

<div id="json"></div>
</body>
</html>