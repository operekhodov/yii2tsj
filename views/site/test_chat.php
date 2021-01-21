<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>
    $(function() {
        var chat = new WebSocket('ws://localhost:8080');
        chat.onmessage = function(e) {
            $('#response').text('');

            var response = JSON.parse(e.data);
            if (response.type && response.type == 'chat') {
                $('#chat').append('<div><b>' + response.from + '</b>: ' + response.message + '</div>');
                $('#chat').scrollTop = $('#chat').height;
            } else if (response.message) {
                $('#response').text(response.message);
            }
        };
        chat.onopen = function(e) {
            $('#response').text("Connection established! Please, set your username.");
        };
        $('#btnSend').click(function() {
            if ($('#message').val()) {
                chat.send( JSON.stringify({'action' : 'chat', 'message' : $('#message').val()}) );
            } else {
                alert('Enter the message')
            }
        })

        $('#btnSetUsername').click(function() {
            if ($('#username').val()) {
                chat.send( JSON.stringify({'action' : 'setName', 'name' : $('#username').val()}) );
            } else {
                alert('Enter username')
            }
        })
    })
</script>
Username:<br />
<input id="username" type="text"><button id="btnSetUsername">Set username</button>

<div id="chat" style="width:400px; height: 250px; overflow: scroll;"></div>

Message:<br />
<input id="message" type="text"><button id="btnSend">Send</button>
<div id="response" style="color:#D00"></div>