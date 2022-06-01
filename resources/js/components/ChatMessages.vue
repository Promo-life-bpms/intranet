<template>
  <div class="card my-0 mx-3">
    <div class="d-flex flex-row justify-content-between adiv p-3 text-white">
      <i
        class="bi bi-caret-down-square"
        style="font-size: 18px"
        @click="collapseChat()"
      ></i>
      <span class="pb-3">{{ userData.name + " " + userData.lastname }}</span>
      <i class="fas fa-times" style="font-size: 18px" @click="cerrarChat()"></i>
    </div>
    <div v-if="chatCollapse">
      <div style="height: 300px; overflow-y: auto" id="formChat">
        <div v-for="(mensaje, i) in messages" :key="i.id">
          <div
            class="d-flex flex-row p-2"
            :class="
              userId == mensaje.transmitter_id
                ? 'justify-content-start'
                : 'justify-content-end'
            "
          >
            <img
              v-if="userId == mensaje.transmitter_id"
              :src="`/${userData.image}`"
              style="width: 25px; height: 25px"
              class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon"
            />

            <div
              class="chat ml-2 p-1"
              :class="
                userId == mensaje.transmitter_id ? 'bg-white ml-2 p-2' : 'chat mr-2 p-2'
              "
            >
              <span class="let">{{ mensaje.message }}</span>
              <div
                class="d-flex text"
                :class="
                  userId == mensaje.transmitter_id
                    ? 'justify-content-start'
                    : 'justify-content-end'
                "
              >
                <span>{{ mensaje.created_at.substring(11, 16) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <ChatForm :authId="authId" :userId="userId" v-on:cerrarChat="cerrarChat" />
    </div>
  </div>
</template>

<script>
import ChatForm from "./ChatForm.vue";
export default {
  components: {
    ChatForm,
  },
  props: ["userId", "userData", "authId"],
  mounted: function () {
    window.Echo.channel("chat").listen("MessageSent", (e) => {
      console.log("Evento recibido");
      console.log(e);
      if (this.authId == e.receptor) {
        this.messages.push({
          message: e.message,
          receiver_id: e.receptor,
          transmitter_id: e.emisor,
          created_at: e.created_at,
        });
        const objDiv = document.getElementById("formChat");
        objDiv.scrollTop = objDiv.scrollHeight;
        console.log(objDiv);
      }
      if (this.authId == e.emisor && e.receptor == this.userId) {
        this.messages.push({
          message: e.message,
          receiver_id: e.receptor,
          transmitter_id: e.emisor,
          created_at: e.created_at,
        });
        const objDiv = document.getElementById("formChat");
        objDiv.scrollTop = objDiv.scrollHeight;
        console.log(objDiv);
      }
    });
    setTimeout(() => {
      this.obtenerMensajes();
    }, 200);
  },

  data() {
    return {
      chatCollapse: true,
      messages: [],
    };
  },
  methods: {
    collapseChat: function () {
      console.log(2);
      this.chatCollapse = !this.chatCollapse;
      if (this.chatCollapse == true) {
        setTimeout(() => {
          const objDiv = document.getElementById("formChat");
          objDiv.scrollTop = objDiv.scrollHeight;
        }, 200);
      }
    },
    cerrarChat() {
      this.$emit("cerrarChat", this.userData);
    },
    obtenerMensajes: function () {
      let m = axios
        .get("/chat/fetchMessages/" + this.userId)
        .then((response) => {
          console.log("si llego bien", response);
          this.messages = response.data.mensajesEnviados;

          setTimeout(() => {
            const objDiv = document.getElementById("formChat");
            objDiv.scrollTop = objDiv.scrollHeight;
          });
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
.let {
  font-size: 11px;
}
</style>
