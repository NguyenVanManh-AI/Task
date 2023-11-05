<template>
  <div>
    <router-view></router-view>
    <Notification></Notification>
  </div>
</template>

<script>

import Notification from './components/Notification'

export default {
  name: 'App',
  components:{
    Notification,
  },
  data(){
    return {

    }
  },
  mounted(){

  }
}
</script>

<style scoped>
::-webkit-scrollbar { /*toàn bộ ô cuộn*/
  width: 13px;
  
}

/* Track */
::-webkit-scrollbar-track { 
  background: white;  /*màu nền của ô cuộn */
}
 
/* Handle */
::-webkit-scrollbar-thumb { /*thanh cuộn nằm trong ô cuộn*/
    background: #888;  
    border-radius: 10px;
    border: 3px solid transparent; /*HAY*/
    background-clip: content-box;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover { /*thanh cuộn nằm trong ô cuộn khi hover vào */
    background: #0085FF; 
    /*background: #555; */
    border-radius: 10px;
    border: 3px solid transparent;
    background-clip: content-box;
}

.max {
  /* height: 526px; */
  height: 495px;
}
.min {
  /* height: 490px; */
  height: 459px;
}
#view{
  display: grid;
  width: 100%;
  overflow-y: scroll;
}
#view::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
#view {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  /* color: #2c3e50; */
}
</style>
