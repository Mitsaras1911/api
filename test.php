<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
                $.ajax({
                    type: 'POST',
                    //dataType:'json',
                    //contentType: "application/json",
                    url: "/sign_in",
                    data:{
                        email:'a',
                        pass: '1234'
                    },
                    success: function(data){
                      //  var r = $.parseJSON(data);
                        //console.log(r);
                        console.log(typeof data);
                        console.log(data);

                }});
        });
    </script>
</head>
<body>

<div id="data"></div>

</body>
</html>