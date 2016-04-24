$( function function_name ( argument ) {

		var conn = new WebSocket('ws://localhost:8080/realtime');

		conn.onopen = function ( ) {
			conn.send( JSON.stringify(  { 'operation' : 'subscribe' , 'data' : { 'user' : 'kishanio', 'channel' : '1' } } ) );
            console.log("send");
		}

    	conn.onmessage = function( e ) {
    		console.log(JSON.parse(e.data));
    	};
} );
