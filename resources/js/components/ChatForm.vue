<template>
  <div class="input-group">
    <textarea
      name="message"
      class="form-control type_msg"
      placeholder="Escribe tu mensaje..."
      v-model="newMessage"
      @keyup.enter="sendMessage"
    ></textarea>

    <span class="input-group-btn">
      <button class="btn btn-primary" id="btn-chat" @click="sendMessage">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-send-fill"
          viewBox="0 0 16 16"
        >
          <path
            d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"
          />
        </svg>
      </button>
    </span>
  </div>
</template>

<script>
export default {
  props: ["userId"],

  data() {
    return {
      newMessage: "",
    };
  },

  methods: {
    sendMessage: function () {
      axios
        .post("/chat/sendMessage", {
          message: this.newMessage,
          receiver_id: this.userId,
        })
        .then((response) => {
          /* console.log(response); */
        })
        .catch((e) => {
          /*  console.log(e); */
        });
      this.$emit("messagesent", {
        user: this.user,
        message: this.newMessage,
      });

      this.newMessage = "";
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

.form-control {
  border-radius: 10px;
  border: 1px solid #9e9e9e;
  font-size: 11px;
}

.form-control:focus {
  box-shadow: none;
}

.form-control::placeholder {
  font-size: 12px;
  color: #6c6c6c;
}
.type_msg {
  background-color: rgb(255, 255, 255) !important;
  border-block: 10 !important;
  color: rgb(0, 0, 0) !important;
  height: 40px !important;
  overflow-y: auto;
}
</style>
