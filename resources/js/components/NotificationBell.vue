<template>
  <div class="notification-drop">
    <ul>
      <li class="item">
        <i class="fa fa-bell-o notification-bell" aria-hidden="true"></i>
        <span class="btn__badge pulse-button">{{ countNotifications }}</span>
        <ul
          class="list-group style-1 hover-cont"
          style="max-height: 300px; overflow-y: scroll"
        >
          <li class="dropdown-header">Notificaciones</li>
          <li
            class="d-flex flex-row p-3 hoverlist justify-content-between align-items-center"
            v-for="(notification, index) in notifications"
            :key="index"
          >
            <!-- <div class="img_cont">
              <img
                :src="'/' + notification.data.image"
                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icons"
                style="width: 25px; height: 25px"
              />
            </div> -->
            <div>
              <span style="font-weight: 750">
                {{ notification.data.transmitter_name }}</span
              >
              <br />
              <span style="font-weight: 800"> Mensaje nuevo: </span>
              {{ notification.data.message }}
            </div>
            <div class="close-notification">
              <a :href="`/chat/eliminarNotificacion/${notification.id}`" style="ml-60px"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-x-circle"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
                  />
                  <path
                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                  /></svg
              ></a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  mounted() {
    window.Echo.channel("chat").listen("MessageSent", (e) => {
      /* console.log("Notificacion guardada");
      console.log(e); */
    });
      this.obtenerMensajes();
  },
  data() {
    return {
      notifications: [],
      countNotifications: [],
    };
  },
  methods: {
    obtenerMensajes: function () {
      let u = axios
        .get("/chat/Notificaciones")
        .then((response) => {
          /* console.log(response); */
          this.notifications = response.data.notificationUnread;
          this.countNotifications = response.data.countNotifications;
        })
        .catch(function (error) {
          /*  console.log(error); */
        });
      /* console.log(u); */
    },
  },
};
$(document).ready(function () {
  $(".notification-drop .item").on("click", function () {
    $(this).find("ul").toggle();
  });
});
</script>

<style>
ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.notification-drop {
  font-family: "Ubuntu", sans-serif;
  color: #444;
}
.notification-drop .item {
  padding: 12px;
  font-size: 18px;
  position: relative;
  border-bottom: 1px solid #ddd;
}
.notification-drop .item:hover {
  cursor: pointer;
}
.notification-drop .item i {
  margin-left: 10px;
}
.notification-drop .item ul {
  display: none;
  position: absolute;
  top: 100%;
  background: #fff;
  left: -200px;
  right: 0;
  z-index: 1;
  border-top: 1px solid #ddd;
}
.notification-drop .item ul li {
  font-size: 12px;
  padding: 15px 0 15px 10px;
}
.notification-drop .item ul li:hover {
  background: #ddd;
  color: rgba(0, 0, 0, 0.8);
}

@media screen and (min-width: 500px) {
  .notification-drop {
    display: flex;
    justify-content: flex-end;
  }
  .notification-drop .item {
    border: none;
  }
}

.notification-bell {
  font-size: 20px;
}

.btn__badge {
  background: #ff5d5d;
  color: white;
  font-size: 12px;
  position: absolute;
  top: 0;
  right: 0px;
  padding: 3px 10px;
  border-radius: 50%;
}

.pulse-button {
  box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.5);
  -webkit-animation: pulse 3.5s infinite;
}

.pulse-button:hover {
  -webkit-animation: none;
}

@-webkit-keyframes pulse {
  0% {
    -moz-transform: scale(0.9);
    -ms-transform: scale(0.9);
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
  50% {
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -webkit-transform: scale(1);
    transform: scale(1);
    box-shadow: 0 0 0 20px rgba(255, 0, 0, 0);
  }
  100% {
    -moz-transform: scale(0.9);
    -ms-transform: scale(0.9);
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
    box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
  }
}

.notification-text {
  font-size: 14px;
  font-weight: bolder;
}

.notification-text span {
  float: right;
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
.hoverlist {
  box-shadow: #61a5b5 0px 0px 0px 1px;
}
.hover-cont {
  box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px,
    rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px,
    rgba(0, 0, 0, 0.09) 0px 32px 16px;
}
.dropdown-header {
  padding: 5px 20px 8px;

  color: #61a5b5;
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  font-family: "ABeeZee", sans-serif;
}
/* .close-notification {
  display: flex;
  float: right;
  padding: 10px;
} */
</style>
