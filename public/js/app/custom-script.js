console.log("Hello from custom.js");

var pusher = new Pusher("c057d20025f341e583c4", {
  cluster: "eu",
});  

var channel = pusher.subscribe("my-channel");
channel.bind("my-event", function (data) {
  alert(JSON.stringify(data));
});
