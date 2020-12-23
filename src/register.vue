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
                <button class="submit" v-on:click="getall()">Registriraj se </button>
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
  name: 'Register',
  created() {
      document.title = "Pananagram - Registracija";
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
                userId: 0,
                username: "",
                password: "",
                status: "0",
                visibility: "0",
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
        if (this.dummyuser.username === "" || this.dummyuser.password === "") {
          window.alert("Morate unijeti korisničko ime i lozinku.");
          return;
        }
        axios.post('http://localhost/panagram/src/getusers.php', {
          request: 1,
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
            if (parseInt(this.users[i].userId) > this.dummyuser.userId) {
                this.dummyuser.userId = parseInt(this.users[i].userId);
            }
            if (this.users[i].username === this.dummyuser.username) {
                found = true;
            }
        }
        this.dummyuser.userId++;
        if (found) {
            window.alert("Odabrano korisničko ime je već iskorišteno.");
            this.dummyuser.username = "";
            this.dummyuser.password = "";
        } else {
            axios.post('http://localhost/panagram/src/getusers.php', {
                request: 3,
                userId: this.dummyuser.userId,
                username: this.dummyuser.username,
                password: this.dummyuser.password,
                status: this.dummyuser.status,
                visibility: this.dummyuser.visibility,
            })
            .then(() => {
                this.user = this.dummyuser;
                sessionStorage.user = JSON.stringify(this.user);
                window.location.reload();
            })
            .catch((error) => {
                console.log(error);
            });
        }
     }
  }
}


</script>


<style scoped src="./pananagram.css">
</style>