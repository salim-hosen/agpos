import axios from "axios";

// Create an Axios instance
const apiClient = axios.create({
  baseURL: "/api", // Base URL for Laravel API
  timeout: 10000,  // Request timeout (10 seconds)
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default apiClient;
