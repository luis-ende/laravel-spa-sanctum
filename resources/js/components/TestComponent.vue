<script setup>
import {onMounted, ref} from 'vue';

let strategies = ref([]);
const formData = ref({
  email: '',
  password: '',
});
const currentUser = ref({
  name: '',
  email: '',
})

onMounted(() => {
  getStrategies()
  getCurrentUser()
})

const handleLogin = () => {
  axios.get('/sanctum/csrf-cookie').then(response => {
    axios.post('/login', formData.value).then(response => {
      getStrategies();
      getCurrentUser();
    });
  });
}

const getStrategies = () => {
  axios.get('/api/strategy').then(response => {
    strategies.value = [];
    strategies.value.push(...response.data.data);
  });
}

const getCurrentUser = () => {
  try {
    axios.get('/api/user-info').then(response => {
      if (response.status === 200) {
        currentUser.value.name = response.data.first_name + response.data.last_name
        currentUser.value.email = response.data.email
      }
    })
  } catch (error) {
    console.log('Error: ' + error)
  }
}

const logout = () => {
  axios.post('/logout').then(response => {
    location.reload();
  })
}

</script>

<template>
  <div class="container">
    <div v-if="currentUser.name">
      <p>Usuario: {{ currentUser.name }} - {{ currentUser.email }}</p>
      <button @click="logout">Logout</button>
    </div>

    <!-- login form -->
    <div class="row mt-4" v-if="!strategies.length">
      <div class="col-6 offset-3">
        <form action="#" @submit.prevent="handleLogin">
          <h3>Sign in for strategies</h3>
          <div class="form-row">
            <input type="email" name="email" class="form-control" v-model="formData.email" placeholder="Email Address">
          </div>
          <div class="form-row">
            <input type="password" name="password" class="form-control" v-model="formData.password" placeholder="Password">
          </div>
          <div class="form-row">
            <button type="submit" class="btn btn-primary">Sign In</button>
          </div>
        </form>
      </div>
    </div>
    <!-- strategies list -->
    <div class="row mt-4" v-if="strategies.length">
      <div class="col-6 offset-3">
        <h3>My strategies</h3>
        <button class="btn btn-secondary" @click="getStrategies">Get strategies Again</button>
        <div class="strategy mb-4" v-for="strategy in strategies" :key="strategy.id">
          <div><strong v-text="strategy.type"></strong></div>
          <em v-text="strategy.created_at"></em><br>
        </div>
      </div>
    </div>
  </div>
</template>