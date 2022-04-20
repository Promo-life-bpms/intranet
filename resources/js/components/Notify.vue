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
        toastr.success(`${e.transmitter_name}: ${e.message}`, "Mensaje");
        audio.play();
      }
    });
  },
  data: function () {
    return {
      message: "",
    };
  },
};
</script>

<style scoped>
#audio {
  display: none;
}
</style>
