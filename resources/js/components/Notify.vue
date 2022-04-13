<template>
  <div>
    <audio id="audio" controls>
      <source type="audio/wav" src="/assets/audio/notification.mp3" />
    </audio>
  </div>
</template>

<script>
import toastr from "toastr";
export default {
  props: ["userId", "userData", "authId"],
  mounted() {
    window.Echo.channel("chat").listen("MessageSent", (e) => {
      console.log("Notificacion recibida");
      console.log(e);
      if (this.authId == e.receptor) {
        toastr.info(`${e.emisor}: ${e.message}`, "Mensaje");
        audio.play();
      }
    });
  },
  data: function () {
    return {
      message: "",
    };
  },
  /* methods: {
    notify() {
      if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
      } else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification("Hi there!");
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission(function (permission) {
          // If the user accepts, let's create a notification
          if (permission === "granted") {
            var notification = new Notification("Hi there!");
          }
        });
      }
    },
  }, */
};
</script>

<style scoped>
#audio {
  display: none;
}
</style>
