<template>
  <div class="card my-0">
    <div
      class="d-flex flex-row justify-content-between adiv p-3 text-white"
      @click="collapseChat()"
    >
      <i class="fas fa-chevron-left"></i> <span class="pb-3">Chat</span>
      <i class="fas fa-times"></i>
    </div>
    <div v-if="chatCollapse" style="height: 400px; overflow-y: auto">
      <div class="d-flex flex-row p-3">
        <img
          src="https://img.icons8.com/color/48/000000/circled-user-female-skin-type-7.png"
          width="30"
          height="30"
        />
        <div class="chat ml-2 p-3">
          Hello and thankyou for visiting birdlymind. Please click the video above
        </div>
      </div>
      <div class="d-flex flex-row p-3">
        <div class="bg-white mr-2 p-3">
          <span class="text-muted">Hello and thankyou for visiting birdlynind.</span>
        </div>
        <img
          src="https://img.icons8.com/color/48/000000/circled-user-male-skin-type-7.png"
          width="30"
          height="30"
        />
      </div>
      <div class="d-flex flex-row p-3">
        <img
          src="https://img.icons8.com/color/48/000000/circled-user-female-skin-type-7.png"
          width="30"
          height="30"
        />
        <div class="chat ml-2 p-3">
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquid, laborum vitae
          temporibus esse quia cupiditate blanditiis, cumque veritatis non neque eius,
          ducimus porro dignissimos! Perferendis cupiditate perspiciatis quasi vel iste.
        </div>
      </div>
      <div class="d-flex flex-row p-3">
        <div class="bg-white mr-2 p-3">
          <span class="text-muted"
            >Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquid, laborum
            vitae temporibus esse quia cupiditate blanditiis, cumque veritatis non neque
            eius, ducimus porro dignissimos! Perferendis cupiditate perspiciatis quasi vel
            iste.</span
          >
        </div>
        <img
          src="https://img.icons8.com/color/48/000000/circled-user-male-skin-type-7.png"
          width="30"
          height="30"
        />
      </div>

      <!-- <div class="d-flex flex-row p-3" v-for="message in messages" :key="message.id">
        <strong>
          {{ message.user.name }}
        </strong>
      </div>
      <p>
        {{ message.message }}
      </p> -->
      <ChatForm />
    </div>
  </div>
</template>
<script>
import ChatForm from "./ChatForm.vue";
export default {
  components: {
    ChatForm,
  },
  props: ["messages"],

  data() {
    return {
      chatCollapse: false,
    };
  },
  methods: {
    collapseChat: function () {
      console.log(2);
      this.chatCollapse = !this.chatCollapse;
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
