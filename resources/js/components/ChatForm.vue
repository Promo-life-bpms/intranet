<template>
  <div class="input-group">
    <input
      id="btn-input"
      type="text"
      name="message"
      class="form-control input-sm"
      placeholder="Escribe tu mnensaje"
      v-model="newMessage"
      @keyup.enter="sendMessage"
    />

    <span class="input-group-btn">
      <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
        Enviar
      </button>
    </span>
  </div>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      newMessage: "",
    };
  },

  methods: {
    sendMessage() {
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
