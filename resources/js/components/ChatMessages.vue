<template>
  <div class="card my-0">
    <div class="d-flex flex-row justify-content-between adiv p-3 text-white">
      <i class="bi bi-caret-down-square" @click="collapseChat()"></i>
      <span class="pb-3">Chat</span>
      <i class="fas fa-times" @click="cerrarChat"></i>
    </div>
    <div v-if="chatCollapse" style="height: 400px; overflow-y: auto">
      <div v-for="(mensaje, i) in messages" :key="i.id">
        <div
          class="d-flex flex-row p-3"
          :class="
            userId == mensaje.transmitter_id
              ? 'justify-content-start'
              : 'justify-content-end'
          "
        >
          <div
            class="chat ml-2 p-3"
            :class="
              userId == mensaje.transmitter_id ? 'chat ml-2 p-3' : 'bg-white mr-2 p-3'
            "
          >
            <span class="text-muted">{{ mensaje.message }}</span>
          </div>
          <img
            src="https://img.icons8.com/color/48/000000/circled-user-male-skin-type-7.png"
            width="30"
            height="30"
          />
        </div>
      </div>
    </div>
    <ChatForm :userId="userId" />
  </div>
</template>
<script>
import ChatForm from "./ChatForm.vue";
export default {
  components: {
    ChatForm,
  },
  props: ["userId"],
  mounted: function () {
    setTimeout(() => {
      this.obtenerMensajes();
    }, 200);
  },

  data() {
    return {
      chatCollapse: false,
      messages: [],
    };
  },
  methods: {
    collapseChat: function () {
      console.log(2);
      this.chatCollapse = !this.chatCollapse;
    },
    cerrarChat() {
      this.$emit("cerrarChat", this.userId);
    },
    obtenerMensajes: function () {
      let m = axios
        .get("/chat/fetchMessages/" + this.userId)
        .then((response) => {
          console.log("si llego bien", response);
          this.messages = response.data.mensajesEnviados;
        })
        .catch(function (error) {
          console.log(error);
        });
      console.log(m);
    },
  },
};
</script>
<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap");

body {
  background: #eeeeee;
  font-family: "Roboto", sans-serif;
}

.card {
  width: 300px;
  border: none;
  border-radius: 15px;
}

.adiv {
  background: #72c3d6;
  border-radius: 15px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
  font-size: 12px;
  height: 46px;
}

.chat {
  border: none;
  background: #e2fbff;
  font-size: 10px;
  border-radius: 20px;
}

.bg-white {
  border: 1px solid #e7e7e9;
  font-size: 10px;
  border-radius: 20px;
}

.myvideo img {
  border-radius: 20px;
}

.dot {
  font-weight: bold;
}

.form-control {
  border-radius: 12px;
  border: 1px solid #f0f0f0;
  font-size: 8px;
}

.form-control:focus {
  box-shadow: none;
}

.form-control::placeholder {
  font-size: 8px;
  color: #c4c4c4;
}
</style>
