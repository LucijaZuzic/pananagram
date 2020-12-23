<template>
  <div>   
    <table style="margin: 0 auto;">
      <tr>
        <td>
          <table class="frame">
            <tr>
              <td style="white-space: nowrap;">
                <span class="description">Korisničko ime: </span>
              </td>
              <td>
                <input class="register description" style="border: none; outline: none" type="text" v-model="dummyuser.username" /> 
              </td>
            </tr>
            <tr>
              <td style="white-space: nowrap;">
                <span class="description">Lozinka: </span>
              </td>
              <td style="white-space: nowrap;">
                <input class="register description" style="border: none; outline: none" id="pass" type="password" v-model="dummyuser.password" />
                <span class="eye description" style="text-decoration: line-through" id="eye" v-on:click="showpassword()">&#128065;</span>
              </td>
            </tr>
          </table><br> 
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td>
                <button class="submit" v-on:click="getall()">Prijavi se </button>
              </td>
            </tr><br> 
            <tr>
              <td>
                <span class="description">Još nemate račun?</span><router-link class="router" to="/register">Registracija</router-link>
              </td>
            </tr>
          </table> 
        </td>
      </tr>
    </table>
  </div>
</template>

<script>

import { user } from './App.vue';

import axios from 'axios'

export default {
  name: 'LogIn',
  created() {
      document.title = "Pananagram - Prijava";
      document.body.style.backgroundColor = "white";
      if (this.user.username) {
        window.alert("Već ste prijavljeni.");
        this.$router.push("/puzzle_list");
      }
  },
  data() {
    return {
        user: user,
        users: [],
        dummyuser:  {
                userId: "",
                username: "",
                password: "",
                status: "",
                visibility: "",
            }
    }
  },
  methods: {
      showpassword() {
        var x = document.getElementById("pass");
        if (x.type === "password") {
          x.type = "text";
          document.getElementById("eye").style.textDecoration = "none";
        } else {
          x.type = "password";
          document.getElementById("eye").style.textDecoration = "line-through";
        }
      },
      getall() {
        axios.post('http://localhost/panagram/src/getusers.php', {
          request: 2,
          username: this.dummyuser.username,
        })
        .then((response) => {
            this.users = response.data;
            this.find_user();
        })
        .catch((error) => {
            console.log(error);
        });
      },
      find_user() {
        var found = false;
        var i;
        for (i = 0; i < this.users.length; i++) {
            if (this.users[i].username === this.dummyuser.username && this.users[i].password === this.dummyuser.password) {
                found = true;
                this.user.username = this.dummyuser.username;
                this.user.password = this.dummyuser.password;
                this.user.userId = this.users[i].userId;
                this.user.status = this.users[i].status;
                this.user.visibility = this.users[i].visibility;
                this.dummyuser.userId = this.users[i].userId;
                this.dummyuser.status = this.users[i].status;
                this.dummyuser.visibility = this.users[i].visibility;
                sessionStorage.user = JSON.stringify(this.user);
            }
        }
        if (!found) {
            window.alert("Neispravno korisničko ime ili lozinka.")
            this.user.userId = "";
            this.user.username = "";
            this.user.password = "";
            this.user.status = "";
            this.user.visibility = "";
            this.dummyuser.userId = "";
            this.dummyuser.username = "";
            this.dummyuser.password = "";
            this.dummyuser.status = "";
            this.dummyuser.visibility = "";
            sessionStorage.user = JSON.stringify(this.user);
        } else {
            this.$router.push("/puzzle_list");
        }
     }
  }
}


</script>


<style scoped src="./pananagram.css">
</style>
