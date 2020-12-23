<template>
   <div>
        <table class="description" width="100%" style='border-collapse: collapse'>
            <tr>
                <h1 class="description pagetitle">&#128221; Popis zagonetki</h1>
            <tr>
            <tr>
                <td>
                    <span class="description">Prikaži: </span>
                    <!--<input class="filter" type="number" min="1" :max="puzzles.length" v-model="page_length">-->
                    <!--<div class="dropdown">
                        <button class="dropbtn2">{{page_length}}</button>
                        <div class="dropdown-content2">
                            <label v-for="size in Array(Math.min(puzzles.length, 18)).keys()" :key="size" class="updown">
                                <input type="radio" name="page_size" v-on:change="page_length=size + 1" :value="size"><span class="description checkmark">{{size + 1}}</span> 
                            </label>
                        </div>
                    </div>-->
                    <span class="filter">{{page_length}}</span>
                    <table style="height: 20px; lineheight: 10px; font-size: 10px; display: inline-block; border-collapse: collapse">
                        <tr>
                            <td>
                                <span class="goup" v-on:click="page_length += 1">
                                    &#9650;
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="godown" v-on:click="page_length -= 1">
                                    &#9660;
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr><br>
            <tr>
                <td style="white-space:nowrap;">
                    <span class="description">Sortiraj po: </span>
                    <div class="dropdown">
                        <button class="dropbtn">{{sortcrit}}</button>
                        <div class="dropdown-content">
                            <label class="updown">
                                <input type="radio" name="kriterij" v-on:change="sortcrit='broju zagonetke'; sort()" value="1" checked="checked"> <span class="description checkmark">broju zagonetke</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="kriterij" v-on:change="sortcrit='broju riječi'; sort()" value="2"> <span class="description checkmark">broju riječi</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="kriterij" v-on:change="sortcrit='duljini riječi'; sort()" value="3"> <span class="description checkmark">duljini riječi</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="kriterij" v-on:change="sortcrit='izvoru'; sort()" value="4"> <span class="description checkmark">izvoru</span> 
                            </label>
                        </div>
                    </div>
                    <label class="updown">
                        <input name="direction" type="radio" value="1" v-on:change="sort()" checked="checked"> <span class="checkmark down"></span>
                    </label>
                    <label class="updown">
                        <input name="direction" type="radio" value="0" v-on:change="sort()"> <span class="checkmark up"></span>
                    </label>
                </td>
            </tr><br>
            <tr>
                <td style="white-space:nowrap;">
                    <span class="description">Pretraži po: </span>
                    <div class="dropdown">
                        <button class="dropbtn">{{filtercrit}}</button>
                        <div class="dropdown-content">
                            <label class="updown">
                                <input type="radio" name="filter" v-on:change="filtercrit='broju zagonetke'" value="1" checked="checked"> <span class="description checkmark">broju zagonetke</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="filter" v-on:change="filtercrit='broju riječi'" value="2"> <span class="description checkmark">broju riječi</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="filter" v-on:change="filtercrit='duljini riječi'" value="3"> <span class="description checkmark">duljini riječi</span> 
                            </label>
                            <label class="updown">
                                <input type="radio" name="filter" v-on:change="filtercrit='izvoru'" value="4"> <span class="description checkmark">izvoru</span> 
                            </label>
                        </div>
                    </div>
                    &#128270;
                    <input autocomplete="off" class="filter register" id="regex" placeholder="Unesite kriterij za pretragu" type="text">
                    <button class = "router" v-on:click="addfilter()">Dodaj</button>
                </td>
            </tr><br>
            <tr>
                <td>  
                    <span v-if="filters.length">
                        <span class="description">Kriteriji:</span>
                        <button class = "crit" style="cursor: context-menu" v-for='(filter, index) in filters' :key = index><span style="cursor: pointer" v-on:click = "remove_filter(index)">&#10062;</span> {{filter}}</button>
                        <br><br>
                        <button class = "clearcrit" v-on:click="filters = []; filtertype = []; filtervalue = []; filter()">Poništi sve</button>
                    </span>
                    <p v-else class = "description">
                        Nisu uneseni kriteriji za pretragu.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <div v-if="puzzles.length">
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr style="border-bottom: gray 2px solid;">
                                <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> zagonetke</span></th>
                                <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> riječi</span></th>
                                <th class="odd"><span style="white-space: nowrap">Duljina</span><span  style="white-space: nowrap"> riječi</span></th>
                                <th style="white-space: nowrap" class="odd">Izvor</th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                            </tr>
                            <tr class = "row" v-for='index in puzzle_array' v-bind:key = index>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzles[page_length * page_number + index].id}}</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzles[page_length * page_number + index].numwords}}</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzles[page_length * page_number + index].max_word_len}}</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzles[page_length * page_number + index].intro[1]}}</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}"><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: puzzles[page_length * page_number + index].id }}">&#9997;</router-link></td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: puzzles[page_length * page_number + index].id }}">&#127918;</router-link></td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title ="Pogledaj informacije o zagonetki"><router-link v-bind:to="{ name: 'puzzle_info', params: { id: puzzles[page_length * page_number + index].id }}" style="color: black">&#128712;</router-link></td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}"><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(puzzles[page_length * page_number + index].id)">&#10060;</span></td>
                            </tr>
                            <tr v-if="user.status === '1'">
                                <td :class="{even: page_length % 2 === 0, odd: page_length % 2 !== 0}" colspan="7"></td>
                                <td :class="{even: page_length % 2 === 0, odd: page_length % 2 !== 0}" title ="Stvori zagonetku"><router-link to="/create">&#10133;</router-link></td>		
                            </tr>
                        </table> 
                        <br>
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array.length > 1">Pananagramke {{page_length * page_number + 1}}-{{Math.min(page_length * (page_number + 1), puzzles.length)}} od {{puzzles.length}}</td>
                                <td class = "description" v-else>Pananagramka {{page_length * page_number + 1}} od {{puzzles.length}}</td>
                                <td class = "description">Stranica {{page_number + 1}} od {{max_page_number + 1}}</td>
                                <!--<td class = "numforward" v-on:click="begin()">&#9664;&#9664;</td>-->
                                <td class = "numforward" v-on:click="previous()">&#9664;</td>
                                <td :class = "{numforward: true, selected: page_number === 0}" v-on:click="jumptopage(0)">1</td>
                                <td :class = "{numforward: true, selected: page_number === 1}" v-on:click="jumptopage(1)" v-if="Math.ceil(max_page_number) > 2">2</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number) > 5 && Math.ceil(max_page_number) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number) > 3">{{Math.ceil(Math.ceil(max_page_number + 1) / 2)}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2)}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2))"  v-if="Math.ceil(max_page_number) > 4">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) + 1}}</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(max_page_number) - 1}" v-on:click="jumptopage(Math.ceil(max_page_number) - 1)" v-if="Math.ceil(max_page_number) > 1">{{Math.ceil(max_page_number)}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(max_page_number)}" v-on:click="jumptopage(Math.ceil(max_page_number))" v-if="Math.ceil(max_page_number) > 0">{{Math.ceil(max_page_number) + 1}}</td>
                                <td class = "numforward" v-on:click="next()">&#9654;</td>
                                <!--<td class = "numforward" v-on:click="end()">&#9654;&#9654;</td>-->
                            </tr>
                        </table> 
                    </div>
                    <div v-else>
                        <p class= "description">Pretraga pomoću zadanih kriterija nije dala rezultate. Uklonite neke kriterije i pokušajte ponovno.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'PuzzleList',
  created() {
    this.getall();
    document.body.style.backgroundColor = "white";    
    document.title = "Pananagram - Lista zagonetki";
  },
  data() {
    return {
        user: user,
        puzzles: [],
        page_length: 0,
        page_number: 0,
        filters: [],
        filtertype: [],
        filtervalue: [],
        sortcrit: "broju zagonetke",
        filtercrit: "broju zagonetke",
    }
  },
  methods: {
      delete_puzzle (x) {
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 4,
            id: parseInt(x),
        })
        .then(() => {
            window.alert("Zagonetka " + x + " je izbrisana.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
        this.getall();
        this.$forceUpdate();
      },
      getall() {
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 1
        })
        .then((response) => {
            this.puzzles = response.data;
            var i;
            for (i = 0; i < response.data.length; i++) {
                this.puzzles[i].id = parseInt(this.puzzles[i].id);
                this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
                this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
                this.puzzles[i].row_with_hint = parseInt(this.puzzles[i].row_with_hint);
                this.puzzles[i].num_order = JSON.parse(this.puzzles[i].num_order);
                this.puzzles[i].solution = JSON.parse(this.puzzles[i].solution);
                this.puzzles[i].word = JSON.parse(this.puzzles[i].word);
                this.puzzles[i].descriptions = JSON.parse(this.puzzles[i].descriptions);
                this.puzzles[i].borders = JSON.parse(this.puzzles[i].borders);
                this.puzzles[i].barriers = JSON.parse(this.puzzles[i].barriers);
                this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
            }
            this.page_length = Math.min(18, this.puzzles.length);
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
        compare(a, b) {
            if (document.getElementsByName("direction")[0].checked === true) {
                if (document.getElementsByName("kriterij")[0].checked === true) {
                    if (a.id > b.id) {
                        return 1;
                    } 
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (document.getElementsByName("kriterij")[1].checked === true) {
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.id > b.id) {
                        return 1;
                    } 
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (document.getElementsByName("kriterij")[2].checked === true) {
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.id > b.id) {
                        return 1;
                    }  
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (document.getElementsByName("kriterij")[3].checked === true) {
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 0;
                    }
                    if (a.id > b.id) {
                        return 1;
                    }  
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    return 0;
                }
            } else {
                if (document.getElementsByName("kriterij")[0].checked === true) {
                    if (a.id < b.id) {
                        return 1;
                    }  
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (document.getElementsByName("kriterij")[1].checked === true) {
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (document.getElementsByName("kriterij")[2].checked === true) {
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    }
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (document.getElementsByName("kriterij")[3].checked === true) {
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    if (a.intro[1] > b.intro[1]) {
                        return 0;
                    } 
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    return 0;
                }
            }
        },
        addfilter() {
            var b = document.getElementById("regex").value;
            if (document.getElementsByName("filter")[0].checked) {
                this.filters.push("Broj zagonetke: " + b);
                this.filtertype.push(0);
                this.filtervalue.push(b);
            }
            if (document.getElementsByName("filter")[1].checked) {
                this.filters.push("Broj riječi: " + b);
                this.filtertype.push(1);
                this.filtervalue.push(b);
            }
            if (document.getElementsByName("filter")[2].checked) {
                this.filters.push("Duljina riječi: " + b);
                this.filtertype.push(2);
                this.filtervalue.push(b);
            }
            if (document.getElementsByName("filter")[3].checked) {
                this.filters.push("Izvor: " + b);
                this.filtertype.push(3);
                this.filtervalue.push(b);
            }
            this.filter();
        },
        remove_filter(index) {
            var nf = [];
            var nft  = [];
            var nfv = [];
            var i;
            for (i = 0; i < this.filters.length; i++) {
                if (i != index) {
                    nf.push(this.filters[i]);
                    nft.push(this.filtertype[i]);
                    nfv.push(this.filtervalue[i]);
                }
            }
            this.filters = nf;
            this.filtertype = nft;
            this.filtervalue = nfv;
            this.filter();
        },
        filter() {
            axios.post('http://localhost/panagram/src/getpuzzles.php', {
                request: 1
            })
            .then((response) => {
                this.puzzles = response.data;
                var i;
                for (i = 0; i < response.data.length; i++) {
                    this.puzzles[i].id = parseInt(this.puzzles[i].id);
                    this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
                    this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
                    this.puzzles[i].row_with_hint = parseInt(this.puzzles[i].row_with_hint);
                    this.puzzles[i].num_order = JSON.parse(this.puzzles[i].num_order);
                    this.puzzles[i].solution = JSON.parse(this.puzzles[i].solution);
                    this.puzzles[i].word = JSON.parse(this.puzzles[i].word);
                    this.puzzles[i].descriptions = JSON.parse(this.puzzles[i].descriptions);
                    this.puzzles[i].borders = JSON.parse(this.puzzles[i].borders);
                    this.puzzles[i].barriers = JSON.parse(this.puzzles[i].barriers);
                    this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
                }
                var j = 0;
                for (j = 0; j < this.filters.length; j++) {
                    var b = this.filtervalue[j];
                    var x = this.filtertype[j];
                    var backup = this.puzzles;
                    this.puzzles = [];
                    for (i = 0; i < backup.length; i++) {
                        var a = backup[i];
                        if (x === 0 && a.id === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 1 && a.numwords === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 2 && a.max_word_len === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 3 && a.intro[1].toLowerCase().match(b.toLowerCase())) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                    }
                    if (this.puzzles.length === 0) {
                        break;
                    }
                }
                this.page_length = Math.min(18, this.puzzles.length);
                this.sort();
            })
            .catch((error) => {
                console.log(error);
            });
        },
        sort() {
            var i = 0;
            var j;
            while (i < this.puzzles.length) {
                for (j = 0; j < this.puzzles.length - i - 1; j++) {                    
                    if (this.compare(this.puzzles[j], this.puzzles[j + 1]) == 1) {              
                        var tmp = this.puzzles[j];
                        this.puzzles[j] = this.puzzles[j + 1];
                        this.puzzles[j + 1] = tmp;
                    }
                }
                i++;
            }
            this.$forceUpdate();
        },
      jumptopage(page) {
          this.page_number = page;
      }
  }, 
  computed: {
    max_page_number: {
        get: function() {
            return Math.ceil(this.puzzles.length / this.page_length) - 1; 
        },
        set: function() {
            return Math.ceil(this.puzzles.length / this.page_length) - 1; 
        }
    },
    puzzle_array: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.puzzles.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.puzzles.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
  },
  watch: {
      page_length: function() {
          if (this.puzzles.length == 0) {
              this.page_length = 0;
              return;
          }
          if (this.page_length < 1) {
              this.page_length = Math.min(18, this.puzzles.length);
          }
          if (this.page_length > this.puzzles.length ) {
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

 