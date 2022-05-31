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
          class="bi bi-send"
          viewBox="0 0 16 16"
        >
          <path
            d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"
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
          console.log(response);
        })
        .catch((e) => {
          console.log(e);
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
  border: 2px solid #9e9e9e;
  font-size: 11px;
}

.form-control:focus {
  box-shadow: none;
}

.form-control::placeholder {
  font-size: 11px;
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
