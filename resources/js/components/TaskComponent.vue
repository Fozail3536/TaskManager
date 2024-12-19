<template>
    <div class="container mt-5">
      <h1 class="mb-4">Task Management</h1>
  
      <!-- Form to create a new task -->
      <form @submit.prevent="createTask">
        <div class="mb-3">
          <label for="title" class="form-label">Task Title</label>
          <input type="text" v-model="newTask.title" class="form-control" id="title" placeholder="Enter task title" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Task Description</label>
          <textarea v-model="newTask.description" class="form-control" id="description" placeholder="Enter task description"></textarea>
        </div>
        <div class="mb-3">
          <label for="due_date" class="form-label">Due Date</label>
          <input type="date" v-model="newTask.due_date" class="form-control" id="due_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Task</button>
      </form>
  
      <!-- Display all tasks -->
      <h2 class="mt-5">All Tasks</h2>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id">
            <td>{{ task.title }}</td>
            <td>{{ task.description }}</td>
            <td>{{ task.status }}</td>
            <td>{{ task.due_date }}</td>
            <td>
              <button class="btn btn-warning btn-sm" @click="editTask(task)">Edit</button>
              <button class="btn btn-success btn-sm" @click="markAsComplete(task)" v-if="task.status !== 'completed'">Mark Complete</button>
              <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        tasks: [],
        newTask: {
          title: '',
          description: '',
          due_date: ''
        },
        editingTask: null
      };
    },
    methods: {
      async fetchTasks() {
        const response = await axios.get('/tasks');
        this.tasks = response.data;
      },
      async createTask() {
        try {
          const response = await axios.post('/tasks', this.newTask);
          this.tasks.push(response.data);
          this.newTask = { title: '', description: '', due_date: '' };
        } catch (error) {
          console.error('Error creating task:', error.response.data);
        }
      },
      async deleteTask(taskId) {
        try {
          await axios.delete(`/tasks/${taskId}`);
          this.tasks = this.tasks.filter(task => task.id !== taskId);
        } catch (error) {
          console.error('Error deleting task:', error.response.data);
        }
      },
      async editTask(task) {
        this.newTask = { ...task };
        this.editingTask = task;
      },
      async updateTask() {
        try {
          const response = await axios.put(`/tasks/${this.editingTask.id}`, this.newTask);
          const index = this.tasks.findIndex(task => task.id === this.editingTask.id);
          this.tasks.splice(index, 1, response.data);
          this.newTask = { title: '', description: '', due_date: '' };
          this.editingTask = null;
        } catch (error) {
          console.error('Error updating task:', error.response.data);
        }
      },
      async markAsComplete(task) {
        try {
          const response = await axios.put(`/tasks/${task.id}`, { status: 'completed' });
          const index = this.tasks.findIndex(t => t.id === task.id);
          this.tasks.splice(index, 1, response.data);
        } catch (error) {
          console.error('Error marking task as complete:', error.response.data);
        }
      }
    },
    mounted() {
      this.fetchTasks();
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 900px;
  }
  </style>
  