<template>
  <div class="like-container">
    <span class="like-btn" @click="likePublication" :class="{ 'like-active': this.like }">
    </span>
     <span class="badge bg-danger">{{ cantidadLikes }} </span>
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

<style>
.badge{
  position:absolute;
  margin-left:-34px;
  margin-top: 40px;
}

.like-container{
  margin-left:-20px;
}
</style>