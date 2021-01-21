<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>
    $(function() {
    var socket = new WebSocket("ws://92.53.118.140:9501");

    document.getElementById("btn").onclick = function() {
        socket.send(JSON.stringify({
            method: "test",
            data : "some data"
        }));
    };

    socket.onmessage = function(event) {
        console.log(event);
        document.getElementById("got").innerText = event.data;
    };
    })
</script>