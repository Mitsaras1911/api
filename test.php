<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="jquery.json-viewer.js"></script>
    <link href="jquery.json-viewer.css" rel="stylesheet">
    <script>
        $(document).ready(function(){

            //console
                $.ajax({
                    url: "/",
                    type: 'GET',
                    //dataType : "json",
                    //contentType: "application/json; charset=utf-8",
                    data: {user_id:22078},
                    success: function(data){
                        var x = JSON.parse(data);
                        console.log(x.name);


                }});
        });
    </script>
</head>
<body>

<div id="json"></div>
</body>
</html>