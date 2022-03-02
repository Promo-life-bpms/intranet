<template>
  <div>
    <span class="like-btn" @click="likePublication" :class="{ 'like-active': this.like }">
    </span>
    <p>{{ cantidadLikes }} Les gusto esta publicación</p>
  </div>
</template>

<script>
export default {
  props: ["publicationId", "like", "likes"],
  data: function () {
    return {
      totalLikes: this.likes,
    };
  },
  methods: {
    likePublication() {
      axios
        .post("/social/publication/" + this.publicationId)
        .then((respuesta) => {
          if (respuesta.data.attached.length > 0) {
            this.$data.totalLikes++;
          } else {
            this.$data.totalLikes--;
          }
        })
        .catch((error) => {
          console.log(error.data);
        });
    },
  },
  computed: {
    cantidadLikes: function () {
      return this.totalLikes;
    },
  },
};
</script>
