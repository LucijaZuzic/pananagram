<template>
   <div class='container-fluid'>
        <br>
        <h1 class="display-4" >&#128145; Popis prijatelja</h1>
        <div class="d-inline-flex align-items-center p-2">
            <div class="p-2">
                <span>Prikaži: </span>
                <b-form-select style="display:inline-block; width: 75px"  v-model="page_length" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in filteredusers' v-bind:key = index>{{index + 1}}</option>
                </b-form-select>
            </div>
            <div class="p-2">
                <div class="d-inline-flex align-items-center p-2">
                    <div class="p-2"> 
                        &#128270;
                        <b-form-input autocomplete="off" id="regex" style="margin:5px;display:inline-block; width:225px" placeholder="Unesite kriterij za pretragu" type="text"></b-form-input>
                    </div>
                    <div class="p-2">
                        <b-button variant='outline-primary' v-on:click="addfilter()">Pretraži</b-button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="filteredusers.length">
            <table class="table table-hover table-striped text-center">                    
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>Status</th>
                        <th>Korisničko ime</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='index in user_array' v-bind:key = index>
                        <td title = "Admin" v-if="filteredusers[page_length * page_number + index].status === '1'">&#127775;</td>
                        <td title = "Korisnik" v-else>&#11088;</td>
                        <td>{{filteredusers[page_length * page_number + index].username}}</td>
                        <td>
                            <router-link title = "Informacije o korisniku" v-bind:to="{ name: 'friend_info', params: { username: filteredusers[page_length * page_number + index].username }}" style="color: black">
                                &#128712;
                            </router-link>
                        </td>
                        <td title = "Prekinite prijateljstvo" style="cursor: pointer"   v-on:click="unfriend(filteredusers[page_length * page_number + index].username)">&#128148;</td>
                        <td title = "Blokirajte korisnika" style="cursor: pointer"   v-on:click="block(filteredusers[page_length * page_number + index].username)">&#128683;</td>
                        <td title = "Promoviraj u admina" style="cursor: pointer"   v-if="canupgrade(filteredusers[page_length * page_number + index])" v-on:click="upgrade(filteredusers[page_length * page_number + index])">&#128081;</td>
                        <td v-else  ></td>
                    </tr>
                </tbody>
            </table> 
            <br>
            <table style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td  v-if="user_array.length > 1">Korisnici {{page_length * page_number + 1}}-{{Math.min(page_length * (page_number + 1), filteredusers.length)}} od {{filteredusers.length}}</td>
                    <td  v-else>Korisnik {{page_length * page_number + 1}} od {{filteredusers.length}}</td>
                    <td >Stranica {{page_number + 1}} od {{max_page_number + 1}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="previous()">&#9664;</b-button>
                            <b-button :class = "{ns: page_number === 0}" variant="outline-primary btn-sm" v-on:click="jumptopage(0)">1</b-button>
                            <b-button :class = "{ns: page_number === 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(1)" v-if="Math.ceil(max_page_number) > 2">2</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number) > 6">...</b-button>
                            <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number) > 5 && Math.ceil(max_page_number) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}}</b-button>
                            <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number) > 3">{{Math.ceil(Math.ceil(max_page_number + 1) / 2)}}</b-button>
                            <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2)}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2))"  v-if="Math.ceil(max_page_number) > 4">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number) > 6">...</b-button>
                            <b-button :class = "{ns: page_number === Math.ceil(max_page_number) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(max_page_number) - 1)" v-if="Math.ceil(max_page_number) > 1">{{Math.ceil(max_page_number)}}</b-button>
                            <b-button :class = "{ns: page_number === Math.ceil(max_page_number)}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(max_page_number))" v-if="Math.ceil(max_page_number) > 0">{{Math.ceil(max_page_number) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="next()">&#9654;</b-button>
                        </div>
                    </td>
                </tr>
            </table> 
        </div>
        <div v-else>
            <p >Pretraga pomoću zadanog kriterija nije dala rezultate. Izmjenite kriterij i pokušajte ponovno.</p>
        </div>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'FriendList',
  created() {
    this.getall();
    document.body.style.backgroundColor = "white";    
    document.title = "Pananagram - Lista prijatelja";
    if (!this.user.username) {
        this.$router.push("/puzzle_list");
    }
  },
  data() {
    return {
        user: user,
        users: [],
        filteredusers: [],
        friendships: [],
        page_length: 0,
        page_number: 0,
        filtervalue: "",
    }
  },
  methods: {
      getall() {
        axios.post('http://localhost/panagram/src/getusers.php', {
            request: 1
        })
        .then((response) => {
            this.users = response.data;
            axios.post('http://localhost/panagram/src/getfriends.php', {
                request: 1
            })
            .then((response) => {
                this.friendships = response.data;
                this.filteredusers = this.users;
                this.filter();
                this.page_length = Math.min(18, this.filteredusers.length);
            })
            .catch((error) => {
                console.log(error);
            });
        })
        .catch((error) => {
            console.log(error);
        });
      },
      next() {
          if (this.page_number < this.max_page_number) {
            this.page_number++;
          }
      },
      previous() {
          if (this.page_number > 0) {
            this.page_number--;
          }
      },
        addfilter() {
            var b = document.getElementById("regex").value;
            this.filtervalue = b;
            this.filter();
        },
        filter() {
            this.filteredusers = [];
            var i;
            for (i = 0; i < this.users.length; i++) {
                if (this.users[i].username.toLowerCase().match(this.filtervalue.toLowerCase()) && this.arefriends(this.users[i].username)) {
                    this.filteredusers.push(this.users[i]);
                }
            }
        },
      jumptopage(page) {
          this.page_number = page;
      },
      arefriends(b) {
            var i;
            for (i = 0; i < this.friendships.length; i++) {
                if (this.friendships[i].username1 == this.user.username && this.friendships[i].username2 == b) {
                    return true;
                }
                if (this.friendships[i].username2 == this.user.username && this.friendships[i].username1 == b) {
                    return true;
                }
            }
            return false;
      },
      upgrade(b) {
          axios.post('http://localhost/panagram/src/getusers.php', {
                request: 4,
                userId: b.userId,
                username: b.username,
                status: 1,
                visibility: b.visibility,
            })
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                console.log(error);
            });
      },
      unfriend(b) {
        axios.post('http://localhost/panagram/src/getfriends.php', {
            request: 4,
            username1: this.user.username,
            username2: b
        })
        .then(() => {
            window.alert("Prekinuli ste prijateljstvo.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
      },
      canupgrade(b) {
          if (this.user.status === '1' && b.status !== '1') {
              return true;
          }
      },
    block(b) {
        axios.post('http://localhost/panagram/src/getblocked.php', {
            request: 4,
            blocker: this.user.username,
            blocked: b
        })
        .then(() => {
            axios.post('http://localhost/panagram/src/getfriends.php', {
                request: 4,
                username1: this.user.username,
                username2: b
            })
            .then(() => {
                axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                    request: 5,
                    requester: this.user.username,
                    requested: b
                }) 
                .then(() => {
                    axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                        request: 5,
                        requested: this.user.username,
                        requester: b
                    }) 
                    .then(() => {
                        window.alert("Korisnik je blokiran.");
                        window.location.reload();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
            })
            .catch((error) => {
                console.log(error);
            });
        })
        .catch((error) => {
            console.log(error);
        });
    }
  },
  computed: {
    max_page_number: {
        get: function() {
            return Math.ceil(this.filteredusers.length / this.page_length) - 1; 
        },
        set: function() {
            return Math.ceil(this.filteredusers.length / this.page_length) - 1; 
        }
    },
    user_array: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.filteredusers.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.filteredusers.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
  },
  watch: {
      page_length: function() {
          if (this.filteredusers.length == 0) {
              this.page_length = 0;
              return;
          }
          if (this.page_length < 1) {
              this.page_length = Math.min(18, this.filteredusers.length);
          }
          if (this.page_length > this.filteredusers.length ) {
              this.page_length = 1;
          }
          if (this.page_number > this.max_page_number) {
              this.page_number = this.max_page_number;
          }
      }
  }
}

</script>


<style scoped src="./pananagram.css">
</style>

 