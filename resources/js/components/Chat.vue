<template>
  <div class="contenedor position-fixed">
    <div class="d-flex flex-row-reverse align-items-end">
      <div class="card my-0" style="box-shadow: 0px 18px 10px 0px black">
        <div class="d-flex flex-row justify-content-between adiv p-3 text-white">
          <i
            class="bi bi-people-fill zoom ease"
            style="font-size: 20px"
            @click="collapseListUsers()"
          ></i>
          <span class="pb-3">Usuarios conectados</span>
        </div>
        <div
          v-if="listUsersCollapse"
          style="max-height: 300px; overflow-y: scroll"
          class="style-1"
        >
          <div
            class="d-flex flex-row p-3 usuario"
            v-for="user in filteredUsers"
            :key="user.id"
            @click="abrirchat(user)"
          >
            <div class="img_cont">
              <img
                :src="'/' + user.image"
                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icons"
                style="width: 30px; height: 30px"
              />
              <span class="online_icon" v-if="user.userOnline"></span>
            </div>

            <p>{{ user.name }}</p>
          </div>
        </div>
        <div class="input-group">
          <span class="input-group-text">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-search"
              viewBox="0 0 18 18"
            >
              <path
                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"
              />
            </svg>
          </span>
          <input
            type="text"
            v-model="buscar"
            class="form-control"
            placeholder="Buscar usuario"
          />
        </div>
      </div>

      <div v-for="lista in listaChatsAbiertos" :key="lista.id">
        <ChatMessages
          :userId="lista.id"
          :userData="lista"
          :authId="authid"
          v-on:cerrarChat="cerrarChat"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ChatMessages from "./ChatMessages.vue";

export default {
  components: {
    ChatMessages,
  },
  props: ["authid"],
  mounted: function () {
    setTimeout(() => {
      this.obtenerUsuarios();
    }, 200);
    setInterval(() => {
      this.obtenerUsuarios();
    }, 18000);
  },
  data() {
    return {
      listUsersCollapse: false,
      showMessages: false,
      usuarios: [],
      listaChats: new Set(),
      listaChatsAbiertos: [],
      buscar: "",
    };
  },
  computed: {
    filteredUsers() {
      if (this.buscar) {
        return this.usuarios.filter((user) => {
          return user.name.toLowerCase().includes(this.buscar.toLowerCase());
        });
      } else {
        return this.usuarios;
      }
    },
  },

  methods: {
    collapseListUsers: function () {
      console.log(1);
      this.listUsersCollapse = !this.listUsersCollapse;
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
    showConversation: function (user) {
      this.showMessages = true;
    },
    abrirchat: function (id) {
      console.log(this.listaChats.size);
      if (this.listaChats.size < 3) {
        this.listaChats.add(id);
        this.listaChatsAbiertos = Array.from(this.listaChats);
        console.log(this.listaChats);
        this.listaChats.size;
      }
    },
    cerrarChat: function (id) {
      console.log(id);
      console.log("Click event on the button of the children with: " + id);
      this.listaChats.delete(id);
      this.listaChatsAbiertos = Array.from(this.listaChats);
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
  background: #61a5b5;
  border-radius: 15px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
  font-size: 12px;
  height: 46px;
}

.form-control {
  border: 1px solid #9e9e9e;
  font-size: 11px;
}

.form-control:focus {
  box-shadow: none;
}

.form-control::placeholder {
  font-size: 11px;
  color: #6c6c6c;
}
.contenedor {
  z-index: 100;
  right: 1px;
  bottom: 1px;
}
.online_icon {
  position: absolute;
  height: 15px;
  width: 15px;
  background-color: #4cd137;
  border-radius: 50%;
  bottom: 0.2em;
  right: 0.4em;
  border: 1.5px solid white;
}
.img_cont {
  position: relative;
  height: 35px;
  width: 40px;
}
.zoom:hover {
  transform: scale(1.3);
  transition: ease-in-out 0.5s;
}
.ease {
  transition: 1s ease-out;
}
.usuario:hover {
  background: #cacaca;
}
.style-1::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  border-radius: 10px;
  background-color: #f5f5f5;
}

.style-1::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.style-1::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #555;
}
</style>
