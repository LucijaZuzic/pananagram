<template>
    <div v-if="numwords >= 11 && numwords <= 15">           
      <table style="width: 100%">
        <tr>
          <td>
            <span class="description">Označi greške: </span>
            <label class="switch">                    
              <input type="checkbox" v-model="highlight_error"> 
              <span class="toggle round"></span>
            </label>
          </td>
          <td><div float="right" id="circle"><span class="description">{{format(time_elapsed)}}</span></div></td>
        </tr>
        <tr>   
          <td colspan="2">
            <span class="descriptionnumber">{{selected_word}}</span>
            <!--<div class="dropdown">
                <button class="dropbtn3 wordnumber">{{selected_word}}</button>
                <div class="dropdown-content3">
                    <label v-for="size in Array(this.numwords).keys()" :key="size" class="updown">
                        <input type="radio" name="page_size" v-on:change="selected_word=size + 1; check_color()" :value="size"><span class="description checkmark">{{size + 1}}</span> 
                    </label>
                </div>
            </div>--> 
            <table style="height: 20px; lineheight: 10px; font-size: 10px; display: inline-block; border-collapse: collapse">
                <tr>
                    <td>
                        <span class="goup" v-on:click="selected_word += 1">
                            &#9650;
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="godown" v-on:click="selected_word -= 1">
                            &#9660;
                        </span>
                    </td>
                </tr>
            </table>
            <label class = "description"> {{descriptions[selected_word - 1]}}</label>
          </td>
        </tr>
        <tr> 
          <td colspan="2">
            <button v-for="(syllable, index) in syllables" :key="index" oncontextmenu="return false;" v-on:click.left="select_syllable(index)" v-on:click.right="remove_syllable(index)"
            :class="{ choice: true, used : position[index] !== -1, selected: selected === index }"> 
              {{syllable}} 
            </button>
          </td>
        </tr>
        <tr> 
          <td colspan="2"> 
            <label class="description">
              {{intro[0]}}
            </label>
          </td>
        </tr>
        <tr> 
          <td colspan="2">      
            <label class="description author">
              {{intro[1]}}
            </label>  
          </td>
        </tr>
        <tr> 
          <td colspan="2">      
            <table class="solution left">
                <tr>
                    <th class = "wordnumber" v-for="col in Array(numwords).keys()" :key = "col">
                        <label v-on:click="selectword(col)">{{col + 1}}</label>
                    </th>
                </tr>
                <tr v-for="row in Array(max_word_len).keys()" :key = "row">
                    <td :class="{selectedword: col == selected_word - 1, rowwithhint : row === row_with_hint - 1, tabledata : true, 
                      incorrect : result[num_order[row * numwords + col] - 1] !== solution[num_order[row * numwords + col] - 1] && result[num_order[row * numwords + col] - 1] && highlight_error  
                      , dashedbottom: borders[row * numwords + col] === 2 || borders[row * numwords + col] === 4 || borders[row * numwords + col] === 7
                      , dashedtop: borders[(row - 1) * numwords + col] === 2 || borders[(row - 1) * numwords + col] === 4  || borders[(row - 1) * numwords + col] === 7
                      , dashedright: borders[row * numwords + col] === 1 || borders[row * numwords + col] === 4 || borders[row * numwords + col] === 5
                      , dashedleft: borders[row * numwords + col - 1] === 1 || borders[row * numwords + col - 1] === 4 || borders[row * numwords + col - 1] === 5
                      , breakbottom: borders[row * numwords + col] === 3 || borders[row * numwords + col] === 5 || borders[row * numwords + col] === 8
                      , breaktop: borders[(row - 1) * numwords + col] === 3 || borders[(row - 1) * numwords + col] === 5 || borders[(row - 1) * numwords + col] === 8
                      , breakright: borders[row * numwords + col] === 6 ||  borders[row * numwords + col] === 7 ||  borders[row * numwords + col] === 8
                      , breakleft: borders[row * numwords + col - 1] === 6 || borders[row * numwords + col - 1] === 7 || borders[row * numwords + col - 1] === 8
                      , Rfaded: solution[num_order[row * numwords + col] - 1] === '-'}"   
                      v-for="col in Array(numwords).keys()" :key = "col" v-on:click.right="remove_at_position(row, col)" style="cursor: pointer" v-on:click.left="place(row * numwords + col)" oncontextmenu="return false;">
                        <sup class="letternumber">{{num_order[row * numwords + col]}}</sup><span class="letter">{{result[num_order[row * numwords + col] - 1]}}</span>       
                    </td>
                </tr>
            </table>
            <table class="solution right">  
                <tr v-for="row in Array(max_word_len).keys()" :key = "row">
                  <td :class="{ selectedword: check_color(row, col), tabledata : true, 
                  incorrect : result[row * numwords + col] !== solution[row * numwords + col] && result[row * numwords + col] && highlight_error
                  , dashedright: barriers[row * numwords + col] === 2, dashedleft: barriers[row * numwords + col - 1] === 2
                  , boldright: barriers[row * numwords + col] === 1
                  , breakright: barriers[row * numwords + col] === 3, breakleft: barriers[row * numwords + col - 1] === 3,
                  LFaded: solution[row * numwords + col] === '-'}" 
                  v-for="col in Array(numwords).keys()" :key = "col">
                      <sup class = "letternumber">{{row * numwords + col + 1}}</sup><span class="letter">{{result[row * numwords + col]}}</span>
                  </td>
                </tr>      
            </table>
          </td>
        </tr>  
        <tr>
          <td colspan="2">
            <button class="submit" v-on:click="check_victory()">
              Provjeri rješenje
            </button>
          </td
        ></tr>
      </table>
    </div>
