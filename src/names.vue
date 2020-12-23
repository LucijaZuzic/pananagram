<template >
  <div >
    <div v-for="user in users" :key="user.userId"  >
     <h1> {{user.username}} </h1>
      <p> {{user.password}} </p>
    </div><button :click="fetchUsers()"> Click me!</button></div>
</template>

<script>
export default {
  name: 'Names',
  data () {
    return {
      users: []
    }
  },
  methods: {
    fetchUsers: function () {
      const baseURI = 'http://localhost/Untitled-2.php'
      this.$http.get(baseURI)
      .then((result) => {
        this.users = result.data
        console.log(result.data)
      })
    }
  }
}
</script>
  