<template>
  <div class="d-flex justify-content-end position-fixed fixed-bottom">
    <div class="card my-0">
      <div
        class="d-flex flex-row justify-content-between adiv p-3 text-white"
        @click="collapseChat()"
      >
        <i class="fas fa-chevron-left"></i> <span class="pb-3">Usuarios conectados</span>
        <i class="fas fa-times"></i>
      </div>
      <div v-if="chatCollapse" style="max-height: 400px; overflow-y: scroll">
        <div class="d-flex flex-row p-3" v-for="user in usuarios" :key="user.id">
          <img
            :src="'/' + user.image"
            class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon"
            style="width: 30px; height: 30px"
          />
          <button class="btn">{{ user.name }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted: function () {
    setTimeout(() => {
      this.obtenerUsuarios();
    }, 200);
  },
  data() {
    return {
      chatCollapse: false,
      usuarios: [],
    };
  },
  methods: {
    collapseChat: function () {
      console.log(1);
      this.chatCollapse = !this.chatCollapse;
    },
    obtenerUsuarios: function () {
      let u = axios
        .get("/chat/obtenerUsuarios")
        .then((response) => {
          console.log(response);
          this.usuarios = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      console.log(u);
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