</template>

<script>
import { alphabet } from './App.vue'
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'Solve',
  created() {
    document.title = "Pananagram - Riješi zagonetku " + this.$route.params.id;
      document.body.style.backgroundColor = "white";
  },
  data() {
    this.getall();
    return {
      intro: ["", ""],
      descriptions: [],
      num_order: [],
      highlight_error: this.startup(),
      numwords: 0,
      word: [],
      result: [],
      syllables: [],
      selected: -1,
      position: [],
      solution: [],
      max_word_len: 0,
      selected_word: 1,
      row_with_hint: 0,
      barriers: [],
      borders: [],
      time_elapsed: 0,
      max_id: 0,
    }
  },
  methods: {
    getall() {
      axios.post('http://localhost/panagram/src/getpuzzles.php', {
        request: 5,
        id: this.$route.params.id.toString(),
      })
      .then((response) => {
        this.puzzles = response.data;
        var i;
        var found = false;
        for (i = 0; i < response.data.length; i++) {
          if (response.data[i].id.toString() === this.$route.params.id.toString()) {
            this.num_order = JSON.parse(this.puzzles[i].num_order);
            this.solution = JSON.parse(this.puzzles[i].solution);
            this.word = JSON.parse(this.puzzles[i].word);
            this.descriptions = JSON.parse(this.puzzles[i].descriptions);
            this.borders = JSON.parse(this.puzzles[i].borders);
            this.barriers = JSON.parse(this.puzzles[i].barriers);
            this.intro = JSON.parse(this.puzzles[i].intro);
            this.numwords = parseInt(this.puzzles[i].numwords);
            this.max_word_len = parseInt(this.puzzles[i].max_word_len);
            this.row_with_hint = parseInt(this.puzzles[i].row_with_hint);
            this.position = [...Array(this.word.length).fill(-1)];
            found = true;
            this.syllables = [...this.sort(this.word)];
            break;
          }
        }
        if (!found) {
          window.alert("Ne postoji zagonetka s tim brojem.");
          this.$router.push("/puzzle_list");
        }
      })
      .catch((error) => {
          console.log(error);
      });
    },
    startup () {
      var c = window.confirm("Želite li da greške budu uznačene?");
      this.startTimer();
      return c;
    },
    find_letter(a) {
      var j;
      for (j = 0; j < alphabet.length; j++) {
        if (alphabet[j] === a) {
          return j;
        }
      }
      return 0;
    },
    compare(a, b) {
      var i;
      for (i = 0; i < Math.max(a.length, b.length); i++) {
        if (i >= a.length) {
          return 0;
        }
        if (i >= b.length) {
          return 1;
        }
        var posa = this.find_letter(a[i]);
        var posb = this.find_letter(b[i]);
        if (posa > posb) {
          return 1;
        }
        if (posb > posa) {
          return 0;
        }
      }
      return 0;
    },
    sort(w) {
      var i = 0;
      var j;
      var s = [];
      for (j = 0; j < this.numwords * this.max_word_len; j++) {
        var s1 = "";
        for (i = 0; i < w[j].length; i++) {
          if (w[j][i] !== "-") {
            s1 += w[j][i];
          }
        }
        if (s1!== "") {
          s.push(s1);
        }
      }
      i = 0;
      j = 0;
      while (i < s.length) {
        for (j = 0; j < s.length - i - 1; j++) {                    
          if (this.compare(s[j], s[j + 1]) == 1) {              
            var tmp = s[j];
            s[j] = s[j + 1];
            s[j + 1] = tmp;
          }
        }
        i++;
      }
      return s;
    },
    select_syllable: function(index) {
      this.selected = index;
    },
    place: function(start) {
      if (this.selected === -1) {
        return;
      }
      var end = start + (this.syllables[this.selected].length - 1) * this.numwords;
      if (end >= this.numwords * this.max_word_len) {
        return;
      }    
      var i = 0;
      while (start + i * this.numwords < this.num_order.length && i < this.syllables[this.selected].length) {
        if (this.solution[this.num_order[start + i  * this.numwords] - 1] === '-') {
          return;
        }
        i++;
      }   
      var j = 0;
      for (j = 0; j < this.position.length; j++) {
        if (this.position[j] !== -1) {
          var overlap = 0;
          var k = 0;
          while (k < this.syllables[j].length) {
            if (this.position[j] + k * this.numwords === start || this.position[j] + k * this.numwords === end) {
              overlap = 1;
              break;
            }
            k++;
          }
          if (overlap === 1) {
            this.remove_syllable(j);
          }
        }
      }      
      if (this.position[this.selected] !== -1) {
        this.remove_syllable(this.selected);
      }
      this.position[this.selected] = start;
      i = 0;
      while (start + i * this.numwords < this.num_order.length && i < this.syllables[this.selected].length) {
        this.result[this.num_order[start + i  * this.numwords] - 1] = this.syllables[this.selected].charAt(i);
        i++;
      }      
      this.$forceUpdate();
    },
    check_victory() {
      var i = 0;
      var j = 0;
      for (i = 0; i < this.max_word_len; i++) {
        for (j = 0; j < this.numwords; j++) {
          if (this.result[i * this.numwords + j] !== this.solution[i * this.numwords + j] && this.solution[i * this.numwords + j] !== '-') {
            window.alert("Rješenje nije točno, pokušajte ponovno!")
            return false;
          }
        }
      }
      if (user.userId == "") {
        window.alert("Niste ulogirani pa se ne može spremiti rezultat.");
        this.$router.push("/victory");
        return;
      }
      axios.post('http://localhost/panagram/src/getrecords.php', {
        request: 1
      })
      .then((response) => {
        this.records = response.data;
        this.max_id = 0;
        var i;
        for (i = 0; i < response.data.length; i++) {
          if (parseInt(this.records[i].scoreid) > this.max_id) {
            this.max_id = parseInt(this.records[i].scoreid);
          }
        }
        this.max_id++;
        window.alert(this.max_id);
        axios.post('http://localhost/panagram/src/getrecords.php', {
          request: 2,
          scoreid: this.max_id,
          id: parseInt(this.$route.params.id),
          userId: user.userId,
          score: this.time_elapsed
        })
        .then(() => {
          window.alert("Točno ste riješili zagonetku.");
          this.$router.push("/victory")
        })
        .catch((error) => {
          console.log(error);
        });
      })
      .catch((error) => {
          console.log(error);
      });
    },
    remove_at_position(row, col) {
      var j = 0;
      for (j = 0; j < this.position.length; j++) {
        if (this.position[j] !== -1) {
          var overlap = 0;
          var k = 0;
          while (k < this.syllables[j].length) {
            if (this.position[j] + k * this.numwords === row * this.numwords + col) {
              overlap = 1;
              break;
            }
            k++;
          }
          if (overlap === 1) {
            this.remove_syllable(j);
          }
        }
      }   
    },
    remove_syllable(index) {
      var r = 0;
      while (r < this.syllables[index].length) {
        this.result[this.num_order[this.position[index] + r * this.numwords] - 1] = '';
        r++;
      }
      this.position[index] = -1;
      this.$forceUpdate();
    },
    check_color(row, col) {
      this.color = [...Array(this.numwords * this.max_word_len).fill(false)];
      var i = 0;
      for (i = 0; i < this.max_word_len; i++) {
        this.color[this.num_order[i * this.numwords + this.selected_word - 1] - 1] = true;
        if (this.num_order[i * this.numwords + (this.selected_word - 1)] - 1 === row * this.numwords + col) {
          return true;
        }
      }
    },
    startTimer() {
      this.time_elapsed = setInterval(() => (this.time_elapsed += 1), 1000);
    },
    format(time) {
      var hours = Math.floor(time / 3600);
      var minutes = Math.floor((time - hours * 3600) / 60);
      var seconds = time - hours * 3600 - minutes * 60;
      if (seconds < 10) {
        seconds = "0" + seconds;
      }
      if (minutes < 10) {
        minutes = "0" + minutes;
      }
      if (hours < 10) {
        hours = "0" + hours;
      }
      return hours + ":" + minutes + ":" + seconds;
    },
    selectword(col) {
      this.selected_word = col + 1;
    }
  },
  watch: {
    selected_word: function() {
        if (this.selected_word > this.numwords) {
            this.selected_word = 1;
        }
        if (this.selected_word < 1) {
            this.selected_word = this.numwords;
        }
    },
    '$route.params.id': function () {window.location.reload();}
  }
}
</script>

<style scoped src="./pananagram.css">
</style>